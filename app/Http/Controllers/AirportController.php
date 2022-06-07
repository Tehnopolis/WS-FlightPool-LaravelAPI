<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Airport;

use Auth;
use Str;
use Validator;
use Crypt;

class AirportController extends BaseController
{
    public function search(Request $request) {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors(), 400);       
        }

        $airports = Airport::where('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('iata', 'LIKE', '%' . $request->search . '%')
            ->get(['name','iata']);

        return $this->sendResponse([
            'items' => $airports
        ], 200);
    }
}