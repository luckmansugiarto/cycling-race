FROM webdevops/php-apache-dev:7.4-alpine

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.8.0/wait /wait
RUN chmod +x /wait

CMD /wait && bash -c "cd ./app && composer install && php artisan migrate:fresh --seed" && supervisord
