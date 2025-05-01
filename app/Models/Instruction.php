<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instruction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'bot_id',
        'content'
    ];

    public function bot() : BelongsTo {
        return $this->belongsTo(Bot::class);
    }

    public function scopeOldestById($query){
        return $query->orderBy('id', 'asc');
    }

}
