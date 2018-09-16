FROM codezm/alpine:3.4

RUN set -ex \
    && PHP_VERSION="7.2.10" \
    && PHP_SHA256="01c2154a3a8e3c0818acbdbc1a956832c828a0380ce6d1d14fea495ea21804f0" \
    && PHP_INI_DIR="/usr/local/etc/php" \
    && mkdir -p "$PHP_INI_DIR/conf.d" \
    && apk update && apk add --no-cache --virtual .persistent-deps \
		ca-certificates \
		curl \
		tar \
		xz \
		libressl \
        wget \
        \
		git \
		vim \
    \
    # PHPIZE_DEPS.
    && apk add --no-cache --virtual .build-deps \
        autoconf \
		dpkg-dev dpkg \
		file \
		g++ \
		gcc \
		libc-dev \
		make \
		pkgconf \
		re2c \
		\
        libmcrypt-dev \
        freetype-dev \
        jpeg-dev \
        libpng-dev \
        libmemcached-dev \
        cyrus-sasl-dev \
        linux-headers\
        \
        coreutils \
        curl-dev \
        libedit-dev \
        libressl-dev \
        libxml2-dev \
        sqlite-dev \
    \
    && addgroup -g 82 -S www-data \
	&& adduser -u 82 -D -S -G www-data www-data \
    && cd /tmp \
    # http://mirrors.sohu.com/php/php-${PHP_VERSION}.tar.xz
    # php pecl for windows. http://windows.php.net/downloads/pecl/releases/ 
    && wget -c -O php.tar.xz "http://cn2.php.net/get/php-${PHP_VERSION}.tar.xz/from/this/mirror" \
    ; \
    \
    if [ -n "$PHP_SHA256" ]; then \
		echo "$PHP_SHA256 *php.tar.xz" | sha256sum -c -; \
	fi; \
	\
    tar -Jxf php.tar.xz -C "/tmp" --strip-components=1 \
    && gnuArch="$(dpkg-architecture --query DEB_BUILD_GNU_TYPE)" \
	./configure \
	    --build="$gnuArch" \
		--with-config-file-path="$PHP_INI_DIR" \
		--with-config-file-scan-dir="$PHP_INI_DIR/conf.d" \
        # Add php extensions.
        --with-mcrypt \
        --with-pdo-mysql \
		--with-curl \
		--with-libedit \
		--with-openssl \
		--with-zlib \
        --with-gd \
        --with-freetype-dir \
        --with-png-dir \
        --with-jpeg-dir \
		\
		--disable-cgi \
		\
		--enable-mysqlnd \
		--enable-mbstring \
        --enable-zip \
        --enable-xml \
        \
        $(test "$gnuArch" = 's390x-linux-gnu' && echo '--without-pcre-jit') \
    && make -j "$(nproc)" \
    && make install && make clean \
    && pecl update-channels \
    && mv php.ini-development "$PHP_INI_DIR/php.ini" \
    \
    && php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" \
    && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com \
    \
    && { \
        echo; \
        echo "extension_dir=\"$(php-config --extension-dir)\""; \
    } | tee -a "$PHP_INI_DIR/php.ini" \
	######################################
	# clear.                             #
	######################################
    \
    #&& rm -rf /tmp/* /tmp/.[!.]* /tmp/..?* ~/.pearrc \
    #&& runDeps="$( \
            #scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
                #| tr ',' '\n' \
                #| sort -u \
                #| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
        #)" \
	#&& apk add --no-cache --virtual .php-rundeps $runDeps \
    #&& apk del .build-deps \
	&& { find /usr/local/bin /usr/local/sbin -type f -perm +0111 -exec strip --strip-all '{}' + || true; }

WORKDIR /web

EXPOSE 9000

ENTRYPOINT ["php"]
