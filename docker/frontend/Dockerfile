FROM node:14-alpine

# Create app directory
WORKDIR /var/www

COPY ./frontend/package.json ./
COPY ./frontend/yarn.lock ./

RUN yarn install --no-cache

EXPOSE 8080
CMD [ "yarn", "dev" ]
