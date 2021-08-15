# Example for local mysql instance configured with Docker
DB_NAME=ListerAPI \
DB_HOST=db-local \
DB_ROOT_USER=root \
DB_ROOT_PASSWORD=WHATEVER_YOU_WANT
### note: if you change your db password or anything related, you may need to run docker volume rm and/or docker-compose rm -v and remove all lingering volumes

# Sign up for Auth0 account -- configure these values
AUTH0_SUB= \
AUTH0_DOMAIN= \
AUTH0_CLIENT_ID= \
AUTH0_CLIENT_SECRET= \
AUTH0_REDIRECT_URI= \
AUTH0_LOGOUT_URI=
