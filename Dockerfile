FROM node:16 as build
WORKDIR /app
RUN yarn global add @quasar/cli
COPY ./quasar /app
RUN yarn 
RUN quasar build -m spa

FROM busybox:latest
WORKDIR /app
COPY --from=build ./app/dist/spa .
CMD ["busybox", "httpd", "-f", "-v", "-p", "9000"]
