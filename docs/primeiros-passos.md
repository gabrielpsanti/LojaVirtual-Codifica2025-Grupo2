# Primeiros Passos com Docker — Windows, macOS e Linux

Guia curto pra rodar o projeto pela primeira vez via Docker. **Você não precisa instalar PHP, MySQL ou Node** — o Docker cuida de tudo.

> Detalhes, troubleshooting e comandos do dia a dia: [`desenvolvimento.md`](./desenvolvimento.md).

---

## 1. Instalar o Docker

Escolha seu sistema operacional:

### 🪟 Windows

1. Baixe o **Docker Desktop**: https://www.docker.com/products/docker-desktop/
2. Execute o instalador (deixe a opção "Use WSL 2" marcada — ele instala sozinho o que precisa).
3. Reinicie a máquina se for pedido.
4. Abra o Docker Desktop uma vez e espere o ícone na bandeja ficar verde.
5. Os comandos abaixo rode no **PowerShell** (Win+R → `powershell` → Enter) ou no **Git Bash**.

### 🍎 macOS

1. Baixe o **Docker Desktop** (escolha Apple Silicon ou Intel conforme seu chip): https://www.docker.com/products/docker-desktop/
2. Arraste pra pasta Applications e abra uma vez para inicializar.
3. Os comandos abaixo rode no **Terminal** padrão do macOS.

### 🐧 Linux (Ubuntu / Debian) — ou WSL2 no Windows

```bash
curl -fsSL https://get.docker.com | sudo sh
sudo usermod -aG docker $USER
newgrp docker
```

> **WSL2 no Windows:** os comandos acima funcionam **igualzinho** dentro de uma janela do **Ubuntu** (WSL2). Duas situações possíveis:
>
> - **Se você já instalou o Docker Desktop** (seção 🪟 Windows acima): **não precisa rodar os comandos acima**. O Docker Desktop já integra automaticamente com o WSL — basta abrir o Ubuntu (Iniciar → "Ubuntu") e pular pro passo 2. Os comandos `docker` já estão disponíveis.
> - **Se você não quer o Docker Desktop** e prefere usar o Docker direto dentro do WSL: aí sim, rode os 3 comandos acima dentro do Ubuntu (WSL).
>
> Dica: **clone o projeto sempre dentro do filesystem do WSL** (ex: `~/projetos/...`) e **não** em `/mnt/c/...` — fica muito mais rápido.

### Confirmar a instalação (qualquer SO)

```bash
docker --version
docker compose version
```

Os dois comandos têm que responder com uma versão. Se não, reabra o terminal (ou reinicie a máquina) e tente de novo.

---

## 2. Clonar e configurar o projeto

```bash
git clone <url-do-repositorio>
cd LojaVirtual-Codifica2025-Grupo2
```

Copie o arquivo de ambiente:

```bash
# Linux / macOS / Git Bash
cp .env.example .env
```

```powershell
# Windows PowerShell
Copy-Item .env.example .env
```

Abra o `.env` no seu editor (VS Code, Notepad, nano...) e ajuste o bloco do banco para apontar para o container `mysql`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=loja
DB_USERNAME=loja
DB_PASSWORD=secret
```

---

## 3. Subir os containers

Os comandos a seguir são **iguais em qualquer sistema**:

```bash
docker compose --profile dev up -d --build
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

A primeira execução demora ~5 min (baixa imagens e instala dependências). Da segunda vez em diante, sobe em segundos.

**Por que cada comando?**

- **`--profile dev`** → liga os serviços extras de desenvolvimento (phpMyAdmin e Vite). Sem esse flag, sobem só `app`, `nginx` e `mysql`.
- **`chmod -R 775 storage bootstrap/cache`** → garante que o Laravel consegue escrever logs e cache. No Linux/WSL, se o seu usuário tiver UID diferente de 1000, sem isso dá `Permission denied` no primeiro log que o framework tentar gravar.
- **`storage:link`** → cria o link simbólico de `public/storage` para `storage/app/public`. Sem isso, qualquer imagem que o usuário fizer upload aparece **quebrada** no navegador.

> **Atalho opcional:** se você não quer digitar `--profile dev` toda vez, adicione `COMPOSE_PROFILES=dev` no seu `.env`. Aí os próximos `docker compose up` já levantam os serviços de dev automaticamente.

---

## 4. Acessar

- **Aplicação:** http://localhost/
- **phpMyAdmin** (admin do banco): http://localhost:8080
  - Usuário: `loja` / Senha: `secret`

---

## 5. Comandos do dia a dia

```bash
# Parar tudo
docker compose down

# Subir de novo
docker compose --profile dev up -d

# Ver logs em tempo real
docker compose logs -f app

# Entrar no container PHP
docker compose exec app bash

# Rodar artisan
docker compose exec app php artisan <comando>
```

Mais comandos, troubleshooting de permissão, e como o ambiente funciona por dentro: [`desenvolvimento.md`](./desenvolvimento.md).
