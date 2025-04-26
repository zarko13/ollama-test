<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pgvector\Laravel\Vector;

class Embedding extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_id',
        'type',
        'embedding',
        'metadata'
    ];

    protected $casts = [
        'embedding' => Vector::class,
        'metadata' => 'array',
    ];

    public static $_TYPE_TEXT = 1;
}
