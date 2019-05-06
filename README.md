# Installation

* Run `bin/install`, it will:
  * create containers + detouch console.
  * install backend vendors
  * install frontend node_modules + apply config values
  * do frontend build

# Usage

* Open `http://localhost/` in your browser.
* Now press button to get a token.

# Settings places:

All commands are written to be run from the root of the project.
* backend config adjustments, run:
  ```
  $ cp backend/.env backend/.env.local
  ```
  * Change settings
  * `docker kill hubspot-php-api`
  * use `bin/install` again
* frontend config adjustments, run:
  ```
  $ cp frontend/.env frontend/.env.local
  ```
  * Change settings
  * `docker kill hubspot-nginx`
  * use `bin/install` again

# Project docker notes

Docker for project was configured with ports 80 (for localhost as a 'frontend') and 8080 (for localhost as a 'backend'). Https is not needed,
as Hubspot works with localhost on http (if some domain should be used here, so https+ssl cert should be used).
