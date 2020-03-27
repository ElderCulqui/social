<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\StatusResource;
use App\User;

class UsersStatusController extends Controller
{
    public function index(User $user)
    {
        return StatusResource::collection(
            $user->statuses()->latest()->paginate()
        );
    }
}
