<?php
namespace Zaver\SDK\Utils;
use Exception;
use JsonSerializable;
use Throwable;

class Error extends Exception {
	protected $errorCode = null;
	protected $docs = null;
	protected $requestBody = null;
	protected $responseBody = null;
	protected $errors = [];
	protected $httpStatus = null;
	protected $responseHeaders = [];

	/**
	 * @param string|array $errorMessage
	 * @param string|null $errorCode
	 * @param string|null $docs
	 * @param JsonSerializable|array|null $requestBody
	 * @param array|null $responseBody
	 * @param Throwable|null $previous
	 */
	public function __construct($errorMessage, ?string $errorCode = null, ?string $docs = null, $requestBody = null, ?array $responseBody = null, ?Throwable $previous = null) {
		if(is_array($errorMessage) && !empty($errorMessage)) {
			$error = $errorMessage[0];

			parent::__construct($error['errorMessage'] ?? '', 0, $previous);

			if(isset($error['errorCode'])) $this->errorCode = $error['errorCode'] ?? null;
			if(isset($error['docs'])) $this->docs = $error['docs'];

			$this->errors = $errorMessage;
			$this->requestBody = $requestBody;
			$this->responseBody = $responseBody;
		}
		elseif(is_string($errorMessage)) {
			parent::__construct($errorMessage, 0, $previous);

			$this->errorCode = $errorCode;
			$this->docs = $docs;
			$this->requestBody = $requestBody;
			$this->responseBody = $responseBody;
			$this->errors = [
				[
					'errorCode' => $errorCode,
					'errorMessage' => $errorMessage,
					'docs' => $docs
				]
			];
		}
		else {
			throw new Error('Invalid error message');
		}
	}

	/**
	 * Set the request body for the error.
	 * @param array|JsonSerializable|null $requestBody
	 * @return void
	 */
	public function setRequestBody(?JsonSerializable $requestBody): void {
		$this->requestBody = $requestBody;
	}

	/**
	 * Set the response body for the error.
	 * @param array $responseBody
	 * @return void
	 */
	public function setResponseBody(?array $responseBody): void {
		$this->responseBody = $responseBody;
	}

	/**
	 * HTTP status of the Zaver API response, or null if the error happened before a response was received.
	 */
	public function getHttpStatus(): ?int {
		return $this->httpStatus;
	}

	public function setHttpStatus(?int $httpStatus): void {
		$this->httpStatus = $httpStatus;
	}

	/**
	 * Headers of the Zaver API response, lower-cased names. Useful when reporting a problem to Zaver:
	 * the API currently returns no request id of its own, but CloudFront's `x-amz-cf-id` identifies the request.
	 * @return array<string, string>
	 */
	public function getResponseHeaders(): array {
		return $this->responseHeaders;
	}

	/** @param array<string, string> $responseHeaders */
	public function setResponseHeaders(array $responseHeaders): void {
		$this->responseHeaders = $responseHeaders;
	}

	/**
	 * Zaver's error code (e.g. `INVALID_AMOUNT`), if the API reported one.
	 */
	public function getErrorCode(): ?string {
		return $this->errorCode;
	}

	/**
	 * A link to relevant documentation.
	 */
	public function getDocs(): ?string {
		return $this->docs;
	}

	public function getErrorMessage(): string {
		return $this->getMessage();
	}

	public function getRequestBody(): ?JsonSerializable {
		return $this->requestBody;
	}

	public function getResponseBody(): ?array {
		return $this->responseBody;
	}
}
