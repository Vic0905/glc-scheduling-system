<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // get all the rooms 
        $roomName = $request->query('room_name'); // Fix the query parameter name

          // Query the subjects table, using the correct column name 'subjectname'
          $rooms = Room::query()
          ->when($roomName, function ($query) use ($roomName) {
              $query->where('roomname', 'like', '%' . $roomName . '%');
          })
          ->orderBy('roomname', 'asc') // Sort alphabetically
          ->paginate(10); // Display 5 rooms per page

          $roomCount = $rooms->count(); // Count the number of rooms
    

        return view('rooms.index', compact('rooms', 'roomName', 'roomCount'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        // Create the subject
        $room = Room::create($request->all());

        // Log the activity
        ActivityLog::create([
            'activity' => 'Room ' . $room->name . ' created',
            'model_type' => 'Room',
            'model_id' => $room->id,
        ]);

        // Redirect to the subjects list with success message
        return redirect()->route('rooms.index')->with('success', 'Room added successfully.');
    }

    public function edit($id)
    {
        // find the subject by the id passed in the URL 
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        // find the subject by the id passed in the URL
        $room = Room::findOrFail($id);
        $room->update($request->all());

        // Log the activity for update
        ActivityLog::create([
            'activity' => 'Room ' . $room->name . ' updated',
            'model_type' => 'Room',
            'model_id' => $room->id,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }
    public function destroy(Room $room) 
    {
 
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
    
    
}
