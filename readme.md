# Lister API

API for developing an application that generates Lists for Users

- Create Users
- A User can have many Lists (GenericList class)
- A List can have many Items
- Items entities have table fields / class properties such as name, description, link, image, meta, etc.

## Still to come

- Create ability to Share lists via a Share entity
- Shares will have table columns for specifying recipient / sender

## To Run

- Configure your .env file, the project looks for .env.local or .env.stage at the moment
- `docker-compose --env-file .env.local up --build` or `docker-compose --env-file .env.stage up --build`
