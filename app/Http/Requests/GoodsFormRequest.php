<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class GoodsFormRequest extends FormRequest
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
        $rules = [
            'name_arabic' => 'nullable|string|min:0|max:255',
            'name_english' => 'nullable|string|min:0|max:255',
            'price' => 'required|numeric|min:-9999999.99|max:9999999.99',
            'photo' => ['image','nullable','file'],
            'goods_type_id' => 'required',
            'unit_id' => 'required',
            'account_id' => 'required|numeric|min:0',
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['name_arabic', 'name_english', 'price', 'goods_type_id', 'unit_id', 'account_id']);
        if ($this->has('custom_delete_photo')) {
            $data['photo'] = null;
        }
        if ($this->hasFile('photo')) {
            $data['photo'] = $this->moveFile($this->file('photo'));
        }



        return $data;
    }
  
    /**
     * Moves the attached file to the server.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }
        
        $path = config('laravel-code-generator.files_upload_path', 'uploads');
        $saved = $file->store('public/' . $path, config('filesystems.default'));

        return substr($saved, 7);
    }

}