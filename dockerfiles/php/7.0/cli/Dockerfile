FROM codezm/alpine:3.4

RUN set -ex \
    && PHP_VERSION="7.0.27" \
    && PHP_EXT_REDIS="3.1.4" \
    && PHP_EXT_MEMCACHED="3.0.4" \
    && PHP_EXT_YAF="3.0.5" \
    && SWOOLE_DEPS_HIREDIS="0.13.3" \
    && PHP_EXT_SWOOLE="1.9.9" \
    && PHP_EXT_INOTIFY="2.0.0" \
    && PHP_SHA256="4b2bc823e806dbf7b62fe0b92b0d14b0c6e03f88c3fc5d96278416c54ce11f6c" \
    && PHP_INI_DIR="/usr/local/etc/php" \
    && mkdir -p "$PHP_INI_DIR/conf.d" \
    && apk add --no-cache --virtual .persistent-deps \
		ca-certificates \
		curl \
		tar \
		xz \
		openssl \
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
        openssl-dev \
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
	######################################
	# Install php other extension.       #
	######################################
    \
    && wget -c -O redis.tgz "http://pecl.php.net/get/redis-$PHP_EXT_REDIS.tgz" \
    && mkdir /tmp/redis && tar -xf redis.tgz -C /tmp/redis --strip-components=1 && cd /tmp/redis \
    && phpize && ./configure --with-php-config=php-config && make -j "$(nproc)" && make install && make clean \
    \
    && wget -c -O memcached.tgz "http://pecl.php.net/get/memcached-$PHP_EXT_MEMCACHED.tgz" \
    && mkdir /tmp/memcached && tar -xf memcached.tgz -C /tmp/memcached --strip-components=1 && cd /tmp/memcached \
    && phpize && ./configure --with-php-config=php-config && make -j "$(nproc)" && make install && make clean \
    \
    && wget -c -O yaf.tgz "http://pecl.php.net/get/yaf-$PHP_EXT_YAF.tgz" \
    && mkdir /tmp/yaf && tar -xf yaf.tgz -C /tmp/yaf --strip-components=1 && cd /tmp/yaf \
    && phpize && ./configure --with-php-config=php-config && make -j "$(nproc)" && make install && make clean \
    \
    && wget -c -O hiredis.tar.gz "https://github.com/redis/hiredis/archive/v$SWOOLE_DEPS_HIREDIS.tar.gz" \
    && mkdir /tmp/hiredis && tar -xf hiredis.tar.gz -C /tmp/hiredis --strip-components=1 && cd /tmp/hiredis \
    && make -j "$(nproc)" && make install \
    \
    && wget -c -O inotify.tgz "http://pecl.php.net/get/inotify-$PHP_EXT_INOTIFY.tgz" \
    && mkdir /tmp/inotify && tar -xf inotify.tgz -C /tmp/inotify --strip-components=1 && cd /tmp/inotify \
    && phpize && ./configure --with-php-config=php-config && make -j "$(nproc)" && make install && make clean \
    \
    && wget -c -O swoole.tgz "http://pecl.php.net/get/swoole-$PHP_EXT_SWOOLE.tgz" \
    && mkdir /tmp/swoole && tar -xf swoole.tgz -C /tmp/swoole --strip-components=1 && cd /tmp/swoole \
    && phpize \ 
    && ./configure --with-php-config=php-config --enable-async-redis=/usr/local --enable-openssl \
    && make -j "$(nproc)" && make install && make clean \
    \
    && wget -c -O libdatrie.tar.gz ftp://linux.thai.net/pub/ThaiLinux/software/libthai/libdatrie-0.2.4.tar.gz \
    && mkdir /tmp/libdatrie && tar -xf libdatrie.tar.gz -C /tmp/libdatrie --strip-components=1 && cd /tmp/libdatrie \
    && ./configure && make -j "$(nproc)" && make install \
    \
    && git clone https://github.com/zzjin/php-ext-trie-filter /tmp/trie_filter \
    && cd /tmp/trie_filter \
    && phpize && ./configure --with-php-config=php-config && make -j "$(nproc)" && make install && make clean \
    \
    && php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" \
    && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com \
	######################################
	# php.ini extension configurate.     #
	######################################
    \
    && { \
        echo; \
        echo "extension_dir=\"$(php-config --extension-dir)\""; \
        echo '[redis]'; \
        echo 'extension=redis.so'; \
        echo; \
        echo '[memcached]'; \
        echo 'extension=memcached.so'; \
        echo; \
        echo '[yaf]'; \
        echo 'extension=yaf.so'; \
        echo 'yaf.environ=development_codezm'; \
        echo 'yaf.use_namespace=0'; \
        echo; \
        echo '[inotify]'; \
        echo 'extension=inotify.so'; \
        echo; \
        echo '[swoole]'; \
        echo 'extension=swoole.so'; \
        echo; \
        echo '[trie_filter]'; \
        echo 'extension=trie_filter.so'; \
    } | tee -a "$PHP_INI_DIR/php.ini" \
	######################################
	# clear.                             #
	######################################
    \
    && rm -rf /tmp/* /tmp/.[!.]* /tmp/..?* ~/.pearrc \
    && runDeps="$( \
            scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
                | tr ',' '\n' \
                | sort -u \
                | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
        )" \
	&& apk add --no-cache --virtual .php-rundeps $runDeps \
    && apk del .build-deps \
	&& { find /usr/local/bin /usr/local/sbin -type f -perm +0111 -exec strip --strip-all '{}' + || true; }

WORKDIR /web

EXPOSE 9000

ENTRYPOINT ["php"]
