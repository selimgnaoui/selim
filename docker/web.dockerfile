FROM nginx:1.13

RUN rm /etc/nginx/conf.d/default.conf

ADD ./_app.conf /etc/nginx/_app.conf
ADD ./vhosts /etc/nginx/conf.d