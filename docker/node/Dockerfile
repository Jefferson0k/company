FROM node:22-alpine
RUN apk add --no-cache php84 php84-phar php84-mbstring php84-openssl php84-tokenizer php84-xml php84-xmlwriter php84-dom && \
    ln -sf /usr/bin/php84 /usr/bin/php
WORKDIR /var/www