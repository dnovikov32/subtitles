FROM node:20.3.1

WORKDIR /app

COPY ./.docker/bootstrap-permissions.sh /bootstrap-permissions.sh

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN /bootstrap-permissions.sh

USER docker

CMD ["/entrypoint.sh"]
