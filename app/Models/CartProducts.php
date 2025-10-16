<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Exception;

class CartProducts extends Model
{
    use HasFactory;

    protected $table = 'cart_products';
    protected $guarded;





}
