FROM php:8.2.0RC5-cli

WORKDIR /usr/src/myapp

RUN apt-get update && \
    apt-get install -y zip
