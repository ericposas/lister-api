FROM ubuntu:latest
WORKDIR /var/www/html
RUN apt-get upgrade
RUN apt-get update
RUN apt-get -y install software-properties-common nano
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get -y install php7.4
RUN php -v
RUN apt-get install -y php7.4-cli php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath
RUN php -m
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN composer --version
COPY ./composer.json .
RUN composer install
RUN a2enmod rewrite
RUN if [ -f "index.html" ]; then rm ./index.html; fi;
RUN ps -ef | grep apache && /usr/sbin/apache2 -V | grep SERVER_CONFIG_FILE
COPY ./apache.conf /etc/apache2/apache2.conf
RUN apachectl -k graceful

EXPOSE 80
EXPOSE 443

#CMD ["apachectl", "-D", "FOREGROUND"]
CMD bash -c "composer install && apachectl -D FOREGROUND"
