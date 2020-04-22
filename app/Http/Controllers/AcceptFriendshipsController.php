<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Friendship;
use App\User;

class AcceptFriendshipsController extends Controller
{
    public function index(Request $request)
    {
        return view('friendships.index', [
            'friendshipRequests' => $request->user()->friendshipRequestsReceived
        ]);
    }

    public function store(Request $request, User $sender)
    {
        $request->user()->acceptFriendshipRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'accepted'
        ]);
    }

    public function destroy(Request $request, User $sender)
    {
        $request->user()->denyFriendshipRequestFrom($sender);    

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }
}
