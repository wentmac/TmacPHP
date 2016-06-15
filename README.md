# TmacPHP
TmacPHP MVC Framework是php mvc框架 OOP

## 项目部署
### nginx/apache配置
项目入口目录在wwwroot深层级，这样保证web目录访问不到程序文件，提高安全性。

    server_name  www.weixinshow.com;    
    root         /var/www/www.weixinshow.com/release_1.0/www/wwwroot/;

## 项目目录结构
    +Public
    | + common
    | + js
    | + www
      | + css
      | + js
      | + image
    +www
    | + application
      | + Config
      | + Controller
      | + Model
        | + dao
        | + entity
        | + service
      | + Plugin
      | + View
    | + var
    | + wwwroot
    +base
    | + application
      | + Config
      | + Controller
      | + Model
        | + dao
        | + entity
        | + service
      | + Plugin
      | + View
    | + var
    | + wwwroot
    -Tmac.config.php
    -database.config.php
    
## 项目部署
