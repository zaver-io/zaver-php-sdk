<?php
namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * Authorization Status for `AllowedPaymentOperations`
 */
class AllowedPaymentOperations extends DataObject {
	/**
	 * If the payment can be captured.
	 * @return bool
	 */
	public function getCanCapture(): bool {
		return $this->data['canCapture'] ?? false;
	}

	/**
	 * If the payment can be refunded.
	 * @return bool
	 */
	public function getCanRefund(): bool {
		return $this->data['canRefund'] ?? false;
	}

	/**
	 * If the payment can be cancelled.
	 * @return bool
	 */
	public function getCanCancel(): bool {
		return $this->data['canCancel'] ?? false;
	}
}
