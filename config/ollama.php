<?php

return [

    'api_url' => env('OLLAMA_API_URL','http://127.0.0.1:11434/api/'),
    'embedding_model' => env('OLLAMA_EMBEDDING_MODEL','nomic-embed-text'),
    'number_of_neighbors' => env('OLLAMA_NUMBER_OF_NEIGHBORS',3),
    'vector_size' => env('OLLAMA_VECTOR_SIZE',768),
    'max_distance' => env('OLLAMA_MAX_POSITIVE_DISTANCE', 0.3),

];
