<?php

namespace App\Http\Controllers;


class ApiController extends Controller 
{

	protected $statusCode = 200;



	public function getStatusCode() 
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode) 
	{
		$this->statusCode = $statusCode;

		return $this;
	}



	/*
	*
	* Set status codes and default messages
	*
	*/
	public function respondNotFound($message = 'Not Found!') 
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondInternalError($message = 'Internal Error') 
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

	public function respondCreated($message = 'Successfully created') 
	{
		return $this->setStatusCode(201)->respondWithSuccess($message);
	}
	public function respondUpdated($message = 'Successfully updated') 
	{
		return $this->setStatusCode(200)->respondWithSuccess($message);
	}
	public function respondDeleted($message = 'Successfully deleted') 
	{
		return $this->setStatusCode(200)->respondWithSuccess($message);
	}



	/*
	*
	* Responders
	*
	*/

	// basic
	public function respond($data, $headers = [])
	{
		return response()->json($data, $this->getStatusCode(), $headers);
	}

	// error
	public function respondWithError($message) 
	{
		return $this->respond([
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	// success
	public function respondWithSuccess($message) 
	{
		return $this->respond([
			'status' => 'success',
			'message' => $message,
			'status_code' => $this->getStatusCode()
		]);
	}


}