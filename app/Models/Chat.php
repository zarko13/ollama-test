<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bot_id',
        'name',
        'status'
    ];

    public static $_STATUS_OPEN = 1;
    public static $_STATUS_CLOSED = 2;

    public static function getStatuses(){
        return [
            self::$_STATUS_OPEN => 'Open',
            self::$_STATUS_CLOSED => 'Closed'
        ];
    }

    public function getStatus(){
        return self::getStatuses()[$this->status];
    }

    public function isOpen(){
        return $this->status == self::$_STATUS_OPEN;
    }

    public function scopeLatestById($query){
        return $query->orderBy('id', 'desc');
    }

    public function toArray(){
        $data = parent::toArray();

        $data['display_status'] = $this->getDisplayStatusAttribute();
        $data['display_bot'] = $this->getDisplayBotAttribute();
        $data['is_open'] = $this->getIsOpenAttribute();

        return $data;
    }

    public function getDisplayStatusAttribute(){
        return $this->getStatus();
    }

    public function getDisplayBotAttribute(){
        return $this->bot ? ($this->bot->name . '(' . $this->bot->type . ')') : null;
    }

    public function bot() : BelongsTo {
        return $this->belongsTo(Bot::class);
    }

    public function messages() : HasMany {
        return $this->hasMany(ChatMessage::class);
    }

    public function messageHistory() : HasMany {
        return $this->hasMany(ChatMessage::class)->oldestById();
    }

    public function getIsOpenAttribute(){
        return $this->isOpen();
    }

    public function getFormattedMessageHistory(){
        $history = [self::getSystemMessage()];
        foreach ($this->messageHistory as $message) {
            $history[] = $message->formatForHistory();
        }

        return $history;
    }

    public static function getSystemMessages(){
        return [

            'role' => 'system',
            'content' => 'Tvoje ime je Alfred i radiš kao chat bot u odjelu za podršku. Svaki novi razgovor započni tako što ceš reći svoje ime u ulogu. U konverzacijama bud pristojan i koncizan. Nemoj izmišljati odgovore. Kada ne znas odogovr na određeno pitanje korisniku pouni da ga preusmjeris na nekoga iz tima za podršku.'
        ];
    }
}
