FROM ubuntu:latest
WORKDIR /var/www/html
RUN apt-get upgrade && apt-get update
RUN apt-get -y install software-properties-common nano
RUN apt-get -y install git --assume-yes
RUN add-apt-repository ppa:ondrej/php && apt-get update
RUN apt-get -y install php7.4 && php -v
RUN apt-get install -y php7.4-cli php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath
#COPY ./install-composer .
#COPY ./composer.json .
RUN git clone https://github.com/ericposas/lister-api.git
RUN cp -r lister-api/* .
COPY ./install-composer-libs .
RUN ./install-composer && composer --version
RUN if [ -f "index.html" ]; then rm ./index.html; fi;
RUN ps -ef | grep apache && /usr/sbin/apache2 -V | grep SERVER_CONFIG_FILE
COPY ./apache.conf /etc/apache2/apache2.conf
RUN a2enmod rewrite
RUN apachectl -k graceful

EXPOSE 80
EXPOSE 443

CMD bash -c "./install-composer-libs && apachectl -D FOREGROUND"
