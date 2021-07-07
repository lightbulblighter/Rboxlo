FROM node:14-alpine
ARG SERVER_PORT
ENV DOCKER true

# Create source folder
RUN mkdir /app
WORKDIR /app

# Copy server files
COPY /Server/package*.json ./
COPY /Server ./
RUN rm -rf ./node_modules
RUN npm install

# Copy branding files
COPY /Branding/Artwork/Backdrops/Bricks.png ./wesbites/eclipse/public/img/art/bricks.png
COPY /Branding/Artwork/Backdrops/Main.png ./websites/eclipse/public/img/art/default.png
COPY /Branding/Logos/Primary/Big.png ./websites/eclipse/public/img/brand/large.png
COPY /Branding/Logos/Primary/Small.png ./websites/eclipse/public/img/brand/small.png

# Copy files necessary for packaging
COPY /.git/refs/heads/trunk ./commit

# Grant permissions on application folder to all users as we are about to switch users
RUN chmod -R 777 /app

# Create and switch to a non-root user
RUN adduser \
    --disabled-password \
    --gecos "" \
    --no-create-home \
    "app"
USER app

EXPOSE ${SERVER_PORT}
CMD [ "npm", "start" ]