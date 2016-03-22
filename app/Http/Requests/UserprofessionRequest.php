<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserprofessionRequest extends Request
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

        if($this->request->get('field')=="graduation_id") {
            return [
                'graduation_id' => 'required|integer'
            ];
        }
        else
        {
            return [

            ];
        }
    }
}
