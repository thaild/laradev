<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\Auth;

class Impersonate
{
    public function impersonate($id)
    {
        $user = User::find($id);

        // Guard against administrator impersonate
        if(! $user->isAdministrator())
        {
            Auth::user()->setImpersonating($user->id);
        }
        else
        {
            flash()->error('Impersonate disabled for this user.');
        }

        return redirect()->back();
    }

    public function stopImpersonate()
    {
        Auth::user()->stopImpersonating();

        flash()->success('Welcome back!');

        return redirect()->back();
    }
}
