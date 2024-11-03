<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null)
    {
        $pageSeo = Page::where('slug', 'reset_password')->first();
        $pageSeo->meta_keywords =isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords?implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);

        return view(template().'auth.passwords.reset',compact('pageSeo'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

}
