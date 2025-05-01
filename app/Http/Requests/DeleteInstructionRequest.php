<?php

namespace App\Http\Requests;


class DeleteInstructionRequest extends BaseAsyncRequest
{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'exists:instructions,id']
        ];
    }
}
