<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\StatusResource;
use App\Events\StatusCreated;
use App\Models\Status;

class StatusesController extends Controller
{
    public function index()
    {
        return StatusResource::collection(
            Status::latest()->paginate()
        );
    }

    public function store(Request $request)
    {
        $validStatus = $request->validate(['body' => 'required|min:5']);

        $status = $request->user()->statuses()->create($validStatus);

        $statusResource = StatusResource::make($status);

        StatusCreated::dispatch($statusResource);

        return $statusResource;
    }   

}
