<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Groups\AddGroupRequest;
use App\Http\Requests\Api\Groups\RemoveGroupUserRequest;
use App\Http\Requests\Api\Groups\UpdateGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Return a json encoded list of every group.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupList(Request $request) {
        $groups = Group::listed()->get();

        return response()->json($groups);
    }

    /**
     * Return the list of groups for the currently logged in user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getOwnGroupList(Request $request) {
        if (!Auth::check()) {
            throw new \Exception('No user is logged in');
        }

        $groups = Auth::user()->groups()->listed()->get();

        return response()->json($groups);
    }

    /**
     * Create a new group
     *
     * @param AddGroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddGroup(AddGroupRequest $request) {
        $group = Group::create([
            'name' => $request->name
        ]);

        $group->load('group_type');

        return response()->json($group);
    }

    /**
     * Update given group with new data.
     *
     * @param UpdateGroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function putEditGroup(UpdateGroupRequest $request, Group $group) {
        $group->load('group_type');

        $group->fill($request->all());
        $group->save();

        return response()->json($group);
    }

    /**
     * Delete given group.
     *
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteGroup(Request $request, Group $group) {
        $group->delete();

        return response()->json($group);
    }

    /**
     * Return json encoded list of all draws that group had so far.
     *
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function listGroupDraws(Request $request, Group $group) {
        $draws = $group->draws;

        return response()->json($draws);
    }

    /**
     * Return json encoded list of all users that are part of this group.
     *
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function listGroupUsers(Request $request, Group $group) {
        $users = $group->users;

        return response()->json($users);
    }

    /**
     * Add given user to given group.
     *
     * @param Request $request
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGroupUser(Request $request, Group $group, User $user) {
        $group->users()->attach($user->id);

        return response()->json([]);
    }

    /**
     * Remove given user from given group.
     *
     * @param RemoveGroupUserRequest $request
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeGroupUser(RemoveGroupUserRequest $request, Group $group, User $user) {
        $group->users()->detach($user->id);

        return response()->json([]);
    }
}
