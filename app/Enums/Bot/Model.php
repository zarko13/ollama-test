<?php

namespace App\Enums\Bot;

enum Model : string {
    case GRANITE = 'granite3-dense:8b';
    case GEMMA_3 = 'gemma3:latest';
    case GEMMA_3_12B = 'gemma3:12b';
    case LLAMA_3_2 = 'llama3.2:latest';

    public static function getOptions(){
        return [
            self::GRANITE->value => 'Granite',
            self::GEMMA_3->value => 'Gemma 3',
            self::GEMMA_3_12B->value => 'Gemma 3:12b',
            self::LLAMA_3_2->value => 'Llama 3.2',
        ];
    }

    public function getName(){
        return self::getOptions()[$this->value];
    }
}
