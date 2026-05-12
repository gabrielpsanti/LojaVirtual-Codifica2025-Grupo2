# Passo a Passo — Preparando o Ambiente em uma VPS Linux

Tutorial linear para deixar uma **máquina Linux** pronta para receber qualquer aplicação Docker. Cobre desde o primeiro login via SSH até a confirmação de que o ambiente está saudável (Docker, Nginx, Certbot, firewall).

Funciona em qualquer VPS — AWS EC2, DigitalOcean, Linode, Hetzner, Vultr, OCI, Azure, Google Cloud ou servidor dedicado. Todos os comandos são para Ubuntu Server **22.04 / 24.04 LTS** (com pequenos ajustes em Debian 12+).

> Este guia cuida do **ambiente** (sistema operacional, Docker, Nginx, Certbot, firewall). Depois de finalizá-lo, siga para [`producao.md`](./producao.md) para fazer o deploy do projeto em si.

**Pré-requisito:** uma VPS já criada com IP público fixo, portas 22/80/443 abertas no firewall do provedor, e acesso SSH funcionando.

**Tempo estimado:** 15 a 20 minutos.

---

## Sumário

1. [Conectar via SSH pela primeira vez](#1-conectar-via-ssh-pela-primeira-vez)
2. [Atualizar o sistema](#2-atualizar-o-sistema)
3. [Criar swap (se RAM ≤ 2 GB)](#3-criar-swap-recomendado-se-ram--2-gb)
4. [Configurar timezone e hostname](#4-timezone-e-hostname)
5. [Instalar Docker e Docker Compose](#5-instalar-docker-e-docker-compose)
6. [Instalar Nginx, Certbot e utilitários](#6-instalar-nginx-certbot-e-utilitários)
7. [Configurar o firewall do host (UFW)](#7-configurar-o-firewall-do-host-ufw)
8. [Verificar o ambiente](#8-verificar-o-ambiente)
9. [Próximos passos: deploy do projeto](#9-próximos-passos-deploy-do-projeto)

---

## 1. Conectar via SSH pela primeira vez

No seu **laptop** (não na VPS ainda), ajuste a permissão da chave privada:

```bash
# Linux / macOS / WSL
chmod 400 ~/caminho/da/chave.pem
```

Conecte:

```bash
ssh -i ~/caminho/da/chave.pem <usuario>@<ip-da-vps>
```

O **`<usuario>`** depende da imagem do SO escolhida no provedor:

| Imagem | Usuário padrão |
|---|---|
| Ubuntu (AWS, GCP, DO, Linode, Vultr...) | `ubuntu` |
| Debian | `admin` ou `debian` |
| Amazon Linux | `ec2-user` |
| CentOS / Rocky / Alma | `centos`, `rocky`, `almalinux` |
| Algumas DigitalOcean/Hetzner | `root` (se você setou senha) |

Na primeira conexão, aparece:

```
The authenticity of host '203.0.113.45 (203.0.113.45)' can't be established.
Are you sure you want to continue connecting (yes/no/[fingerprint])?
```

Digite `yes` e Enter. Você está dentro do servidor.

> **A partir daqui, TODOS os comandos rodam dentro da VPS** (depois do prompt `usuario@hostname:~$`).

---

## 2. Atualizar o sistema

Primeiríssima coisa em qualquer máquina nova:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt autoremove -y
```

Leva 5 a 10 minutos. Se aparecer um popup azul perguntando sobre configurações de serviço, escolha **"keep the local version currently installed"** (opção padrão).

Se for solicitado reboot:

```bash
sudo reboot
# espera 30s, reconecta:
# ssh -i ~/caminho/da/chave.pem <usuario>@<ip>
```

---

## 3. Criar swap (recomendado se RAM ≤ 2 GB)

Em uma VPS com 2 GB de RAM, `composer install` e `npm run build` chegam perto do limite. 2 GB de swap evitam o processo morrer com `Killed`.

```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile

# torna permanente (sobrevive a reboot)
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab

# confere
free -h
```

A linha `Swap:` deve mostrar `2.0Gi`.

> Em VPS com 4 GB+ de RAM não precisa de swap.

---

## 4. Timezone e hostname

```bash
# Timezone do Brasil
sudo timedatectl set-timezone America/Sao_Paulo

# Hostname amigável (aparece no prompt e nos logs)
sudo hostnamectl set-hostname lojavirtual-prod

# Verifica
timedatectl
hostnamectl
```

Para o novo hostname aparecer no prompt, **deslogue e logue de novo**.

---

## 5. Instalar Docker e Docker Compose

A versão do Docker que vem no `apt` padrão é antiga. Vamos usar o repositório oficial:

```bash
# 1. Pré-requisitos
sudo apt install -y ca-certificates curl gnupg

# 2. Adicionar a chave GPG do Docker
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# 3. Adicionar o repositório do Docker
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 4. Instalar
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# 5. Permitir rodar docker sem sudo (adiciona seu usuário ao grupo docker)
sudo usermod -aG docker $USER

# 6. Recarregar o grupo (sem precisar deslogar)
newgrp docker
```

> Em **Debian**, troque `ubuntu` por `debian` na URL do repositório do Docker (passo 3).

Verifique:

```bash
docker --version          # Docker version 27.x.x ...
docker compose version    # Docker Compose version v2.x.x
docker run hello-world    # deve baixar a imagem e imprimir "Hello from Docker!"
```

> Se `docker run hello-world` der erro de permissão, **deslogue e logue de novo** (`exit` e reconecte via SSH).

---

## 6. Instalar Nginx, Certbot e utilitários

```bash
sudo apt install -y nginx python3-certbot-nginx ufw git curl htop
```

- **nginx** → servidor web do **host** que recebe as conexões públicas e faz proxy para o container
- **python3-certbot-nginx** → ferramenta da Let's Encrypt para emitir e renovar certificados TLS
- **ufw** → firewall do Linux (segunda camada, complementa o firewall do provedor)
- **git** → para clonar projetos do GitHub
- **htop** → monitor de processos (digite `htop` no terminal — útil para diagnosticar)

---

## 7. Configurar o firewall do host (UFW)

```bash
sudo ufw allow OpenSSH       # porta 22 (SSH)
sudo ufw allow 'Nginx Full'  # portas 80 (HTTP) e 443 (HTTPS)
sudo ufw --force enable
sudo ufw status verbose
```

A saída deve mostrar **`Status: active`** e as portas 22, 80, 443 como `ALLOW`. Nenhuma outra porta fica aberta.

> O firewall do provedor já filtra antes do tráfego chegar na máquina. O UFW é uma **segunda camada** — defesa em profundidade contra erros de configuração no painel do provedor.

---

## 8. Verificar o ambiente

Antes de seguir para o deploy, confirme que tudo está saudável:

```bash
# Docker e Compose
docker --version
docker compose version
docker run --rm hello-world      # imprime mensagem de sucesso e remove o container

# Nginx do host
sudo systemctl status nginx      # active (running)
curl -I http://localhost         # 200 OK (página default do Nginx)

# Certbot
certbot --version

# Firewall
sudo ufw status                  # active, 22/80/443 ALLOW

# Sistema
free -h                          # confere RAM + swap
df -h /                          # confere disco
timedatectl                      # confere timezone America/Sao_Paulo
```

Se todos os comandos respondem como esperado, o **ambiente está pronto**.

---

## 9. Próximos passos: deploy do projeto

A máquina está preparada. Para colocar o projeto **Loja Virtual** no ar com HTTPS, siga agora [`producao.md`](./producao.md). Esse guia cobre:

- Apontar o DNS do domínio do projeto
- Clonar o repositório
- Configurar o `.env` de produção
- Subir os containers (PHP-FPM, Nginx do container, MySQL)
- Configurar o vhost no Nginx do host (proxy reverso)
- Emitir o certificado HTTPS com Certbot
- Backups, atualização contínua, etc.
