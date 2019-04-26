FROM debian:jessie

RUN apt-get update && apt-get install -y curl apt-transport-https \
	&& curl -sL https://deb.nodesource.com/setup_11.x -o nodesource_setup.sh \
	&& bash nodesource_setup.sh \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y gcc g++ make nodejs yarn nginx

ADD nginx.conf /etc/nginx/
ADD api.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/api.conf /etc/nginx/sites-enabled/api \
    && rm /etc/nginx/sites-enabled/default \
	&& usermod -u 1000 www-data

WORKDIR /var/www/frontend

CMD ["nginx"]

EXPOSE 12080
