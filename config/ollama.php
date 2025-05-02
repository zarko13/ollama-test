<?php

return [

    'api_url' => env('OLLAMA_API_URL','http://127.0.0.1:11434/api/'),
    'embedding_model' => env('OLLAMA_EMBEDDING_MODEL','mxbai-embed-large:latest'),
    'number_of_neighbors' => env('OLLAMA_NUMBER_OF_NEIGHBORS',5),
    'vector_size' => env('OLLAMA_VECTOR_SIZE',1024)

];
