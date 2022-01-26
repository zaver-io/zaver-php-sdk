<?php
namespace Zaver\SDK;
use DateTime;
use Zaver\SDK\Exceptions\Error;

/**
 * Contains the current status of the payment. Returned by way of callback, or by calling the `GET` endpoint.
 */
class PaymentStatusResponse extends Utils\DataObject {

	/**
	 * 	The token used to start the in-page checkout
	 */
	public function getToken(): string {
		return $this->data['token'] ?? '';
	}

	/**
	 * Expiry time of the payment.
	 */
	public function getValidUntil(): ?DateTime {
		return (isset($this->data['validUntil']) ? new DateTime() : null);
	}

	/**
	 * The ID of the payment.
	 */
	public function getPaymentId(): string {
		return $this->data['paymentId'] ?? '';
	}

	/**
	 * Reference set by merchant, e.g. order reference.
	 */
	public function getMerchantPaymentReference(): string {
		return $this->data['merchantPaymentReference'] ?? '';
	}

	/**
	 * The payment amount in the format 100 or the format 100.00.
	 */
	public function getAmount(): float {
		return (float)($this->data['amount'] ?? 0);
	}

	/**
	 * The status of the payment. Possible statuses are `CREATED`, `SETTLED`, `CANCELLED` and `ERROR`.
	 */
	public function getPaymentStatus(): string {
		return $this->data['paymentStatus'] ?? '';
	}

	/**
	 * An associative array of merchant-defined key-value pairs. These are set at payment creation.
	 */
	public function getMerchantMetadata(): array {
		return $this->data['merchantMetadata'] ?? [];
	}

	/**
	 * Contains customization options. All values are strings.
	 */
	public function getMerchantCustomizations(): array {
		return $this->data['merchantCustomizations'] ?? [];
	}

	public function isTest(): bool {
		return !empty($this->data['test']);
	}

	public function getHtmlSnippet(array $attributes = []): string {
		if($this->getPaymentStatus() !== Config\PaymentStatus::CREATED) {
			throw new Error('Payment is not in CREATED state');
		}

		return Utils\Html::getTag('script', false, [
			'src' => ($this->isTest() ? Config\Endpoint::TEST_SCRIPT : Config\Endpoint::PRODUCTION_SCRIPT),
			'id' => 'zco-loader',
			'zco-token' => $this->getToken(),
			...$attributes
		]);
	}
}