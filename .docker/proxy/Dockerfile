FROM nginx:1.15

RUN mkdir -p /etc/ssl/custom
ADD ./custom-ssl/loc.crt /etc/ssl/custom/loc.crt
ADD ./custom-ssl/loc.key /etc/ssl/custom/loc.key

#  default conf for proxy service
COPY ./default.conf /etc/nginx/conf.d/default.conf

# NOT FOUND response
COPY ./backend-not-found.html /var/www/html/backend-not-found.html

# Proxy configurations
COPY ./includes/ /etc/nginx/includes/

EXPOSE 80:80
EXPOSE 443:443