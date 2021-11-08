<?php

namespace CoLearn\Auth\Services;

use CoLearn\RabbitMQ\RabbitMQ;

class RpcService
{
	private $rabbit;
	
	public function __construct()
	{
		$this->rabbit = app('rabbitmq.queue')->connection('rabbitmq');
	}

	public function retrieveUserByToken($token, $config)
	{
		try {
			$user = null;
			$request = [
	            'requestMethod' => $config['colearn_auth.rpc.method'],
	            'requestPath' => $config['colearn_auth.rpc.url'],
	            'urlParam' => '',
	            'pathParam' => '',
	            'headerParam' => [
	                'authorization' => 'Bearer ' . $token
	            ]
	        ];
	        $response = RabbitMQ::declareRPCClient($this->rabbit, $config['colearn_auth.rpc.queue'], json_encode($request));
	        if ($response && isset($response['data']['status']) && $response['data']['status'] === 200){
				$user = $response['data']['data'];
			}

			return $user;
		} catch (\Exception $e) {
			return null;
		}
	}
}