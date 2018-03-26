<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['user' => $user]);
    }

}
