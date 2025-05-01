<?php

namespace App\Models;

use App\Enums\Bot\Model as BotModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'model'
    ];

    protected function casts(): array{
        return [
            'model' => BotModel::class,
        ];
    }

    public function instructions(): HasMany{
        return $this->hasMany(Instruction::class);
    }

    public function documents(): HasMany{
        return $this->hasMany(Document::class);
    }

    public function scopeLatestById($query){
        return $query->orderBy('id', 'desc');
    }

    public function formatInstructionsForChat(){
        $instructions = [];
        foreach ($this->instructions as $instruction) {
            $instructions[] = $instruction->formatForChat();
        }

        return $instructions;
    }
}
