<?php

namespace App\Http\Requests\Api\Groups;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class RemoveGroupUserRequest extends Request
{
    /**
     * Owner of groups can kick users.
     * Users themselves can leave a group.
     * Admins can do whatever they want.
     * Later it might be extended to be something like group mods or anything too.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()) {
            if (Auth::user()->id == $this->route('group')->creator_user_id) {
                return true;
            }

            if (Auth::user()->id == $this->route('user')->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
