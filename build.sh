#!/usr/bin/env bash

yum update -y && \
    yum install -y autoconf \
    bison \
    gcc \
    gcc-c++ \
    libcurl-devel \
    libxml2-devel \
    zip \
    git \
    libedit-devel \
    php73 \
    php73-json

mkdir /tmp/layer
cd /tmp/layer

cp /opt/layer/bootstrap .
chmod +x bootstrap

mkdir bin && \
    cp /usr/bin/php bin/

curl -sS https://getcomposer.org/installer | ./bin/php
./bin/php composer.phar require guzzlehttp/guzzle

mkdir lib
for lib in libncurses.so.5 libtinfo.so.5 libpcre.so.0; do
  cp "/lib64/${lib}" lib/
done
cp /usr/lib64/libedit.so.0 lib/
cp -a /usr/lib64/php lib/

cat << EOF > php.ini
extension_dir=/opt/lib/php/7.3/modules

extension=curl.so
extension=json.so
EOF

mkdir src
cp /opt/layer/postNippoCount.php src/

zip -r /opt/layer/runtime.zip bin lib bootstrap php.ini
zip -r /opt/layer/postNippoCount.zip src/postNippoCount.php
zip -r /opt/layer/vendor.zip vendor
