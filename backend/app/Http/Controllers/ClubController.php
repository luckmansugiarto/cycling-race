<?php
namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function createNew(Request $request)
    {
        $club = Club::create($request->all());

        return response()->json($club, 201);
    }

    public function getAll()
    {
        return response()->json(Club::all());
    }

    public function update($id, Request $request) {
        $club = Club::findOrFail($id);
        $club->update($request->all());

        return response()->json($club, 200);
    }
}
