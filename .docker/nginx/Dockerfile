FROM nginx:1.27.0

ARG SITE_HOST

COPY ./.docker/nginx/conf.d/nginx.local.conf /etc/nginx/conf.d/nginx.local.conf
RUN sed -i -e "s/SITE_HOST/$SITE_HOST/" /etc/nginx/conf.d/nginx.local.conf

CMD ["nginx", "-g", "daemon off;"]
