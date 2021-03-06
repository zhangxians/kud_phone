FROM nginx:mainline-alpine
LABEL maintainer="Jaak zhang <xxxx@163.come>"

COPY start.sh /start.sh
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY site.conf /etc/nginx/sites-available/default.conf

RUN echo "#aliyun" > /etc/apk/repositories
RUN echo "https://mirrors.aliyun.com/alpine/v3.10/main/" >> /etc/apk/repositories
RUN echo "https://mirrors.aliyun.com/alpine/v3.10/community/" >> /etc/apk/repositories
RUN apk update

RUN apk add --update curl  php7-fpm php7  php7-dev  php7-apcu  php7-bcmath  php7-xmlwriter  php7-ctype  php7-curl  php7-exif  php7-iconv  php7-intl  php7-json  php7-mbstring php7-opcache  php7-openssl  php7-pcntl  php7-pdo  php7-mysqlnd  php7-mysqli  php7-pdo_mysql  php7-pdo_pgsql  php7-phar  php7-posix  php7-session  php7-xml  php7-simplexml  php7-mcrypt  php7-xsl  php7-zip  php7-zlib  php7-dom  php7-redis php7-tokenizer  php7-gd  php7-fileinfo  php7-zmq  php7-memcached  php7-xmlreader

RUN curl -sS https://getcomposer.org/installer | \
php -- --install-dir=/usr/bin/ --filename=composer

RUN apk add --update bash vim openssh-client supervisor

RUN mkdir -p /etc/nginx && \
mkdir -p /etc/nginx/sites-available && \
mkdir -p /etc/nginx/sites-enabled && \
mkdir -p /run/nginx && \
ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf && \
mkdir -p /var/log/supervisor && \
rm -Rf /var/www/* && \
chmod 755 /start.sh

RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" \
-e "s/variables_order = \"GPCS\"/variables_order = \"EGPCS\"/g" \
/etc/php7/php.ini && \
sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" \
-e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" \
-e "s/user = nobody/user = nginx/g" \
-e "s/group = nobody/group = nginx/g" \
-e "s/;listen.mode = 0660/listen.mode = 0666/g" \
-e "s/;listen.owner = nobody/listen.owner = nginx/g" \
-e "s/;listen.group = nobody/listen.group = nginx/g" \
-e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g" \
-e "s/^;clear_env = no$/clear_env = no/" \
/etc/php7/php-fpm.d/www.conf

EXPOSE 443 80
WORKDIR /var/www

CMD ["/start.sh"]