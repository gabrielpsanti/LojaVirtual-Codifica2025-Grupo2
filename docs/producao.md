# Deploy do Projeto em Produção (com HTTPS)

Este guia cobre o **deploy do projeto Loja Virtual** em um servidor que **já tem o ambiente preparado** (Docker, Nginx do host, Certbot, firewall). Inclui clonar o repositório, configurar `.env`, subir os containers, expor com HTTPS e operação contínua (deploys e backups).

> Servidor ainda não preparado? Siga primeiro [`passo-a-passo.md`](./passo-a-passo.md). Para rodar localmente, veja [`desenvolvimento.md`](./desenvolvimento.md).

---

## Arquitetura do deploy

```
  Internet
     │
     ▼  :80 / :443
┌──────────────────────────┐
│  Nginx do HOST           │  ← termina TLS (certbot)
└──────────┬───────────────┘
           │  proxy_pass http://127.0.0.1:8088
           ▼
┌──────────────────────────┐
│  Container nginx         │
└──────────┬───────────────┘
           │  fastcgi → app:9000
           ▼
┌──────────────────────────┐
│  Container app (PHP-FPM) │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│  Container mysql         │
└──────────────────────────┘
```

> Por que Nginx **no host** (e não dentro do compose)? É a forma mais simples de operar `certbot`: o `python3-certbot-nginx` edita o vhost automaticamente, configura o redirect HTTP→HTTPS, e a renovação roda via systemd timer sem precisar reiniciar containers.

---

## Sumário

