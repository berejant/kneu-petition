<?php

namespace Kneu\Petition\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kneu\Petition\Http\Controllers\Controller;
use Kneu\Petition\User;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as KneuUser;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return \SocialiteProviders\Kneu\Provider
     */
    protected function getProvider()
    {
        return Socialite::with('kneu');
    }

    public function login(Request $request)
    {
        $request->session()->put('url.intended', url()->previous());

        return $this->getProvider()->redirect();
    }

    public function loginComplete(Request $request)
    {
        /** @var KneuUser $kneuUser */
        $kneuUser = $this->getProvider()->user();

        /** @var User $user */
        $user = User::findOrNew($kneuUser->id);
        $user->fill($kneuUser->getRaw());
        $user->saveOrFail();

        Auth::login($user);

        $request->session()->put('userName', $user->getName());

        return redirect()->intended($this->redirectTo);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return $this->getProvider()->logoutRedirect(url()->previous());
    }

}
