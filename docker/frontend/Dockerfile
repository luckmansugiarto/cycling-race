FROM node:14-alpine

# Create app directory
WORKDIR /var/www

RUN yarn global add @quasar/cli

EXPOSE 8080
CMD [ "quasar", "dev" ]