1. [Pré-requisitos do ambiente](#1-pré-requisitos-do-ambiente)
2. [Apontar o DNS do domínio](#2-apontar-o-dns-do-domínio)
3. [Clonar o projeto](#3-clonar-o-projeto)
4. [Configurar o `.env` de produção](#4-configurar-o-env-de-produção)
5. [Ajustar o `docker-compose.yml` para produção](#5-ajustar-o-docker-composeyml-para-produção)
6. [Subir os containers e preparar o Laravel](#6-subir-os-containers-e-preparar-o-laravel)
7. [Configurar o Nginx do host como proxy reverso](#7-configurar-o-nginx-do-host-como-proxy-reverso)
8. [Emitir o certificado HTTPS com Certbot](#8-emitir-o-certificado-https-com-certbot)
9. [Configurar TrustProxies no Laravel](#9-configurar-trustproxies-no-laravel)
10. [Renovação automática do certificado](#10-renovação-automática-do-certificado)
11. [Atualizar a aplicação (deploy contínuo)](#11-atualizar-a-aplicação-deploy-contínuo)
12. [Backup automático do banco](#12-backup-automático-do-banco)
13. [Acessar phpMyAdmin em produção (emergência)](#13-acessar-phpmyadmin-em-produção-emergência)
14. [Checklist final](#14-checklist-final)

---

## 1. Pré-requisitos do ambiente

Antes de continuar, confirme que a máquina já passou por [`passo-a-passo.md`](./passo-a-passo.md). Resumo do que tem que estar pronto:

- [ ] VPS com IP público **fixo**
- [ ] Portas **22 (seu IP), 80, 443** abertas no firewall do provedor
- [ ] Sistema atualizado, timezone `America/Sao_Paulo`, swap (se RAM ≤ 2 GB)
- [ ] **Docker** e **Docker Compose** instalados e rodando sem `sudo`
- [ ] **Nginx do host** instalado (`systemctl status nginx` → `active`)
- [ ] **Certbot** com plugin nginx instalado (`certbot --version`)
- [ ] **UFW** ativo com `OpenSSH` + `Nginx Full`

Se algum desses itens não está pronto, volte para [`passo-a-passo.md`](./passo-a-passo.md).

---

## 2. Apontar o DNS do domínio

No seu provedor de DNS (Registro.br, Cloudflare, GoDaddy, Route53, etc.), crie um registro:

| Tipo | Nome | Valor | TTL |
|---|---|---|---|
| `A` | `loja` (ou `@` para o domínio raiz) | IP fixo da VPS | 300 (5 min) |

Verifique do seu laptop:

```bash
dig loja.seudominio.com.br +short
# deve retornar o IP fixo da VPS
```

Se ainda não retornar, **espere mais alguns minutos** — sem DNS resolvendo, o Certbot falha mais à frente.

---

## 3. Clonar o projeto

```bash
sudo mkdir -p /var/www
sudo chown $USER:$USER /var/www
cd /var/www
git clone <URL-DO-REPOSITORIO-NO-GITHUB> lojavirtual
cd lojavirtual
```

> Se o repositório for **privado**, gere um Personal Access Token no GitHub e use HTTPS: `https://<token>@github.com/usuario/LojaVirtual-Codifica2025-Grupo2.git`. Outra opção é configurar uma deploy key SSH na própria VPS.

---

## 4. Configurar o `.env` de produção

```bash
cp .env.example .env
nano .env
```

Edite os blocos abaixo (no nano, use `Ctrl+W` para buscar):

```env
APP_NAME="Loja Virtual"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://loja.seudominio.com.br

LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=loja
DB_USERNAME=loja
DB_PASSWORD=TROQUE_POR_SENHA_FORTE_AQUI
DB_ROOT_PASSWORD=TROQUE_POR_OUTRA_SENHA_FORTE

APP_PORT=8088
SESSION_SECURE_COOKIE=true
```

**NÃO** adicione `COMPOSE_PROFILES=dev` — é isso que mantém o phpMyAdmin e o Vite desligados em produção.

Salve no nano: `Ctrl+O`, Enter, `Ctrl+X`.

> Para gerar senhas fortes rapidamente: `openssl rand -base64 24`.

---

## 5. Ajustar o `docker-compose.yml` para produção

O `docker-compose.yml` vem configurado para **desenvolvimento** — o container `nginx` bindando em `80` para acessar via `http://localhost/`. Em produção isso causa dois problemas:

1. Conflito com o **Nginx do host** que também escuta na 80 (vai dar `bind: address already in use` ao subir o container).
2. Mesmo se não conflitasse, o container ficaria exposto direto na internet — queremos que **só o Nginx do host** responda para fora.

A solução é mudar a linha de `ports` do serviço `nginx`:

```bash
nano docker-compose.yml
```

Localize o serviço `nginx` (`Ctrl+W` → digite `nginx:`). A linha atual é:

```yaml
      - "${APP_PORT:-80}:80"
```

Troque por:

```yaml
      - "127.0.0.1:${APP_PORT:-8088}:80"
```

Duas mudanças:
- **`127.0.0.1:`** na frente → só escuta no loopback (não fica acessível da internet)
- **default `8088`** em vez de `80` → evita conflito com o Nginx do host

Como `APP_PORT=8088` já está no `.env` (passo 4), o default é só uma rede de segurança. Salve e saia.

---

## 6. Subir os containers e preparar o Laravel

```bash
# Builda a imagem do app (PHP 8.4 + Composer + Node) — ~3 min na primeira vez
docker compose up -d --build

# Espera o MySQL ficar saudável (verde no STATUS)
docker compose ps   # esperar até aparecer "(healthy)" no mysql

# Dependências PHP (sem pacotes de dev, otimizado)
docker compose exec app composer install --no-dev --optimize-autoloader

# Gera a APP_KEY
docker compose exec app php artisan key:generate

# Roda as migrations
docker compose exec app php artisan migrate --force

# Cria o link simbólico storage/app/public → public/storage
docker compose exec app php artisan storage:link

# Dependências do front e build estático do Vite
docker compose exec app npm ci
docker compose exec app npm run build

# Caches do Laravel (produção)
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

Teste local (ainda sem domínio):

```bash
curl -I http://127.0.0.1:8088
# Deve retornar HTTP/1.1 200 OK ou 302 Found
```

Se der erro, confira os logs:

```bash
docker compose logs --tail=50 nginx
docker compose logs --tail=50 app
```

---

## 7. Configurar o Nginx do host como proxy reverso

```bash
sudo nano /etc/nginx/sites-available/lojavirtual
```

Cole (substituindo o domínio):

```nginx
server {
    listen 80;
    server_name loja.seudominio.com.br;

    client_max_body_size 64M;

    location / {
        proxy_pass http://127.0.0.1:8088;
        proxy_http_version 1.1;
        proxy_set_header Host              $host;
        proxy_set_header X-Real-IP         $remote_addr;
        proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host  $host;
        proxy_read_timeout 120s;
    }
}
```

Salve, ative o vhost e remova o default:

```bash
sudo ln -s /etc/nginx/sites-available/lojavirtual /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t                       # testa a sintaxe — deve dizer "syntax is ok"
sudo systemctl reload nginx
```

Teste pelo domínio (ainda em HTTP):

```bash
curl -I http://loja.seudominio.com.br
# Deve retornar HTTP/1.1 200 OK ou 302
```

Se retornar `502 Bad Gateway`, é porque o container `nginx` não está rodando — `docker compose ps` para diagnosticar.

---

## 8. Emitir o certificado HTTPS com Certbot

```bash
sudo certbot --nginx -d loja.seudominio.com.br
```

Vai perguntar:

1. **E-mail:** seu e-mail real (recebe avisos quando o certificado estiver perto de vencer).
2. **Termos:** digite `Y`.
3. **EFF mailing list:** `N` (opcional).
4. **Redirect HTTP → HTTPS:** escolha **2** (redirecionar todo o tráfego).

Saída final:

```
Successfully received certificate.
Certificate is saved at: /etc/letsencrypt/live/loja.seudominio.com.br/fullchain.pem
...
Deploying certificate
Successfully deployed certificate for loja.seudominio.com.br
```

Teste:

```bash
curl -I https://loja.seudominio.com.br
# HTTP/2 200
```

E abra `https://loja.seudominio.com.br` no navegador — deve aparecer o cadeado verde.

### 8.1 Múltiplos domínios / `www`

Para incluir `www.` no mesmo certificado:

```bash
sudo certbot --nginx -d loja.seudominio.com.br -d www.loja.seudominio.com.br
```

---

## 9. Configurar TrustProxies no Laravel

Como o TLS termina no Nginx do host, o container recebe HTTP puro. Sem confiar no proxy, o Laravel gera URLs com `http://` e cookies inseguros.

```bash
nano bootstrap/app.php
```

Procure o bloco `->withMiddleware(...)` e adicione:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

Salve. Refresque o cache:

```bash
docker compose exec app php artisan config:cache
```

---

## 10. Renovação automática do certificado

O pacote `certbot` já instala um **systemd timer** que renova certificados duas vezes por dia. Verifique:

```bash
systemctl list-timers | grep certbot
sudo certbot renew --dry-run
```

Se o `--dry-run` passar, está tudo certo. A renovação acontece em background, sem downtime — o Nginx é recarregado automaticamente.

Para forçar agora (raramente necessário):

```bash
sudo certbot renew
sudo systemctl reload nginx
```

---

## 11. Atualizar a aplicação (deploy contínuo)

Sempre que houver código novo no Git:

```bash
cd /var/www/lojavirtual
git pull
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app php artisan migrate --force
docker compose exec app npm ci
docker compose exec app npm run build
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose restart app
```

> Se mudou o `Dockerfile` ou o `docker-compose.yml`, rode `docker compose up -d --build` no lugar do `restart`.

---

## 12. Backup automático do banco

Crie a pasta de backups e agende o dump diário (3h da manhã, retenção 14 dias):

```bash
sudo mkdir -p /var/backups/lojavirtual
sudo chown $USER:$USER /var/backups/lojavirtual

# Abre o crontab do usuário atual
crontab -e
```

Adicione no final do arquivo:

```cron
0 3 * * * cd /var/www/lojavirtual && /usr/bin/docker compose exec -T mysql mysqldump -uroot -p"$(grep ^DB_ROOT_PASSWORD .env | cut -d= -f2)" loja | gzip > /var/backups/lojavirtual/loja-$(date +\%F).sql.gz && find /var/backups/lojavirtual -name 'loja-*.sql.gz' -mtime +14 -delete
```

Salve e saia. Teste rodando manualmente uma vez:

```bash
cd /var/www/lojavirtual && docker compose exec -T mysql mysqldump -uroot -p"$(grep ^DB_ROOT_PASSWORD .env | cut -d= -f2)" loja | gzip > /var/backups/lojavirtual/loja-test.sql.gz
ls -lh /var/backups/lojavirtual/
```

> Para **backups duráveis** (fora da VPS): envie esses .gz para storage externo — S3 (AWS), Spaces (DigitalOcean), R2 (Cloudflare), GCS (Google) — com `aws s3 cp` ou `rclone`. Outra opção é usar um **banco gerenciado** do provedor (RDS, Cloud SQL, DO Managed DB, etc.), que vem com snapshots automáticos.

---

## 13. Acessar phpMyAdmin em produção (emergência)

Como explicado em [`desenvolvimento.md`](./desenvolvimento.md), o serviço `phpmyadmin` está atrás do profile `dev` e bindado em `127.0.0.1` — em produção ele **não sobe** porque o `.env` não tem `COMPOSE_PROFILES=dev`.

Se precisar acessar pontualmente (debug de produção):

```bash
# na VPS
docker compose --profile dev up -d phpmyadmin

# no seu desktop, abra um túnel SSH
ssh -L 8080:127.0.0.1:8080 <usuario>@<ip-da-vps>
# acesse http://localhost:8080

# ao terminar, derrube na VPS
docker compose stop phpmyadmin
```

---

## 14. Checklist final

- [ ] Ambiente preparado conforme [`passo-a-passo.md`](./passo-a-passo.md)
- [ ] DNS A record propagado (`dig` retorna o IP da VPS)
- [ ] Projeto clonado em `/var/www/lojavirtual`
- [ ] `.env` com `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`
- [ ] `.env` **sem** `COMPOSE_PROFILES=dev`
- [ ] Container `nginx` exposto apenas em `127.0.0.1:8088`
- [ ] Containers `app`, `nginx`, `mysql` rodando (`docker compose ps` → todos `Up`, mysql `(healthy)`)
- [ ] Vhost do Nginx do host configurado e ativo
- [ ] Certificado Let's Encrypt emitido (`certbot --nginx`)
- [ ] `certbot renew --dry-run` passa
- [ ] `TrustProxies` confiando no proxy do host
- [ ] `config:cache` / `route:cache` / `view:cache` rodados
- [ ] Backup automático agendado no cron
