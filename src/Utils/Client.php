<?php
namespace Zaver\SDK\Utils;
use Exception;
use GuzzleHttp;
use GuzzleHttp\Exception\BadResponseException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

class Client {
	const GENERIC_ERROR = 'An error occurred while communicating with the Zaver API';

	private $client = null;

	/**
	 * @param string $endpoint Endpoint for all requests
	 * @param string $apiKey Bearer token
	 * @param int $timeout Request timeout in seconds. Default 15 seconds
	 * @param UserAgent|null $userAgent Sent as User-Agent header. Without one, only the SDK and PHP versions are sent
	 */
	public function __construct(string $endpoint, string $apiKey, int $timeout = 15, ?UserAgent $userAgent = null) {
		$this->client = new GuzzleHttp\Client([
			'base_uri' => $endpoint,
			'timeout' => $timeout,
			'headers' => [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'Authorization' => sprintf('Bearer %s', $apiKey),
				'User-Agent' => (string)($userAgent ?? UserAgent::create())
			]
		]);
	}

	/**
	 * @param string $uri URI of the request
	 */
	public function get(string $uri): array {
		return $this->request('GET', $uri);
	}

	/**
	 * @param string $uri URI of the request
	 * @param array|JsonSerializable $body Body of the request
	 */
	public function post(string $uri, $body): array {
		return $this->request('POST', $uri, $body);
	}

	/**
	 * @param string $uri URI of the request
	 * @param array|JsonSerializable $body Body of the request
	 */
	public function patch(string $uri, $body): array {
		return $this->request('PATCH', $uri, $body);
	}

	/**
	 * @param string $uri URI of the request
	 */
	public function delete(string $uri): array {
		return $this->request('DELETE', $uri);
	}

	/**
	 * @param string $method HTTP method
	 * @param string $uri URI where the request will be sent
	 * @param array|JsonSerializable $body Body of the request
	 */
	private function request(string $method, string $uri, $body = null): array {
		try {
			$options = [];
			if(!is_null($body)) {
				$options['json'] = $body;
			}

			return self::unwrap($this->client->request($method, $uri, $options));
		}
		catch(Error $e) {
			$e->setRequestBody($body instanceof JsonSerializable ? $body : null);
			throw $e;
		}
		catch(BadResponseException $e) {
			// Any 4xx or 5xx response. Zaver reports errors as a list in the body; keep the HTTP status
			// and response headers for logging, whether or not that list is present.
			$response = $e->getResponse();
			$status = $response->getStatusCode();
			$responseBody = self::tryUnwrap($response);

			if(!empty($responseBody['errors']) && is_array($responseBody['errors'])) {
				$error = new Error($responseBody['errors'], null, null, $body, $responseBody, $e);
			}
			else {
				$error = new Error(sprintf('%s (HTTP %d)', self::GENERIC_ERROR, $status), null, null, $body, $responseBody, $e);
			}

			$error->setHttpStatus($status);
			$error->setResponseHeaders(self::headers($response));

			throw $error;
		}
		catch(Exception $e) {
			throw new Error(self::GENERIC_ERROR, null, null, $body, null, $e);
		}
	}

	static private function unwrap(ResponseInterface $response): array {
		try {
			return json_decode($response->getBody(), true, 10, JSON_THROW_ON_ERROR);
		}
		catch(Exception $e) {
			throw new Error('Failed to decode Zaver response', null, null, null, null, $e);
		}
	}

	static private function tryUnwrap(ResponseInterface $response): ?array {
		try {
			$data = json_decode((string)$response->getBody(), true, 10, JSON_THROW_ON_ERROR);

			return is_array($data) ? $data : null;
		}
		catch(Exception $e) {
			return null;
		}
	}

	/** @return array<string, string> Lower-cased header name => value */
	static private function headers(ResponseInterface $response): array {
		$headers = [];

		foreach($response->getHeaders() as $name => $values) {
			$headers[strtolower($name)] = implode(', ', $values);
		}

		return $headers;
	}
}
