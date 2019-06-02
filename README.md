# Extract audio (mp3) from video (mp4)

### Installation
1. Make sure you're using PHP 7.1 or higher and have composer installed
1. Make sure you have installed `git`
1. Clone repository: git clone https://github.com/c3zi/promo-audio-extractor.git
1. Install ffmpeg library `sudo apt-get update && sudo apt-get install ffmpeg`
1. Install all dependencies using composer: `composer install --no-dev`
1. Prepare storage directory: `composer storage`
1. Prepare logs directory: `composer logs`
1. Make sure that storage and logs directory are writable
1. Run `composer start` command to start PHP server (8080 port) 

#### Docker
1. Make sure you have installed `git`
1. Clone repository: git clone https://github.com/c3zi/promo-audio-extractor.git
1. Make sure you have installed `docker` and `docker-compose`

   1. `sudo apt-get install docker.io`
   
   2. `sudo curl -L "https://github.com/docker/compose/releases/download/1.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose`
   
   3. sudo chmod +x /usr/local/bin/docker-compose
 
1. `docker-compose up -d`
1. `docker-compose exec application composer install --no-dev`
1. `docker-compose exec application composer storage`
1. `docker-compose exec application composer logs`
1. Make sure that storage and logs directory are writable

### Usage
API consists of two endpoints:

1. Extract mp3 from a promo site

`/api/promo2mp3?tag=<tag>` (http method: GET) (i.e. /api/promo2mp3?tag=facebook-ads) 

* allowed tags: travel, gaming, ecommerce, marketing-videos, facebook-ads

2. Download extracted mp3

`/api/mp3/<id>` (http method: GET) (i.e. /api/mp3/5b5f027cf8bb6eba3a7b23c6) 

* id can be taken from the first endpoint 

### Quality
To run code quality checks just run a command: `composer qa`

It runs three commands:

1. ```./vendor/bin/parallel-lint --exclude vendor .```

2. ```./vendor/bin/phpcs -s src tests```

3. ```./vendor/bin/phpunit```

* Be sure that you have installed composer `dev` dependencies
