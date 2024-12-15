<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{


    /**
     * Esta función va a crear un usuario "ficticio"
     *  Se creará en el momento en el cual el usuario hace el checkout
     *  El usuario nunca se entera de este proceso
     *  Se crea con los datos que el usuario ingresó en el checkout
     *
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $user = User::where('email', $data["email"])->first();

        if ($user == null) {

            $user               = new User();
            $user->name         = $data["name"];
            $user->email        = $data["email"];
            $user->phone        = $data["phone"];
            $user->dni_or_cuit  = $data["dni_or_cuit"];
            $user->role         = 'user';
            $user->password     = Hash::make(config('user.default_password'));
            $user->save();
        }

        return $user;
    }

}
