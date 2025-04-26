<?php

namespace App\Http\Requests;


class CloseChatRequest extends BaseAsyncRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'exists:chats,id']
        ];
    }
}
