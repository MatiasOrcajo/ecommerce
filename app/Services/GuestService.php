<?php

namespace App\Services;

use App\Models\GuestModel;
use Illuminate\Http\Request;

class GuestService{

    /**
     * Store the IP address of the current request into the GuestModel.
     *
     * @param Request $request The incoming HTTP request instance.
     * @return void
     */
    public function store(Request $request): void
    {
        GuestModel::create([$request->ip()]);
    }


}
