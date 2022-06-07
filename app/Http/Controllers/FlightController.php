<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Flight;

use Auth;
use Str;
use Validator;
use Crypt;

class FlightController extends BaseController
{
    protected function convertIata($id) {
        switch($id) {
            case 'KZN':
                return 1;
            case 'SVO':
                return 2;
            case 'LED':
                return 3;
            case 'AER':
                return 4;
            default:
                return 'Invalid';
        }
    }

    public function search(Request $request) {
        $input = $request->all();
        $input['from'] = $this->convertIata($input['from']);
        $input['to'] = $this->convertIata($input['to']);

        $validator = Validator::make($input, [
            'from' => 'required|numeric|min:1|max:4',
            'to' => 'required|numeric|min:1|max:4',
            'date1' => 'required|date',
            'date2' => 'required|date',
            'passengers' => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors(), 400);       
        }

        $flights = Flight::where('from_id', '=', $input['from'])
            ->where('to_id', '=', $input['to'])
                ->orWhere('from_id', '=', $input['to'])
                ->where('to_id', '=', $input['from'])
            ->with('from')
            ->with('to')
            ->get();

        $from = [];
        $back = [];

        foreach($flights as &$flight) {
            if($flight['from_id'] == $input['from'])
                array_push($from, $flight);
            else if($flight['from_id'] == $input['to'])
                array_push($back, $flight);
        }

        return $this->sendResponse([
            'flights_to' => $from,
            'flights_back' => $back
        ], 200);
    }
}