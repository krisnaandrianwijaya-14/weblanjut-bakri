<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $url = match ($user->role) {
            'platform_admin'  => '/platform',
            'admin_pesantren' => '/client/'.$user->defaultClientSlug().'/admin/dashboard',
            'guru'            => '/client/'.$user->defaultClientSlug().'/guru/dashboard',
            'wali_asrama'     => '/client/'.$user->defaultClientSlug().'/asrama/dashboard',
            'wali_santri'     => '/client/'.$user->defaultClientSlug().'/wali/dashboard',
            'santri'          => '/client/'.$user->defaultClientSlug().'/santri/dashboard',
            default           => '/',
        };

        return redirect()->intended($url);
    }
}
