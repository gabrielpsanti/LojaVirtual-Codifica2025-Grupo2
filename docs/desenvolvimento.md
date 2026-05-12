# Ambiente de Desenvolvimento — Loja Virtual (Codifica 2025)

Container do projeto Laravel 13 com **PHP 8.4**, **Nginx**, **MySQL 8**, **phpMyAdmin** e **Node 20** (Vite). Funciona em Linux, macOS e Windows (via WSL2).

> Para deploy em servidor de produção com HTTPS, veja [`producao.md`](./producao.md).

---

## 1. Pré-requisitos

| Ferramenta | Versão mínima | Como instalar |
|---|---|---|
| Docker Engine | 24+ | https://docs.docker.com/engine/install/ |
| Docker Compose plugin | v2.20+ | já incluso no Docker Desktop / pacote `docker-compose-plugin` no Linux |
| Git | qualquer | `apt install git` / `brew install git` |

Verifique com:

```bash
docker --version
docker compose version
```

> No Windows, use sempre o terminal **WSL2** (Ubuntu) e clone o projeto **dentro do filesystem do WSL** (`/home/<usuario>/...`), não dentro de `/mnt/c/...`, para evitar problemas de permissão e performance.

---

## 2. Estrutura

```
.docker/
├── Dockerfile          # imagem da aplicação (PHP 8.4 FPM + Composer + Node)
├── nginx/
│   └── default.conf    # vhost do Nginx que serve o public/ e proxia .php para o php-fpm
└── php/
    └── php.ini         # overrides do PHP (memory_limit, upload, opcache, timezone)
docs/
├── desenvolvimento.md  # este arquivo
└── producao.md         # deploy em servidor
docker-compose.yml      # orquestra os serviços (app, nginx, mysql, phpmyadmin, node)
.dockerignore
```

Serviços do [`docker-compose.yml`](../docker-compose.yml):

| Serviço | Imagem | Função | Porta exposta |
|---|---|---|---|
| `app` | build local (PHP 8.4 FPM) | executa o Laravel | — (interno na rede) |
| `nginx` | nginx:1.27-alpine | servidor web | `80` no host |
| `mysql` | mysql:8.0 | banco | `3306` no host |
| `phpmyadmin` | phpmyadmin:5-apache | UI web para o MySQL (perfil `dev`, só `127.0.0.1`) | `8080` no host |
| `node` | node:20-alpine | Vite dev server (perfil `dev`) | `5173` no host |

> Serviços com **perfil `dev`** (`phpmyadmin`, `node`) **só sobem em desenvolvimento**. Em produção, basta não ativar o perfil — eles ficam desligados. Por defesa adicional, o `phpmyadmin` só escuta em `127.0.0.1`, então nem se for ligado por engano fica acessível pela internet.

---

## 3. Primeira execução (passo a passo)

### 3.1 Clonar e entrar no projeto

```bash
git clone <url-do-repositorio>
cd <pasta-do-repositorio>
```

### 3.2 Criar o `.env`

```bash
cp .env.example .env
```

Edite o `.env` e descomente/ajuste o bloco do banco para apontar para o container `mysql`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=loja
DB_USERNAME=loja
DB_PASSWORD=secret
```

> Os valores acima batem com os defaults do `docker-compose.yml`. Se quiser mudar, altere também as variáveis `MYSQL_*` no compose ou exporte `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` no shell antes de subir.

(Opcional) ajuste portas, UID/GID e perfis ativos:

```bash
# .env (opcional)
APP_PORTS=80:80
PMA_PORT=8080
FORWARD_DB_PORT=3306
VITE_PORT=5173
UID=1000
GID=1000

# Ativa phpmyadmin + node automaticamente em `docker compose up`
COMPOSE_PROFILES=dev
```

> Com `COMPOSE_PROFILES=dev` no `.env`, qualquer `docker compose up -d` já sobe phpMyAdmin e o Vite junto. Sem essa variável, o `up` levanta só `app`, `nginx` e `mysql` — útil para simular o ambiente de produção.

> Em Linux, rode `id -u` e `id -g` para descobrir seu UID/GID e evitar problemas de permissão em arquivos criados pelo container.

### 3.3 Subir os containers

```bash
docker compose --profile dev up -d --build
```

> Use `--profile dev` (ou deixe `COMPOSE_PROFILES=dev` no `.env`) para incluir o phpMyAdmin e o Vite. Sem o flag, sobe só `app`, `nginx` e `mysql`.

Aguarde o healthcheck do MySQL ficar verde (`docker compose ps` mostra `healthy`).

### 3.4 Instalar dependências e preparar o Laravel

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

### 3.5 Subir o Vite (assets do front)

Se você subiu com `--profile dev`, o serviço `node` já está rodando o Vite com hot reload em http://localhost:5173.

Para apenas gerar o build estático uma vez (sem deixar o Vite rodando):

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

### 3.6 Acessar a aplicação

- Aplicação: http://localhost
- phpMyAdmin: http://localhost:8080 (só com perfil `dev`; usuário/senha vêm do `.env` — `DB_USERNAME` / `DB_PASSWORD`; para acessar como root, use `root` / `DB_ROOT_PASSWORD`)

---

## 4. Comandos do dia a dia

```bash
# Logs
docker compose logs -f app
docker compose logs -f nginx

# Shell no container PHP
docker compose exec app bash

# Artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker
docker compose exec app php artisan queue:work

# Composer
docker compose exec app composer require <pacote>
docker compose exec app composer dump-autoload

# NPM / Vite
docker compose exec app npm install
docker compose exec app npm run build

# Testes
docker compose exec app php artisan test

# Parar tudo
docker compose down

# Parar e apagar volumes (CUIDADO: apaga o banco)
docker compose down -v
```

---

## 5. Acessar o MySQL externamente

### 5.1 Via phpMyAdmin (mais simples)

Abra http://localhost:8080. O login já vem pré-preenchido com `DB_USERNAME` / `DB_PASSWORD` do `.env`. Para entrar como root, use `root` / `DB_ROOT_PASSWORD`.

### 5.2 Via cliente externo (DBeaver / TablePlus / Workbench)

A porta `3306` do container está exposta no host:

- Host: `127.0.0.1`
- Porta: `3306`
- Usuário: `loja` (ou `root`)
- Senha: `secret` (ou `root` para o root)
- Database: `loja`

### 5.3 Via CLI

```bash
docker compose exec mysql mysql -uloja -psecret loja
```

---

## 6. Problemas comuns

**Erro de permissão em `storage/` ou `bootstrap/cache/`**
Garanta que `UID`/`GID` do `.env` batem com seu usuário do host. Depois rode:
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Porta 80, 8080 ou 3306 já em uso**
Mude `APP_PORTS`, `PMA_PORT` ou `FORWARD_DB_PORT` no `.env` e suba de novo:
```bash
docker compose down && docker compose up -d
```

**MySQL não inicializa**
Apague o volume e recrie (perde os dados):
```bash
docker compose down -v && docker compose up -d
```

**Vite não carrega assets no browser**
Confirme que o serviço `node` está rodando (`docker compose ps`). No `.env` adicione:
```env
VITE_HOST=localhost
```
e rebuilde os assets.

**Mudei o `Dockerfile` e nada mudou**
Force o rebuild:
```bash
docker compose build --no-cache app && docker compose up -d
```
