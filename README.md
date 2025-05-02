# Requirements
1. php
2. composer
3. node
4. npm
5. pgsql (pg_vector exstension)
6. ollama (https://ollama.com/)
7. redis


# Env
 1. setup db connection
 2. set que connection to redis
 3. set filesystem disk to public
 4. set reverb
 5. BROADCAST_CONNECTION=reverb

# Setup
1. Instal ollama
2. Pull required models (ollama pull model_name, all models can be found at Model.php enum plus model for embedding specified in ollama.php config) 
3. composer update
4. npm install
5. php artisan storage:link
6. php artisan db:migrate --seed


# Run
1. ollama serve
2. php artisan serve
3. npm run dev
4. php artisan que:work
5. php artisan reverb:start --port=9000 --debug

# Notes
1. currently best model for chat is gemma3:12b and for embedding nomic-embed-text
2. when adding document you must run php artisan generate:embeddings
3. currently it is used cosine distance with margin od 0.3, test other distance adn margin
4. model instructions needs to be better





 


