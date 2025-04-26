<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'status'
    ];

    public static $_STATUS_PENDING = 1;
    public static $_STATUS_PROCESSING = 2;
    public static $_STATUS_FAILED = 3;
    public static $_STATUS_PROCESSED = 4;

    public static function getStatuses(){
        return [
            self::$_STATUS_PENDING => 'Pending',
            self::$_STATUS_PROCESSING => 'Processing',
            self::$_STATUS_FAILED => 'Failed',
            self::$_STATUS_PROCESSED => 'Processed'
        ];
    }

    public function getStatus(){
        return self::getStatuses()[$this->status];
    }


    public function scopeLatestById($query){
        return $query->orderBy('id', 'desc');
    }

    public function toArray(){
        $data = parent::toArray();

        $data['display_path'] = $this->getDisplayPathAttribute();
        $data['display_status'] = $this->getDisplayStatusAttribute();

        return $data;
    }

    public function getDisplayPathAttribute(){
        return Storage::url($this->path);
    }

    public function getDisplayStatusAttribute(){
        return $this->getStatus();
    }

    public function embeddings() : HasMany {
        return $this->hasMany(Embedding::class);
    }

}
