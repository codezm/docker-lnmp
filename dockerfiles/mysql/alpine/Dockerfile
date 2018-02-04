FROM codezm/alpine:3.4

RUN apk add --update mysql mysql-client && rm -f /var/cache/apk/*

VOLUME ["/var/lib/mysql"]

COPY ./Startup.sh /Startup.sh

EXPOSE 3306

ENTRYPOINT ["/Startup.sh"]
