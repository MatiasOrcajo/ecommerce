<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestModel extends Model
{
    use hasFactory;

    protected $table = 'guests';
    protected $guarded;
}
