<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Profile;
class GithubController extends Controller
{
    const NAME = 'GITHUB';
    protected User $authUser;
    //RedirectResponse
    public function __invoke() {
        try {
            $user = Socialite::driver('github')->user();

            DB::transaction(function() use($user) {
                $this->authUser = User::updateOrCreate([
                    'email' => $user->email,
                ],
                [
                    'name' => $user->name,
                    'password' => Hash::make(Str::random(7))
                ]); //->load('interest', 'preference')

                Profile::updateOrCreate([
                    'user_id' => $this->authUser->id,
                    'provider' => self::NAME,
                    'provider_user_id' => $user->id,
                    'nickname' => $user->nickname,
                    'avatar' => $user->avatar,
                    'data' => json_encode($user->user)
                ]);
            }, attempts: 3);

            Auth::login($this->authUser);

            if (is_null($this->authUser->interest)) {
                return redirect()->route('app.interest');
            }

            if(is_null($this->authUser->preference)) {
                return redirect()->route('app.preference');
            }

            return redirect()->route('app.developers');

        } catch (\Exception $e) {
                DB::rollback();

        }
       
    }
}
