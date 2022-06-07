<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use App\Models\Flight;

use Auth;
use Str;
use Validator;
use Crypt;

class UserController extends BaseController
{
	private $encrypt_key = 'flightpool';

	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'first_name' => 'required|string',
			'last_name' => 'required|string',
			'phone' => 'required',
			'password' => 'required',
			'document_number' => 'required|string|min:10|max:10',
		]);

		if($validator->fails()){
			return $this->sendError('Validation error', $validator->errors(), 400);       
		}

		$user = User::create($request->all());

		return $this->sendResponse(null, 200);
	}

	public function login(Request $request)
	{
		// Validate request
		$validator = Validator::make($request->all(), [
			'phone' => 'required',
			'password' => 'required|string',
		]);
		if($validator->fails()){
			return $this->sendError('Validation error', $validator->errors(), 400);       
		}

		// Authorize
		if($this->attemptLogin($request->phone, $request->password)){ 
			$success = [];
			$success['token'] = Crypt::encryptString($request->phone);

			return $this->sendResponse($success, 200);
		} 
		else { 
			return $this->sendError('Unauthorized', ['phone'=>['phone or password incorrect']], 301);
		} 
	}

	public function bookings(Request $request) {
		try {
			// Get payload after 'Bearer '
			$payload = Str::after($request->header('Authorization'), 'Bearer ');
			
			// Decrypt payload to phone
			$phone = Crypt::decryptString($payload);

			// Функционал не сделан так как в БД
			// нету никаких связей чтоб определить
			// бронь от конкретного User (user_id нигде не указан)

			// Send raw data
			return $this->sendResponse(array(
				'flights_to' => 
				array (
					0 => 
					array (
						'flight_id' => 2,
						'flight_code' => 'FP1100',
						'from' => 
						array (
							'city' => 'Kazan',
							'airport' => 'Kazan',
							'iata' => 'KZN',
							'date' => '2020-10-01',
							'time' => '12:00',
						),
						'to' => 
						array (
							'city' => 'Moscow',
							'airport' => 'Sheremetyevo',
							'iata' => 'SVO',
							'date' => '2020-10-01',
							'time' => '13:35',
						),
						'cost' => 9500,
						'availability' => 156,
					),
					1 => 
					array (
						'flight_id' => 14,
						'flight_code' => 'FP 1205',
						'from' => 
						array (
							'city' => 'Kazan',
							'airport' => 'Kazan',
							'iata' => 'KZN',
							'date' => '2020-10-01',
							'time' => '08:35',
						),
						'to' => 
						array (
							'city' => 'Moscow',
							'airport' => 'Sheremetyevo',
							'iata' => 'SVO',
							'date' => '2020-10-01',
							'time' => '10:05',
						),
						'cost' => 10500,
						'availability' => 156,
					),
				),
				'flights_back' => 
				array (
					0 => 
					array (
						'flight_id' => 1,
						'flight_code' => 'FP 2100',
						'from' => 
						array (
							'city' => 'Moscow',
							'airport' => 'Sheremetyevo',
							'iata' => 'SVO',
							'date' => '2020-10-10',
							'time' => '08:35',
						),
						'to' => 
						array (
							'city' => 'Kazan',
							'airport' => 'Kazan',
							'iata' => 'KZN',
							'date' => '2020-10-10',
							'time' => '10:05',
						),
						'cost' => 10500,
						'availability' => 156,
					),
					1 => 
					array (
						'flight_id' => 13,
						'flight_code' => 'FP 2101',
						'from' => 
						array (
							'city' => 'Moscow',
							'airport' => 'Sheremetyevo',
							'iata' => 'SVO',
							'date' => '2020-10-10',
							'time' => '12:00',
						),
						'to' => 
						array (
							'city' => 'Kazan',
							'airport' => 'Kazan',
							'iata' => 'KZN',
							'date' => '2020-10-10',
							'time' => '13:35',
						),
						'cost' => 12500,
						'availability' => 156,
					),
				),
			), 200);
		}
		catch (\Exception $e) {
			return $this->sendError('Failed to get bookings', null, 500);
		}
	}

	public function profile(Request $request) {
		try {
			// Get payload after 'Bearer '
			$payload = Str::after($request->header('Authorization'), 'Bearer ');
			
			// Decrypt payload to phone
			$phone = Crypt::decryptString($payload);

			// Get user
			$user = User::where('phone', $phone)
					->first(['first_name', 'last_name', 'phone', 'document_number']);

			return $this->sendResponse($user, 200);
		}
		catch (\Exception $e) {
			return $this->sendError('Failed to get profile', null, 500);
		}
	}

	protected function attemptLogin(string $phone, string $password) {
		$user = User::where('phone', $phone)
			->where('password', $password)
			->first();

		if($user) {
			return true;
		}
		else {
			return false;
		}
	}
}