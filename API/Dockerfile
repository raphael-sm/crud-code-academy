FROM php:8.3-cli

WORKDIR /app

COPY config/ config/
COPY public/ public/
COPY src/ src/

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "public", "public/index.php"]