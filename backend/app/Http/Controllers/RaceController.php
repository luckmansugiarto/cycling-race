<?php
namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\Rider;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function addParticipant($id, Request $request) {
        $race = Race::findOrFail($id);

        $rider = Rider::UpdateOrCreate(
            [
                'firstname' => $request->post('firstname'),
                'surname' => $request->post('surname')
            ], 
            $request->all()
        );

        return response()->json($rider, 201);
    }

    public function createNew(Request $request)
    {
        $race = Race::create($request->all());

        return response()->json($race, 201);
    }

    public function getAll()
    {        
        return response()->json(Race::with(['club', 'riders'])->get());
    }

    public function saveResult($id, $riderId, Request $request) {
        $race = Race::findOrFail($id);        

        $rider = Rider::findOrFail($riderId);
        $statusCode = 201;

        if ($rider->races->contains($id)) {
            $rider->races()->updateExistingPivot($id, $request->all());
            $statusCode = 200;
        } else {
            $rider->races()->attach($id, $request->all());
        }

        $rider->refresh();            

        return response()->json([], $statusCode);
    }

    public function update($id, Request $request)
    {
        $race = Race::findOrFail($id);
        $race->update($request->all());

        return response()->json($race, 200);
    }
}
