FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

ADD nginx.conf /etc/nginx/
ADD api.conf /etc/nginx/sites-available/
ADD upstream.conf /etc/nginx/conf.d/upstream.conf

RUN ln -s /etc/nginx/sites-available/api.conf /etc/nginx/sites-enabled/api \
&& rm /etc/nginx/sites-enabled/default

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 11080
#EXPOSE 80
#EXPOSE 443
