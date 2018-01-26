FROM alpine:3.4

ARG OS_VERSION=3.4

# 时区配置
ENV TIMEZONE=Asia/Shanghai

# 更换为国内镜像，以加速docker image制作速度，非中国内用户请注释掉
RUN { \
        echo "https://mirrors.aliyun.com/alpine/v${OS_VERSION}/main"; \
        #echo '@community https://mirrors.aliyun.com/alpine/v${OS_VERSION}/community'; \
        #echo '@testing http://mirrors.aliyun.com/alpine/edge/testing'; \
    } | tee /etc/apk/repositories \
    && apk update && apk upgrade \
    && apk add tzdata \
    && ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime \
    && echo $TIMEZONE > /etc/timezone

# 容器命令执行
CMD ["sh"]
