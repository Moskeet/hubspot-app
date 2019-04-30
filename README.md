# Installation

* Run `bin/install`, it will:
  * create containers + detouch console.
* Run `bin/extra`, it will:
  * install backend vendors
  * install frontend node_modules + apply config values
  * do frontend build
  * (!) show information, to be added to your local /etc/hosts file, to test the system

# Usage

* Open `https://backend-hubspor.wr.loc/` in your browser. Approve certificate (self-signed).
* Open `https://frontend-hubspor.wr.loc/` in your browser. Approve certificate (self-signed).
* Now press button to get a token.

# Settings places:

All commands are written to be run from the root of the project.
* backend config adjustments, run:
  ```
  $ cp backend/.env backend/.env.local
  ```
  * Change settings
  * stop docker-images with: 'proxy', 'backend', 'php-api'
  * use `bin/extra` again
* frontend config adjustments, run:
  ```
  $ cp frontend/.env frontend/.env.local
  ```
  * Change settings
  * stop docker-images with: 'proxy', 'frontend'
  * use `bin/extra` again (! important to do this, as it will recompile **build** folder with new settings)
