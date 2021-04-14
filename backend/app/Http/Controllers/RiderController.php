<?php
namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function createNew(Request $request)
    {
        $rider = Rider::create($request->all());

        return response()->json($rider, 201);
    }

    public function getAll()
    {
        return response()->json(Rider::all());
    }
}
