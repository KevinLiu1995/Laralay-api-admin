<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:200|min:4',
            'content'   => 'required|string',
			'images' => 'array|max:3'
        ];
    }

	public function messages()
	{
		return [
			'images.max' => '最多上传三张图片'
		];
    }
}
