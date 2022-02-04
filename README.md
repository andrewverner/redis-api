## Redis API

1. Run docker containers `docker-compose up --build`

2. Open `balancer_php` terminal `docker exec -it -u root balancer_php sh`

3. Install dependencies `composer install`


There are 2 endpoints:
`GET api/stat`. Parameter `code` should be passed as a query variable or as a JSON object field. Ex:
```json
{"code": "ru"}
```

`POST api/stat` returns JSON object with counters. Ex:

```json
{
    "ru": 5,
    "en": 2
}
```

## Docker infrastructure

There are 6 containers:
1. `balancer_php` (PHP interpreter)
2. `balancer_proxy` main NGINX container that retrieves connections and works as a balancer
3. `balancer_node{n}` NGINX containers with the same code base
4. `balancer_redis` Redis instance

To add more HTTP nodes:
1. copy-past one of the node config within `docker-compose.yml` file
2. change nodes names
3. add those nodes names into `./docker/nginx/proxy.conf` file

Если я правильно понял, то остноная цель задания - высокая нагрузка описаная как "1000 запросов в секунду".
Код контроллера, который увеличивает счётчики и возвращает их значния - всего несколько строк. Оптимизировать
там по сути нечего. Поэтому единственное решение, которое я пока вижу, для работы с такими нагрузками -
балансировка между несколькими нодами. Плюс ко всему добавляется ещё проблема того, что какой фреймворк не
выбери - все они делают много лишней для простого API инициализаций. Можно либо почитить код ядра фреймворка,
чтобы выключить всё лишнее, либо выбрать более подходящий по цели API фреймворк, например Lumen или Comet,
но проюлема Lumen в том, что в нём очень сложно настроить работу с Redis как с базой данных, а не кэш-хранилищем.
Проблема Comet - настройка его в докере
