<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Passenger;

use Auth;
use Str;
use Validator;
use Crypt;

class BookingController extends BaseController
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'flight_from' => 'required',
            'flight_from.id' => 'required|numeric',
            'flight_from.date' => 'required|date',
            'flight_back' => 'required',
            'flight_back.id' => 'required|numeric',
            'flight_back.date' => 'required|date',
            'passengers' => 'required',
            'passengers.*.first_name' => 'required|string',
            'passengers.*.last_name' => 'required|string',
            'passengers.*.birth_date' => 'required|date',
            'passengers.*.document_number' => 'required|string|min:10|max:10',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors(), 400);       
        }

		$input = $request->all();

		// Create booking
		$bookingCode = Str::upper(Str::random(5));
		$booking = Booking::create([
			'flight_from' => $input['flight_from']['id'],
			'flight_back' => $input['flight_back']['id'],
			'date_from' => $input['flight_from']['date'],
			'date_back' => $input['flight_back']['date'],
			'code' => $bookingCode
		]);

		// Create passengers (seats)
		foreach($input['passengers'] as &$passenger) {
			Passenger::create([
				'booking_id' => $booking['id'],
				'first_name' => $passenger['first_name'],
				'last_name' => $passenger['last_name'],
				'birth_date' => $passenger['birth_date'],
				'document_number' => $passenger['document_number'],
				'place_from' => null,
				'place_back' => null
			]);
		}
		
        return $this->sendResponse([
			'code' => $bookingCode
        ], 200);
    }

	public function get(Request $request, $code) {
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|string|min:5|max:5'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors(), 400);       
        }

		$booking = Booking::where('code', '=', $code)
			->with('from')
			->with('back')
			->with('passengers')
			->first();

		if($booking !== null)
			return $this->sendResponse([
				'code' => $code,
				'cost' => 40000,
				'flights' => [
					$booking['from'],
					$booking['back']
				],
				'passengers' => $booking['passengers']
			], 200);
		else
			return $this->sendError('Booking not found', null, 404);
	}
}