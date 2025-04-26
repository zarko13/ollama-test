<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'type',
        'content'
    ];

    public static $_TYPE_BY_USER = 1;
    public static $_TYPE_BY_SYSTEM = 2;

    public function scopeOldestById($query){
        return $query->orderBy('id', 'asc');
    }

    public function chat() : BelongsTo {
        return $this->belongsTo(Chat::class);
    }

    public function toArray(){
        $data = parent::toArray();

        $data['is_by_user'] = $this->getIsByUserAttribute();

        return $data;
    }

    public function getIsByUserAttribute(){
        return $this->type == self::$_TYPE_BY_USER;
    }
}
