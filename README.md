# protestart-backend

# Setup

Follow the official laravel installation guide:

https://laravel.com/docs/6.x/installation

Then follow this guide for environment setup:

https://laravel.com/docs/6.x/configuration#environment-configuration

At the moment, the following services configuration are needed:
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=false
APP_URL=

LOG_CHANNEL=stack

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=


#Accessing the API

Available APIs:

POST /api/users/register
POST /api/users/login
GET  /api/users/is_alive
GET  /api/arts
GET  /api/arts/{art_id}
POST /api/arts
