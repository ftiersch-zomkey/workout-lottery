<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Groups\AddGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function getGroupList(Request $request) {
        $groups = Group::listed()->get();

        return response()->json($groups);
    }

    public function getOwnGroupList(Request $request) {
        if (!Auth::check()) {
            throw new \Exception('No user is logged in');
        }

        $groups = Auth::user()->groups()->listed()->get();

        return response()->json($groups);
    }

    public function postAddGroup(AddGroupRequest $request) {
        $group = Group::create([
            'name' => $request->name
        ]);

        $group->load('group_type');

        return response()->json($group);
    }
}
