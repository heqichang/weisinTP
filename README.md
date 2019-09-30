

---
* [开发环境](#开发环境)
* [环境配置](#环境配置)
* [容器部署](#容器部署)


---


# 开发环境


 + php 5.6.40
 + mysql 5.7.26
 + redis 4.0.9
 

# 环境配置

* 获取代码后，首先用 composer 安装各项依赖
* 数据库里执行 init.sql
* 配置 nginx，路径指向你本地代码的 public 目录，完整配置类似如下，也可参考项目根目录下 site.conf

~~~
server {
    listen 80;
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

}

~~~

* 在项目根目录下创建 .env 文件，配置如下：

~~~
//API配置
[API]
JWT_SECRET = '938jdsldendkshcmndmaiueoos*jdfs;dfn#sdfksl0.ers*233dwdds.dsdsdsn'
JWT_EXP=31536000
ACCESS_CONTROL_ALLOW_ORIGIN = "*"

//数据库配置
[DATABASE]
TYPE='mysql'
HOSTNAME='mysql'
DATABASE='weisin'
USERNAME='root'
PASSWORD='weisin'
HOSTPORT='3306'

//redis配置
[REDIS]
HOST=redis
KEY_PREFIX=weisin
EXPIRE=0
PASSWORD=''
TIMEOUT=0
PORT=6379

[URL]
IMAGE_BASE_URL='http://pydq4fkyl.bkt.clouddn.com/'


[QINIU]
ACCESSKEY='fXLewyUKoQ-hn9R5ZfLYeHKalxkh098aVmn-cWEc'
SECRETKEY='ud3cr-4-aIRu4Bitb_diXZEGpr4msc-R2ejLSQQ1'
BUCKET='weisin365'
~~~

* 如果是自己部署在本机的话，参考自己本机参数修改 .env 文件，如果使用下面容器部署可以不用更改 .env

# 容器部署

* 需要在项目根目录下先执行以下命令（pecl 安装 redis 扩展会经常报错，可以多试几次）

~~~
docker build -t heqichang/weisin-basefpm .
~~~

* 然后进入 docker 目录执行以下命令就可以运行了

~~~
docker-compose up -d
~~~

* 停止运行

~~~
docker-compose down
~~~

* localhost 默认 8800 是 api 服务器，8801 是 phpmyadmin，8802 是 phpredisadmin，数据库默认用户名 root ，密码 weisin

## 注意事项
使用容器部署，第一次部署的时候，本地没有找到相关镜像，会去下载镜像会很慢，可参考这篇文章加上加速器下载，[https://www.jianshu.com/p/405fe33b9032](!https://www.jianshu.com/p/405fe33b9032)。第一次成功部署之后，下次 up 运行不会重新下载，就很快了。
