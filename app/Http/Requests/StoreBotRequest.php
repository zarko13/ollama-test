<?php

namespace App\Http\Requests;

use App\Enums\Bot\Model;
use Illuminate\Validation\Rule;

class StoreBotRequest extends BaseAsyncRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'model' => ['required', Rule::enum(Model::class)]
        ];
    }
}
