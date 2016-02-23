<?php

namespace App\Http\Requests\Api\Groups;

use App\Http\Requests\Request;

class UpdateGroupRequest extends Request
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
            'name' => 'required',
            'interval_minutes' => 'required|integer|between:5,60',
            'interval_time_start' => 'required|date_format:H:i:s',
            'interval_time_end' => 'required|date_format:H:i:s',
            'number_of_winners' => 'required|integer',
            'finish_exercise_time' => 'required|integer|between:1,60'
        ];
    }
}
