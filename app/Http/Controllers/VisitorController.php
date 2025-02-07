<?php

namespace App\Http\Controllers;

use App\Services\GuestService;
use Illuminate\Http\Request;

class VisitorController extends Controller
{

    public function __construct(private readonly GuestService $guestService)
    {
    }

    public function getIpAddress(Request $request)
    {
        return response()->json($this->guestService->getIpAddress($request));
    }

}
