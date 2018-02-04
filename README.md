Docker-LNMP
======================================================================================================================
为了方便搭建本地开发环境、更便捷切换各服务及扩展版本。如果想个性化配置可以基于此 `Change && Build` 属于你自己的本地 `LNMP` 开发环境。

## 使用
下载后使用 `docker-compose` 管理服务，使用 `docker-compose up` 即可开启所有服务项, 向本地「即: 主机」`hosts` 添加 `127.0.0.1  www.local.com` 打开浏览器并访问 `http://www.local.com` 访问容器服务，你还可以[检测](http://www.local.com/detect.php)各服务项是否正常运行。

其他操作: 关闭本地所有服务 `docker-compose down` , 清扫 `docker system prune --force` 适用于服务部署成功后清除缓存。

## 集成服务
    - alpine/debian     -- 更改为国内镜像源、时区 `Asia/Shanghai`, Version: Alpine - 3.4、Debian - jessie.
    - nginx             -- Nginx     Version: 1.12.1
    - mysql             -- Mysql     Version: 8.0.3-rc-1debian8
    - php               -- PHP       Version: 7.0.27、7.1.13
    - redis             -- Redis     Version: 4.0.6
    - memcached         -- Memcached Version: 1.5.2

## 目录结构
    ▾ dockerfiles/        -- 包含所有服务的 Dockerfile
      ▸ alpine/            
      ▸ debian/            
      ▸ memcached/         
      ▸ mysql/             
      ▸ nginx/             
      ▸ php/               
      ▸ redis/             
    ▸ mysqldb/            -- Mysql数据库数据存储目录
    ▾ webconf/            -- 服务配置目录
      ▸ mysql/             
      ▸ nginx/             
      ▸ php/               
      ▸ phpfpm/            
      ▸ redis/             
    ▾ weblogs/            -- 服务日志
      ▸ mysql/             
      ▸ nginx/             
      ▸ phpfpm/            
    ▸ webroot/            -- 项目目录: Coding
      _env                -- 环境配置相关

## Mysql服务:
Mysql 有两个版本分别基于 `alpine` 和 `debian` 基础镜像，最小镜像原则的用户可以考虑使用基于 `alpine` 镜像创建的Mysql服务，「推荐」使用 `debain` 创建的镜像。

## PHP服务的两种模式
    - php-cli  -- 默认安装的`PHP`扩展: redis、memcached、yaf、swoole、inotify、trie_filter
    - php-fpm  -- 默认安装的`PHP`扩展: redis、memcached、yaf、xdebug

## 感谢
欢迎大家反馈使用意见及建议。

 - 参见 [docker-library](https://github.com/docker-library)
