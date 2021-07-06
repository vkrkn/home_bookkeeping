<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['logout','me']);
    }

    public function register(Request $request)
    {
        $this->validate($request,
        [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,25|confirmed',
        ]);

        $user = new User($request->only(['name', 'email', 'password']));
        $user->password = bcrypt($request->password);
        $user->api_token = Str::random(60);
        $user->save();

        $user->balance()->create(['total' => 0]);
        $user->categories()->createMany(Category::DEFAULT_CATEGORY);

        return response()->json([
            'success' => true,
            'user' => $user->info()
        ]);
    }

    public function login(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email',
                'password' => 'required|between:6,25',
            ]);

        $user = User::whereEmail($request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $user->api_token = Str::random(60);
            $user->save();

            return response()->json([
                'success' => true,
                'user' => $user->info()
            ]);
        }

        return response()->json([
            'error' => 'Ошибка авторизации. Проверьте email или пароль'
            ]
        , 422);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function me(Request $request) {
        $user = $request->user();
        return response()->json($user->info());
    }
}
