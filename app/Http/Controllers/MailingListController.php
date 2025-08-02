<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use Illuminate\Http\Request;

class MailingListController extends Controller
{


    public function store(Request $request)
    {
        MailingList::create($request->toArray());

        return "Gracias por suscribirte";
    }

}
