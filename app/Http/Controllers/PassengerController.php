<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule as Rule;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Passenger;

use Auth;
use Str;
use Validator;
use Crypt;

class PassengerController extends BaseController
{
    public function change(Request $request) {
		$input = $request->all();
        $validator = Validator::make($input, [
            'passenger' => 'required|numeric',
            'seat' => 'required|string|min:2|max:2|unique:passengers,place_from,place_back',
            'type' => 'in:from,back',
        ]);

        if($validator->fails()){
			if($validator->failed()['seat']['Unique'])
			return $this->sendError('Seat is occupied', null, 400);
			else
            	return $this->sendError('Validation error', $validator->errors(), 400);       
        }

		$update = [];
		$update['place_' . $input['type']] = $input['seat'];

		// Change seat
		$passenger = tap(Passenger::where('id', $input['passenger']))
			->update($update)
			->first(['id','first_name','last_name','birth_date','document_number','place_from','place_back']);
		
		if($passenger !== null)
        	return $this->sendResponse($passenger, 200);
		else
        	return $this->sendError('Passenger does not apply to booking', null, 404);
    }

	public function get(Request $request, $code) {
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|string|min:5|max:5'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors(), 400);       
        }

		$booking = Booking::where('code', '=', $code)
			->with('passengers')
			->first();

		if($booking !== null) {
			$from = [];
			$back = [];
			foreach($booking['passengers'] as &$passenger) {
				// From
				if(isset($passenger['place_from'])) {
					array_push($from, [
						'passenger_id' => $passenger['id'],
						'place' => $passenger['place_from']
					]);
				}
				// Back
				else if($passenger['place_back'] !== null) {
					array_push($back, [
						'passenger_id' => $passenger['id'],
						'place' => $passenger['place_back']
					]);
				}
			}

			return $this->sendResponse([
				'occupied_from' => $from,
				'occupied_back' => $back
			], 200);
		}
		else
			return $this->sendError('Booking not found', null, 404);
	}
}