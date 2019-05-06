FROM debian:jessie

RUN apt-get update && apt-get install -y curl apt-transport-https \
	&& curl -sL https://deb.nodesource.com/setup_11.x -o nodesource_setup.sh \
	&& bash nodesource_setup.sh \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y gcc g++ make nodejs yarn nginx

COPY nginx /etc/nginx/

RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf \
	&& rm /etc/nginx/sites-enabled/default \
	&& touch /var/log/nginx/frontend_error.log \
	&& touch /var/log/nginx/frontend_access.log \
	&& touch /var/log/nginx/backend_error.log \
	&& touch /var/log/nginx/backend_access.log \
	&& usermod -u 1000 www-data

WORKDIR /var/www/frontend

EXPOSE 80
EXPOSE 8080

CMD ["nginx"]