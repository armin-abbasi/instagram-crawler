# instagram-crawler
This application let's you store all of instagram post details provided by an specific hashtag.

## Requisites
You need to have these technologies installed on your machine: 
* mongodb
* redis

> Also don't forget about the mongodb driver corresponding to your php version, ie : `php7.2-mongodb`

> Run ```composer install``` to install required packages

## Usage
> Run ```php artisan posts:store --hashtag=love``` in your CLI, this will queue all the retrieved data.

> You need to run ```php artisan queue:work``` to trigger the queue worker and persist your data in mongodb database.

> Note that you can avoid using `--hashtag` option which will end up scrapping data using a pre-defined value.

## Elasticsearch
You can index your stored data into elasticsearch by utilizing this command :
```php artisan posts:index```
> Use `index_name` to index data with your desired index name.
