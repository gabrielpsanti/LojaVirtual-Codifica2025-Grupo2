# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

E-commerce ("Loja Virtual") built with **Laravel 13 + PHP 8.4 + MySQL 8 + Vite 8 + Tailwind 4**. The codebase is in **Portuguese (pt-BR)** — routes, models, controllers, views, and column names all use Portuguese (e.g. `Produto`, `Categoria`, `Venda`, `Endereco`, `Desconto`). Match this convention when adding new code.

The full environment runs in Docker — there is no expectation that PHP/Composer/Node are installed on the host. The host-side `node_modules/` and `vendor/` directories may exist (visible in `ls`) but commands should be run inside containers.

## Common commands

All commands run from the repo root. The `app` container holds PHP 8.4, Composer, and Node 20.

```bash
# Bring the stack up (include dev profile for phpmyadmin + node/Vite hot reload)
docker compose --profile dev up -d --build
docker compose down

# Run anything in the PHP container
docker compose exec app <cmd>

# First-time / after pulling
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app npm install
docker compose exec app npm run build      # production assets
# (or use the `node` service from --profile dev for `vite` hot reload on :5173)

# Tests (PHPUnit 12; uses sqlite :memory: per phpunit.xml)
docker compose exec app php artisan test
docker compose exec app php artisan test --filter=SomeTest
docker compose exec app php artisan test tests/Feature/ExampleTest.php

# Lint / format (Laravel Pint)
docker compose exec app ./vendor/bin/pint
docker compose exec app ./vendor/bin/pint --test    # check only

# Common artisan
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan tinker
docker compose exec app php artisan route:list
```

Service URLs: app at http://localhost, phpMyAdmin at http://localhost:8080 (dev profile only, bound to 127.0.0.1).

## Architecture

Standard Laravel MVC, organised by audience rather than by feature:

- **`app/Http/Controllers/Admin/`** — back-office CRUD: `CategoriaController`, `ProdutoController`, `VendaController`, `DescontoController`, `EnderecoController`, `DashboardController`.
- **`app/Http/Controllers/Cliente/`** — storefront + checkout + auth: `ProdutoController` (catalog/search), `CompraController` (multi-step checkout), `LoginController`, `UsuarioController`, `EnderecoController`, `CabecalhoController`, `CabecalhoContatoController`.
- **`app/Models/`** — Eloquent models in Portuguese. Note `User.php` (Laravel default, used for auth) coexists with `Usuario.php` (domain model). Be careful to use the right one when touching auth vs. profile/account features.
- **`app/Repositories/`** — `CategoriaRepository`, `ProdutoRepository` wrap query logic that's reused across controllers. Add to existing repositories rather than duplicating query logic in controllers.
- **`resources/views/admin/`** and **`resources/views/cliente/`** — Blade views mirror the controller split. Shared pieces live in `resources/views/components/` (also `app/View/Components/` for class-based components).

### Routes (`routes/web.php`)

All routes live in a single file, grouped by middleware:

- `middleware('guest')` — login + register.
- `middleware('auth')` — account, checkout flow, **and the entire `/admin/*` area**. There is currently no separate admin role middleware — admin is gated only by `auth`. Keep this in mind before assuming admin actions are role-restricted.
- Outside both groups — public storefront: `/`, `/produtos`, `/produtos/{id}`, `/pesquisar`, `/carrinho`, `/faq`, `/quem-somos`, `/contato`, plus a **greedy `/{categoria}` route** that matches any single-segment path. Any new top-level public route must be registered **above** `/{categoria}` or it will be shadowed.

### Checkout flow

Multi-step, handled entirely by `Cliente/CompraController`: `carrinho` → `frete` → `pedido` → `pagamento` → `finalizar` → `sucesso`. Each step has a `view` GET and a POST action. State is carried across steps — check existing methods before adding new fields.

### Docker layout

- `app` (PHP-FPM, built from `.docker/Dockerfile`) and `nginx` are always up.
- `mysql` is always up; data persists in the `mysql-data` named volume.
- `phpmyadmin` and `node` are gated behind the `dev` Compose profile (`--profile dev` or `COMPOSE_PROFILES=dev` in `.env`).
- Containers reach each other by service name on the `lojavirtual` network — that's why `.env` uses `DB_HOST=mysql`.
- `APP_PORTS` in `.env` controls the public bind (`80:80` for dev; in production set `127.0.0.1:8088:80` and front with a host-level reverse proxy).

## Docs

Deeper environment/deploy docs live in `docs/` (in Portuguese): `primeiros-passos.md`, `desenvolvimento.md`, `preparar-producao.md`, `producao.md`, `acesso-github.md`. Consult these before changing Docker, Nginx, or deployment config.
