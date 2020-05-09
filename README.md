# P8_Openclassroom
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/328d0db421c548fba12879a4e8c1c65f)](https://www.codacy.com/manual/lionneclement/P8_Openclassroom?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=lionneclement/P8_Openclassroom&amp;utm_campaign=Badge_Grade)
## Clone
1) Make a clone with `https://github.com/lionneclement/P8_Openclassroom.git` and `cd P8_Openclassroom`
2) Install composer with `composer install`
3) Create database with `php bin/console doctrine:database:create`
4) Create table with `php bin/console doctrine:schema:create`
5) Create data with `php bin/console doctrine:fixtures:load --group=app`

   By default a user was created with email=user@gmail.com and password=password and a admin with email=admin@gmail.com and password=password
   
6) Run server with `bin/console server:run` or `symfony server:start`and go to localhost with port 8000

Normally everything works, If you have a error or send me an mail to lionneclement@gmail.com or create a issue
