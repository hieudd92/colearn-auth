<?php

return [
	'rpc' => [
		'queue' => env('RPC_AUTH_QUEUE', 'rpc_auth_queue'),
		'url' => env('RPC_AUTH_URL', 'url'),
		'method' => env('RPC_AUTH_METHOD', 'POST')
	]
];