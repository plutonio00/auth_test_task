Установка

1. Настройка окружения (laradock)

`git clone https://github.com/Laradock/laradock.git`

`cd laradock`

`cp env-example .env`

`docker-compose up -d nginx mysql`

В случае ошибки mysql 'too many arguments' нужно добавить в 
файл docker-compose.yml в секцию настроек mysql следующую команду

`command:
        - --innodb-buffer-pool-size=512MB`
        
1.1. Настройка nginx

`cd ./nginx/sites`

`cp default.conf <domain-name.conf>`

Далее в новом конфигурационном файле необходимо указать server_name и root 

Подробная информация о проекте: https://github.com/plutonio00/auth_test_task/wiki
