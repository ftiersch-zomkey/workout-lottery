<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getOwnUser(Request $request) {
        if (!Auth::check()) {
            throw new Exception('Not logged in');
        }
        return response()->json(Auth::user());
    }
}
