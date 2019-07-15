# instagram-crawler
This application let's you store all of instagram post details provided by an specific hashtag.

## Requisites
You need to have these technologies installed on your machine: 
* mongodb
* redis

> Also don't forget about the mongodb driver for your version of php
ie: `php7.2-mongodb`

## Usage
1. Run ```composer install``` 

2. Run ```php artisan posts:store --hashtag=love``` in your CLI
this command will store all the retrieved data into `redis` or any other queue driver of your favor.
you need to run ```php artisan queue:work``` to trigger the queue worker and persist your data in mongodb database.

> Note that you can avoid using `--hashtag` option which will end up scrapping data using a pre-defined value.
