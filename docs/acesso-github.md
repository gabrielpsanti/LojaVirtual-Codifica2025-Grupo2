# Autorizar o servidor a clonar o repo

Se o `git clone` no servidor retorna `Permission denied (publickey)` ou `Authentication failed`, é porque ele ainda não tem credencial no GitHub. Escolha uma opção:

---

## A — Deploy Key SSH (recomendado)

Acesso restrito a este único repositório, não expira.

No servidor, gere a chave padrão e mostre a pública:

```bash
ssh-keygen -t ed25519 -C "deploy-lojavirtual" -N ""
# Pressione Enter para aceitar o caminho padrão (~/.ssh/id_ed25519)
cat ~/.ssh/id_ed25519.pub
```

No GitHub → repositório → `Settings` → `Deploy keys` → `Add deploy key`. Cole a chave pública e adicione **sem** marcar "Allow write access".

Clone:

```bash
git clone git@github.com:gabrielpsanti/LojaVirtual-Codifica2025-Grupo2.git
```

---

## B — Personal Access Token via HTTPS (rápido)

**No GitHub**: avatar → `Settings` → `Developer settings` → `Personal access tokens (classic)` → `Generate new token (classic)`. Marque o scope **`repo`**, gere e copie o token.

```bash
git clone https://<TOKEN>@github.com/gabrielpsanti/LojaVirtual-Codifica2025-Grupo2.git
```
