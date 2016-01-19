# SocialNetwork

works pretty similar to a well known social network, 
but you can host it on your very own infrastructure or intranet. 

## Demo
http://www.dasmerkendienie.com/


##API Documentation
http://www.dasmerkendienie.com/help/

##Features:

* Share (Websites, Images, Videos, SourceCode)
* Like / Dislike / Comments
* #hash tag search (orderd by popularity)
* REST API
* Backend



# Instalation

## Download the Sourcecode

Via GIT: 
```
git clone https://github.com/andreas83/SocialNetwork.git
```
Via Download:

https://github.com/andreas83/SocialNetwork/archive/andrea.zip

## Configuration 

you can adjust the db credentials in app/config/main.cfg 
```
db_name="dmdn"
db_user="root"
db_pass="nv8xy0815d"
```
You will find the [database.sql](https://raw.githubusercontent.com/andreas83/SocialNetwork/andrea/database.sql) file in repro

Also you should adopt following configuration parameter
```
address="http://www.yourdomain.com"
dir="/home/lissi/tmp/dmdn/trunk";
```

For security reasons please don`t forget to change the salat to some other random string
```
salat="KJMnmnNUU&/§N(JH/h80h87fnunu43h8u7"
```

##Permissions

Please make sure that following directories are writeable by webserver
```
public/css/scss/
public/upload
```

via 
```
sudo chown www-data:www-data public/css/scss
sudo chown www-data:www-data public/upload
```


## Webserver 

If you run Apache, you need also activate mod_rewirte 

```
a2enmod rewrite
```

## Sample nginx configuration

```
server {
  listen 217.11.57.227:80;
  listen [2a00:1828:2000:148::3]:80;

  server_name .dasmerkendienie.com;

  access_log /var/log/nginx/www.dasmerkendienie.com.access.log;
  error_log /var/log/nginx/www.dasmerkendienie.com.error.log info;

  root /home/dasmerkendienie/www/dasmerkendienie.com;
  index index.php;

  location ^~ /(css|img|js|upload|bootstrap) {
    # enable CORS (http://enable-cors.org/server_nginx.html)
    if ($http_origin ~ (https?://.*\.dasmerkendienie\.com(:[0-9]+)?)) {
      add_header "Access-Control-Allow-Origin" $http_origin;
    }
    root /home/dasmerkendienie/www/dasmerkendienie.com;
    expires 1d;
  }
  location /public/upload {
    deny all;
  }
  location /app/config {
    deny all;
  }
  location / {
    rewrite ^/public/css/scss.php/(.*)$ /public/css/scss.php?p=$1 last;
    try_files $uri /index.php?$args;

    location ~ .php$ {
      try_files $uri $uri/ =404;
      fastcgi_index index.php;
      fastcgi_read_timeout 1800s;
      fastcgi_send_timeout 1800s;
      fastcgi_pass phpcgi;
      include fastcgi_params;
      fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      fastcgi_param SCRIPT_NAME $fastcgi_script_name;
    }

    # deny access to apache .htaccess files
    location ~ /\. {
      deny all;
    }
  }
}
```
