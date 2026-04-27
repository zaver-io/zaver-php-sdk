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
