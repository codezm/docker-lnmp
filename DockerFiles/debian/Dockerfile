FROM debian:jessie

RUN { \
    echo 'deb http://mirrors.aliyun.com/debian/ jessie main non-free contrib'; \
    echo 'deb http://mirrors.aliyun.com/debian/ jessie-proposed-updates main non-free contrib'; \
    echo 'deb-src http://mirrors.aliyun.com/debian/ jessie main non-free contrib'; \
    echo 'deb-src http://mirrors.aliyun.com/debian/ jessie-proposed-updates main non-free contrib'; \
    } | tee /etc/apt/sources.list \
    && apt-get update \
    && echo "Asia/Shanghai" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

CMD ["bash"]
