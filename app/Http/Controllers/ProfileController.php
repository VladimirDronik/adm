<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateRequest $r)
    {
        $user = user();

        $user->login = trim($r->login);

        if (!is_null($r->password)) {
            $user->password = Hash::make($r->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success','Данные успешно изменены');
    }
}