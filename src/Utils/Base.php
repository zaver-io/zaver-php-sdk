<?php
namespace Zaver\SDK\Utils;
use Zaver\SDK\Config\Endpoint;

abstract class Base {

	/** @var Client $client */
	protected $client = null;
	protected $test = false;
	protected $callbackToken = null;

	/**
	 * @param string $apiKey Bearer token
	 * @param bool $test Use the Zaver test environment
	 * @param string|null $callbackToken Token Zaver sends in the Authorization header of callbacks
	 * @param int $timeout Request timeout in seconds. Default 15 seconds
	 * @param UserAgent|null $userAgent Identifies your platform and plugin towards Zaver (optional, see `UserAgent`)
	 */
	public function __construct(string $apiKey, bool $test = false, ?string $callbackToken = null, int $timeout = 15, ?UserAgent $userAgent = null) {
		$this->client = new Client(($test ? Endpoint::TEST : Endpoint::PRODUCTION), $apiKey, $timeout, $userAgent);
		$this->test = $test;
		$this->callbackToken = $callbackToken;
	}

	public function isTest(): bool {
		return $this->test;
	}

	public function getCallbackToken() {
		return $this->callbackToken;
	}
}
