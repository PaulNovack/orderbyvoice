<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuSizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->user()->company_id;

        return [
            'name' => ['required', 'string', 'max:50'],
            'size_note' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['integer', 'min:0'],
            'category_id' => ['nullable', "exists:menu_categories,id,company_id,{$companyId}"],
        ];
    }
}
