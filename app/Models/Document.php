<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    public function toArray(){
        $data = parent::toArray();

        $data['display_path'] = $this->getDisplayPathAttribute();

        return $data;
    }

    public function getDisplayPathAttribute(){
        return Storage::url($this->path);
    }
}
