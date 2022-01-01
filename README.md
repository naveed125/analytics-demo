# Analytics Demo
A demo app showing analytics implementation using Segment and Google Analytics in a PHP/Lumen App.

# Development Environment Setup
1. Copy src/.env.local to src/.env
2. docker-compose up -d --force-recreate
3. docker exec -it `docker ps -q -f name=web` composer install

# Check if its all good
```
% curl -i localhost
HTTP/1.1 200 OK
Server: nginx/1.20.2
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: keep-alive
Vary: Accept-Encoding
X-Powered-By: PHP/8.0.13
Cache-Control: no-cache, private
Date: Sat, 01 Jan 2022 22:23:01 GMT

Analytics Demo App running on local.
```

