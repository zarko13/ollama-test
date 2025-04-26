<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'path'
    ];


    public function scopeLatestById($query){
        return $query->orderBy('id', 'desc');
    }
}
