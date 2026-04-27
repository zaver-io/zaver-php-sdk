<?php
namespace Zaver\SDK\Object;

use DateTime;
use DateTimeInterface;
use Zaver\SDK\Utils\DataObject;

/**
 * The payer specific data that is used for the `SendLinkTo` object
 *
 * @method string getEmail				The email of the payer in question.
 * @method string getPhoneNumber		The phone number of the payer in question. Supplied with the country code followed by digits. Swedish example: country code +46, phone number 701740605 = +46701740605
 */
class SendLinkTo extends DataObject {

	/**
	 * The email of the payer in question.
	 */
	public function setEmail(string $email): self {
		$this->data['email'] = $email;

		return $this;
	}

	/**
	 * The phone number of the payer in question. Supplied with the country code followed by digits. Swedish example: country code +46, phone number 701740605 = +46701740605
	 */
    public function setPhoneNumber(string $phoneNumber): self {
		$this->data['phoneNumber'] = $phoneNumber;

		return $this;
	}
}
