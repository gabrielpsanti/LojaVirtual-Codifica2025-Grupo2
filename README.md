# Loja Virtual — Codifica 2025 (Grupo 2)

Projeto de e-commerce desenvolvido por alunos do **Codifica+** em **Laravel 13** com **PHP 8.4**, **MySQL 8** e **Vite/Tailwind**. Todo o ambiente roda em Docker — você não precisa instalar PHP, Composer, Node ou MySQL na máquina.

---

## Por onde começar

Escolha o cenário que se aplica a você:

| Quero... | Vá para |
|---|---|
| Rodar o projeto na minha máquina (Linux / macOS / Windows com WSL2) | [`docs/desenvolvimento.md`](./docs/desenvolvimento.md) |
| Preparar uma VPS Linux do zero (Docker, Nginx, Certbot, firewall...) | [`docs/passo-a-passo.md`](./docs/passo-a-passo.md) |
| Fazer o deploy do projeto em servidor já preparado (com HTTPS) | [`docs/producao.md`](./docs/producao.md) |

> **Fluxo completo para colocar no ar:** primeiro [`passo-a-passo.md`](./docs/passo-a-passo.md) (preparar a máquina), depois [`producao.md`](./docs/producao.md) (subir o projeto).

> Os guias assumem que você já tem **Docker** e **Docker Compose plugin** instalados localmente. Links de instalação estão dentro do guia de desenvolvimento.

---

## Estrutura do repositório

```
.docker/                # artefatos do container
├── Dockerfile          # imagem da aplicação (PHP 8.4 FPM + Composer + Node 20)
├── nginx/default.conf  # vhost do Nginx que serve o public/ e proxia .php para o php-fpm
└── php/php.ini         # overrides do PHP (memory_limit, upload, opcache, timezone)
docs/                   # documentação do projeto
├── desenvolvimento.md  # como rodar localmente
├── passo-a-passo.md    # como preparar uma VPS Linux do zero
└── producao.md         # como fazer o deploy do projeto em servidor com HTTPS
app/                    # código-fonte Laravel (controllers, models, etc.)
resources/              # views Blade, CSS, JS
routes/                 # rotas web/API
database/               # migrations, seeders, factories
public/                 # document root (index.php, assets compilados)
docker-compose.yml      # orquestra app, nginx, mysql, phpmyadmin e node
.env.example            # template de variáveis de ambiente
```

---

## Quick start (TL;DR para quem já conhece Docker)

```bash
git clone <url-do-repositorio>
cd <pasta-do-repositorio>

cp .env.example .env
# editar .env: DB_HOST=mysql, descomentar bloco do banco, opcional COMPOSE_PROFILES=dev

docker compose --profile dev up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app npm install
docker compose exec app npm run build
```

- Aplicação: http://localhost
- phpMyAdmin: http://localhost:8080

Detalhes, comandos do dia a dia e troubleshooting em [`docs/desenvolvimento.md`](./docs/desenvolvimento.md).

---

## Stack

- **PHP 8.4** + **Laravel 13**
- **MySQL 8**
- **Nginx** (servidor web)
- **Vite 8** + **Tailwind CSS 4** (front)
- **phpMyAdmin** (UI do banco, só em desenvolvimento)
- **Docker** + **Docker Compose v2**

---

## Licença

Código baseado no [framework Laravel](https://laravel.com), distribuído sob a [licença MIT](https://opensource.org/licenses/MIT).
