<?php

namespace App\Http\Requests;


class StoreChatRequest extends BaseAsyncRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'bot_id' => ['required', 'exists:bots,id']
        ];
    }
}
