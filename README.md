
Все заказы
Copycurl -X GET http://localhost:8080/api/orders

Конкретный заказ:
Copycurl -X GET http://localhost:8080/api/orders/1

Новый заказ:
Copycurl -X POST -H "Content-Type: application/json" -d '{"user_id":1,"products":[{"product_id":1,"quantity":2},{"product_id":2,"quantity":1}]}' http://localhost:8080/api/orders

Обновить заказ:
Copycurl -X PUT -H "Content-Type: application/json" -d '{"status":"processed"}' http://localhost:8080/api/orders/1

Удалить заказ:
Copycurl -X DELETE http://localhost:8080/api/orders/1


Инструкция для заупска
docker-compose up --build -d
потом сайт не сразу откроется коампузер может долго устанавливать зависимости минут десять 
docker-compose logs -f app     этой командой можно смотреть или есть есть докер десктор в логе baraholka_php

