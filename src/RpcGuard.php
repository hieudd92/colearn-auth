<?php

namespace CoLearn\Auth;

use CoLearn\RabbitMQ\RabbitMQ;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Rpc custom guard
 */
class RpcGuard implements Guard
{
	use GuardHelpers;

	private $request;
	private $config;
	private $rabbit;

	public function __construct(Request $request, $config)
	{
		$this->request = $request;
		$this->config = $config;
		$this->rabbit = app('rabbitmq.queue')->connection('rabbitmq');
	}

	public function user () {
		if (!is_null($this->user)) {
			return $this->user;
		}

		$user = null;

		// retrieve via token
		$token = $this->getTokenForRequest();

		if (!empty($token) && !empty($this->config['colearn_auth.rpc'])) {
			$request = [
	            'requestMethod' => $this->config['colearn_auth.rpc.method'],
	            'requestPath' => $this->config['colearn_auth.rpc.url'],
	            'urlParam' => '',
	            'pathParam' => '',
	            'headerParam' => [
	                'authorization' => 'Bearer ' . $token
	            ]
	        ];
	        $response = RabbitMQ::declareRPCClient($this->rabbit, $this->config['colearn_auth.rpc.queue'], json_encode($request));
	        if ($response && isset($response['data']['status']) && $response['data']['status'] === 200){
				$user = $response['data']['data'];
			}
			
		}

		return $this->user = $user;
	}

	/**
	 * Get the token for the current request.
	 * @return string
	 */
	public function getTokenForRequest () {
		$token = $this->request->bearerToken();

		return $token;
	}

	/**
	 * Validate a user's credentials.
	 *
	 * @param  array $credentials
	 *
	 * @return bool
	 */
	public function validate (array $credentials = []) {
		return true;
	}
}