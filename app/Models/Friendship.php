<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Friendship extends Model
{
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
    
    public function recipient()
    {
        return $this->belongsTo(User::class);
    }
}
