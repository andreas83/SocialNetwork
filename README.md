[![Build Status](https://travis-ci.org/andreas83/SocialNetwork.svg?branch=master)](https://travis-ci.org/andreas83/SocialNetwork)

## About Project

works pretty similar to a well known social network, but you can host it on your very own infrastructure. No external dependencies needed. Focus of this project is stability. security and performance.

## Anouncement

On 13.02.2020 i decided to mark version 2.0 as deprecated and started to rewrite everything from scratch.
At moment the master has less features than 2.0, but iam working hard to restore the most important ones.
Feel free to join the development process by creating tasks (feature request) or make code improvements.


## 0. Demo

https://dev.codejungle.org

## 1. Installation

    git clone https://github.com/andreas83/SocialNetwork.git
    cd SocialNetwork
    composer install
    npm install

### 1.2 Configuration

Check the .env.example file for db settings


### 1.3 Database

This will create the db structure

    php artisan migrate

### 1.3 Folders / Permissions

Create storage folder (symbolic link)

    php artisan storage:link

Make phantomsjs executeable (needed for og tag parsing)

    chmod +x bin/phantomjs

### 1.4 Webserver (nginx)

This is just a example configuration of our dev system

    server {

      server_name dev.codejungle.org;
      root /var/www/dev.codejungle.org/public;
      index index.html index.php;
      location ~ \.php$ {
              include snippets/fastcgi-php.conf;
              fastcgi_pass unix:/run/php/php7.4-fpm.sock;
      }

      location ~ /\.ht {
              deny all;
      }

      location / {
          try_files $uri $uri/ /index.php?$args;
      }



      listen 443 ssl; # managed by Certbot
      ssl_certificate /etc/letsencrypt/live/dev.codejungle.org/fullchain.pem; # managed by Certbot
      ssl_certificate_key /etc/letsencrypt/live/dev.codejungle.org/privkey.pem; # managed by Certbot
      include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
      ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

    }
    server {
      if ($host = dev.codejungle.org) {
          return 301 https://$host$request_uri;
      } # managed by Certbot

          server_name dev.codejungle.org;

      listen 80;
      return 404; # managed by Certbot


    }


## 2. Support

You can use the github issue tracker for bugs.

For commercial support please contact: support@moving-bytes.at


## 3. History
### Version 1.0 (est 2008)

![Screenshoot of verion 1.0](https://social.codejungle.org/upload/6008a1154e1ef3.12760041_new_vote_style.jpg)


### Version 2.0  (est 2014)

![Screenshoot of verion 2.0](https://social.codejungle.org/upload/5e43db0e34a814.07174424_Screenshot_20200212_113721.png)


This version was a complete rewrite, build on a my very own php framework and with the help of react for frontend stuff. Sadly the frontend code became not maintainable also i made huge mistakes how the components communicate with each other.

Working features:
* Share (Websites, Images, Videos, SourceCode)
* Like / Dislike / Comments
* #hash tag search (orderd by popularity)
* @user mentions and notifications via websockets
* REST API
* Oauth2 (Facebook, Github)
* Backend with Dashboard

One side project was to visualize the related hashtags.

![Visualization of the related Hashtags](https://social.codejungle.org/upload/56f48025dc02d4.12264426_dashboard2.jpg)


Another cool feature was the Google Chrome extension to share content with just a click.
![Screenshoot of Google Chrome extension](https://social.codejungle.org/upload/5e43db94a6b792.10501109_Screenshot_20200212_120334.png)


**Please keep in mind Version 2.0 is not longer maintained**

Demo is still alive here: https://social.codejungle.org/


### Version 3.0 (est 2020)

Please report feature requests and bugs.
Demo is here: https://dev.codejungle.org/

Working features are:

* Share, Commnet, Reshare, Likes
* OpenGraph Tag parsing
* OAuth login via Github and Facebook

**Attention**
This is a early stage beta, things can change drasticly.


## 4. Donate
Bitcoins: 1GqMSGseij18JnAoB9f3LHJRozNr1QeHkh

Ethereum: 0x6788024D1D36641DDE7832ce9B0300eBbD7C4832
