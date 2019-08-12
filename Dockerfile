FROM quay.io/continuouspipe/php7.3-nginx:latest

RUN apt-get update -qq \
 && DEBIAN_FRONTEND=noninteractive apt-get -qq -y --no-install-recommends install \
    php-mongodb \
 \
 # Clean the image \
 && apt-get auto-remove -qq -y \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

COPY ./ /app
WORKDIR /app

ENV PHP_TIMEZONE=Europe/London

# Install dependencies
ARG GITHUB_TOKEN=
RUN /usr/local/bin/container build
