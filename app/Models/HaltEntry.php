<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaltEntry extends Model
{
    protected $fillable = ['hungry', 'angry', 'lonely', 'tired'];
}
