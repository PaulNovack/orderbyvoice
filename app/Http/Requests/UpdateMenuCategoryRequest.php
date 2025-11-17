<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuCategoryRequest extends FormRequest
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
        $category = $this->route('menu_category');
        $categoryId = is_object($category) ? $category->id : $category;
        $companyId = $this->user()->company_id;

        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:50', "unique:menu_categories,slug,{$categoryId},id,company_id,{$companyId}"],
        ];
    }
}
