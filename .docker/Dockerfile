# ============================================================================
#  Dockerfile — receita para construir a imagem da aplicação Laravel
# ============================================================================
#  Imagem = "template" de um sistema operacional + tudo que a aplicação precisa.
#  Container = uma "instância em execução" de uma imagem (igual classe x objeto).
#
#  Este arquivo é lido pelo comando `docker build` (ou pelo `docker compose
#  build`, que é o que usamos aqui). Cada instrução vira uma "camada" cacheada:
#  se você muda só uma linha, só as camadas dali pra baixo são reconstruídas.
# ============================================================================


# --- 1. Imagem base ---------------------------------------------------------
# Começamos a partir da imagem oficial do PHP 8.4 no modo FPM (FastCGI Process
# Manager). O FPM é o "motor" do PHP que fica escutando requisições vindas do
# Nginx. Esta imagem já vem com Debian + PHP 8.4 instalados.
FROM php:8.4-fpm


# --- 2. Argumentos de build -------------------------------------------------
# ARG cria variáveis usadas SÓ durante o `docker build`. Aqui passamos o UID
# (ID do usuário) e GID (ID do grupo) do host para o container, evitando que
# arquivos criados dentro do container apareçam como "root" no Linux do host.
# Valor padrão 1000 (primeiro usuário comum no Linux).
ARG UID=1000
ARG GID=1000


# --- 3. Variável de ambiente do apt -----------------------------------------
# ENV cria variáveis que ficam disponíveis dentro do container (no build E em
# tempo de execução). `DEBIAN_FRONTEND=noninteractive` faz o `apt-get` não
# pedir confirmação interativa durante a instalação dos pacotes.
ENV DEBIAN_FRONTEND=noninteractive


# --- 4. Instalar pacotes do sistema + Node.js 20 ----------------------------
# RUN executa comandos shell dentro da imagem. Juntamos tudo em UM RUN só
# (encadeado com `&&`) por dois motivos:
#   1. Cada RUN vira uma camada na imagem — menos camadas = imagem menor
#   2. Limpar o cache do apt na mesma camada economiza ~30MB
#
# Pacotes:
#   git, curl, ca-certificates → essenciais
#   lib*-dev                   → headers necessários para compilar as extensões
#                                do PHP no passo seguinte (gd, zip, intl, etc.)
#   zip, unzip                 → Composer precisa para extrair pacotes
#   nodejs                     → para rodar Vite/Tailwind (build do front)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        ca-certificates \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
        zip \
        unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*


# --- 5. Instalar extensões do PHP -------------------------------------------
# `docker-php-ext-install` é um helper que vem na imagem oficial do PHP, usado
# para compilar e habilitar extensões. O Laravel 13 precisa destas extensões:
#   pdo_mysql → driver para conectar no MySQL
#   mbstring  → manipulação de strings UTF-8
#   exif      → leitura de metadados de imagens
#   pcntl     → controle de processos (usado pela fila/jobs)
#   bcmath    → matemática de precisão arbitrária
#   gd        → manipulação de imagens (redimensionar, etc.)
#   zip       → compactação
#   intl      → internacionalização (formatação de datas, números por locale)
#
# `-j$(nproc)` compila em paralelo usando todos os cores da máquina.
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl


# --- 6. Copiar o Composer de outra imagem -----------------------------------
# Truque chamado "multi-stage build": em vez de instalar o Composer com curl
# (mais lento, várias linhas), copiamos o binário pronto da imagem oficial
# `composer:2`. É a forma idiomática recomendada pelo próprio Composer.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# --- 7. Criar usuário não-root ----------------------------------------------
# Por segurança, NUNCA rodamos a aplicação como root dentro do container.
# Criamos um usuário "app" usando o UID/GID recebidos via ARG. Assim, arquivos
# criados pelo container (cache, logs, etc.) pertencem ao seu usuário no host.
RUN groupadd -g ${GID} app \
    && useradd -m -u ${UID} -g app -s /bin/bash app


# --- 8. Copiar configurações customizadas do PHP ----------------------------
# COPY pega arquivos do host (lado esquerdo) e coloca dentro da imagem (lado
# direito). O caminho `conf.d/` é onde o PHP lê configurações adicionais —
# tudo que cair lá é mergeado com o php.ini padrão.
# Prefixo "zz-" garante que nosso arquivo é lido por último (sobrescreve).
COPY .docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini


# --- 9. Diretório de trabalho ----------------------------------------------
# WORKDIR define a pasta "atual" dentro do container — é o equivalente a um
# `cd /var/www/html` antes de rodar qualquer comando.
WORKDIR /var/www/html


# --- 10. Trocar para o usuário não-root -------------------------------------
# A partir daqui, qualquer comando roda como o usuário "app", não como root.
USER app


# --- 11. Documentar a porta exposta -----------------------------------------
# EXPOSE não "abre" a porta — só DOCUMENTA que o container escuta nessa porta.
# A 9000 é a porta padrão do PHP-FPM (onde o Nginx vai mandar as requisições).
EXPOSE 9000


# --- 12. Comando padrão -----------------------------------------------------
# CMD é o que roda quando o container sobe (se você não passar outro comando).
# `php-fpm` inicia o servidor FastCGI do PHP em foreground (necessário porque
# containers morrem se o processo principal cair).
CMD ["php-fpm"]
