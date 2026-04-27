<?php
namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * Used to update an ongoing payment, if possible. Only fields that are to be updated are to be included.
 *
 * @method float  getAmount()        The new amount for the payment.
 */
class PaymentUpdateRequest extends DataObject {

	/**
	 * The new amount for the payment.
	 */
	public function setAmount(float $amount): self {
		$this->data['amount'] = $amount;

		return $this;
	}

	public function setPaymentStatus(string $paymentStatus): self {
		error_log('Deprecated method `Zaver\SDK\Object\PaymentUpdateRequest::setPaymentStatus` called. Use `Zaver\SDK\Checkout::cancelPayment(string $paymentId)` instead to cancel a payment. Deprecated since version 2.0.0');
		return $this;
	}
}
