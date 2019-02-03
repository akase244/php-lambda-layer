#!/usr/bin/env bash

yum update -y && \
    yum install -y autoconf \
    bison \
    gcc \
    gcc-c++ \
    make \
    libcurl-devel \
    libxml2-devel \
    tar \
    gzip \
    zip \
    git

cd /tmp
curl -sL http://www.openssl.org/source/openssl-1.0.1k.tar.gz | tar -xvz
cd /tmp/openssl-1.0.1k
./config && make && make install

cd /tmp
curl -sL https://github.com/php/php-src/archive/php-7.3.1.tar.gz | tar -xvz
cd php-src-php-7.3.1
./buildconf --force && \
    ./configure \
      --prefix=/usr/local/ \
      --with-openssl=/usr/local/ssl \
      --with-curl \
      --with-zlib \
      --enable-pcntl \
      --without-pear && \
    make install

mkdir /tmp/layer
cd /tmp/layer

cp /opt/layer/bootstrap /tmp/layer
chmod +x bootstrap

mkdir /tmp/layer/bin && \
    cp /usr/local/bin/php /tmp/layer/bin

mkdir /tmp/layer/src
cp /opt/layer/phpversion.php /tmp/layer/src
cp /opt/layer/phpinfo.php /tmp/layer/src

zip -r /opt/layer/runtime.zip bin bootstrap
zip -r /opt/layer/phpinfo.zip src/phpinfo.php
