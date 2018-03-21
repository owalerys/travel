<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProfileController extends Controller
{

    public function permissions()
    {
        return response()->json([
            'roles' => Auth::user()->roles,
            'permissions' => Auth::user()->permissions
        ]);
    }

    public function show()
    {
        return response()->json(User::whereId(Auth::user()->id)->with(['roles.permissions', 'permissions'])->first());
    }

}
