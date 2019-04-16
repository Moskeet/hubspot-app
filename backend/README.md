# MySQL

Login as a user, who can create db + user.

Run:
```mysql
CREATE DATABASE `wickedreport-hubspot-db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER `wickedreport-hubspot-u`@`localhost` IDENTIFIED BY '3478u6r3ir';
GRANT ALL PRIVILEGES ON `wickedreport-hubspot-db`.* TO `wickedreport-hubspot-u`@`localhost`;
FLUSH PRIVILEGES;
```

# Nginx

Config for nginx (php7.2 used as FPM):
```
server {
    listen               80;
    server_name backend-hubspot.wr.loc;
    root /[project root]/backend/public;

    location / {
        # try to serve file directly, fallback to index.php
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/backend-hubspot.wr_error.log;                                                                                                                                               
    access_log /var/log/nginx/backend-hubspot.wr_access.log;                                                                                                                                             
}
```