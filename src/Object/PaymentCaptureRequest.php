<?php
namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * The Payment Capture Request contains the necessary information to capture a payment.
 *
 * @method float          getAmount()                   The Payment amount in the format 100 or the format 100.00.
 * @method string         getCurrency()                 The ISO currency code of the Payment. Currently, only "SEK" is supported.
 * @method array          getMerchantMetadata()         List of lineItems on the payment request to capture. Deprecated since 2.0.0
 * @method array          getCaptureMetadata()          List of lineItems on the payment request to capture.
 * @method array          getLineItems() 		        An associative array of merchant-defined key-value pairs.
 * @method string         getCaptureIdempotencyKey()    The capture idempotency key.
 */
class PaymentCaptureRequest extends DataObject {

	/**
	 * Required. The Payment amount in the format 100 or the format 100.00.
	 */
	public function setAmount(float $amount): self {
		$this->data['amount'] = $amount;

		return $this;
	}

	/**
	 * Required. The ISO currency code of the Payment. Currently, only "SEK" is supported.
	 */
	public function setCurrency(string $currency): self {
		$this->data['currency'] = $currency;

		return $this;
	}

	/**
	 * Add a LineItem to capture.
	 */
	public function addLineItem(LineItem $lineItem): self {
		if(!isset($this->data['lineItems'])) {
			$this->data['lineItems'] = [];
		}

		$this->data['lineItems'][] = $lineItem;

		return $this;
	}

	/**
	 * An associative array of merchant-defined key-value pairs. These are returned with the Payment Status Response.
	 * A Maximum of 20 pairs is allowed, each key and value with a maximum length of 200 characters.
	 *
	 * @deprecated Use `captureMetadata` instead, deprecated since 2.0.0
	 */
	public function setMerchantMetadata(array $merchantMetadata): self {
		error_log('Deprecated method `Zaver\SDK\Object\PaymentCaptureRequest::setMerchantMetadata` called. Use `Zaver\SDK\Object\PaymentCaptureRequest::setCaptureMetadata` instead. Deprecated since version 2.0.0');
		return $this->setCaptureMetadata($merchantMetadata);
	}

	/**
	 * An associative array of merchant-defined key-value pairs. These are returned with the Payment Status Response.
	 * A Maximum of 20 pairs is allowed, each key and value with a maximum length of 200 characters.
	 */
	public function setCaptureMetadata(array $captureMetadata): self {
		$this->data['captureMetadata'] = $captureMetadata;

		return $this;
	}

	/**
	 * The capture idempotency key.
	 */
	public function setCaptureIdempotencyKey(string $captureIdempotencyKey): self {
		$this->data['captureIdempotencyKey'] = $captureIdempotencyKey;

		return $this;
	}
}
