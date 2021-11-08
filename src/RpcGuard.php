<?php

namespace CoLearn\Auth;

use CoLearn\Auth\Services\RpcService;
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

	public function __construct(Request $request, $config)
	{
		$this->request = $request;
		$this->config = $config;
	}

	public function user () {
		if (!is_null($this->user)) {
			return $this->user;
		}

		$user = null;

		// retrieve via token
		$token = $this->getTokenForRequest();

		if (!empty($token) && !empty($this->config['colearn_auth.rpc'])) {
			$user = (new RpcService)->retrieveUserByToken($token, $this->config);
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