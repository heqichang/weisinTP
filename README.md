

## 开发环境



 + php 5.6.40
 + mysql 5.7.26
 + redis 4.0.9
 

## 安装部署

1. 获取代码后，首先用 composer 安装各项依赖
2. 数据库里执行 init.sql
3. 配置 nginx，路径指向你本地代码的 public 目录，完整配置类似如下：

~~~
server {
    listen 80;
    listen 443 ssl http2;
    server_name .weisin.app;
    root "/home/vagrant/code/tp5/public"; # 这里替换成你自己的路径

    index index.html index.htm index.php;

    charset utf-8;

    location / {
		if (!-e $request_filename) {
                rewrite  ^(.*)$  /index.php?s=/$1  last;
    	}
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/weisin.app-error.log error;

    sendfile off;

    client_max_body_size 100m;

	 # 这里也要你配置自己本地的 php-fpm 路径
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php5.6-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;


        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    ssl_certificate     /etc/nginx/ssl/weisin.app.crt;
    ssl_certificate_key /etc/nginx/ssl/weisin.app.key;
}

~~~



