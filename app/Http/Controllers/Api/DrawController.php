<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\User;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function putMarkDrawSucceeded(Request $request, Draw $draw, User $user) {
        $draw->users()->updateExistingPivot($user->id, ['succeeded' => 1]);

        return response()->json([]);
    }
}
