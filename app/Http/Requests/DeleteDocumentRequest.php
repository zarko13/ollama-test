<?php

namespace App\Http\Requests;


class DeleteDocumentRequest extends BaseAsyncRequest
{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'exists:documents,id']
        ];
    }
}
