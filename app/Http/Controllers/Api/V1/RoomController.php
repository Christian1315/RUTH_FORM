<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    #GET A ROOM
    function RetrieveRoom(Request $request, $id)
    {
       $room = Room::find($id);
       return response()->json($room);
    }

}
