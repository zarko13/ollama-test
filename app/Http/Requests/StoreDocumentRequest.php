<?php

namespace App\Http\Requests;


class StoreDocumentRequest extends BaseAsyncRequest
{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'file' => ['required', 'file']
        ];
    }
}
