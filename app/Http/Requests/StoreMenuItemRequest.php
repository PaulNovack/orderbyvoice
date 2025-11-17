<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
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
            'category_id' => ['required', "exists:menu_categories,id,company_id,{$companyId}"],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'prices' => ['nullable', 'array'],
            'prices.*' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
        ];
    }
}
