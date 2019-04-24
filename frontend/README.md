# Installation:

Project requires:
* nodejs
* npm
* yarn

To install packages (run from the root of frontend project):
```
$ yarn install
```

To run the frontend (run from the root of frontend project):
```
$ yarn start
```
You'll be prompted to use frontend (usually) from http://localhost:3000/ . It's a
1-page application which shows either "Connect Hubspot" button, or connection data
(token). 

## Project specific places

Configuration:
```bash
$ cp .env .env.local
```
Here (in .env.local) you can adjust settings:
* hubspot client-id
* backend server url

# Alternative setup with nginx

Required packages:
* nodejs
* npm
* yarn
* nginx

To install packages (run from the root of frontend project):
```
$ yarn install
```

To compile frontend:
```
$ yarn build
```

Folder 'build' will appear. It will be used in nginx config.

## Nginx
Config:
```
server {
    listen               80;
    server_name frontend-hubspot.wr.loc;
    root /[project root]/frontend/build;

    index index.html;

    location / {
    }

    error_log /var/log/nginx/frontend-hubspot.wr_error.log;
    access_log /var/log/nginx/frontend-hubspot.wr_access.log;
}
```
But you need to edit **.env.local** file, `REACT_APP_HUBSPOT_REDIRECT_URL` variable, to use the same domain.