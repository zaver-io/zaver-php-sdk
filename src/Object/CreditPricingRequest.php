<?php

namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * Asks Zaver which consumer credit options a shop may show for an amount before any payment exists: pay later
 * (single invoice), installments and monthly invoice, with the representative example in the requested language.
 *
 * @method float   getAmount()         The amount to finance, in the format 100 or 100.00.
 * @method string  getCurrency()       The ISO currency code of the amount.
 * @method string  getMarket()         The ISO 3166-1 country code of the market.
 * @method ?string getPaymentMethod()  The single method to price, or null for every consumer credit method.
 * @method ?string getLanguage()       Language of the returned texts, e.g. `Language::SV_SE`; the market's language when omitted.
 */
class CreditPricingRequest extends DataObject {

	/**
	 * Required. The amount to finance, greater than zero, in the format 100 or 100.00.
	 */
	public function setAmount(float $amount): self {
		$this->data['amount'] = $amount;

		return $this;
	}

	/**
	 * Required. The ISO currency code of the amount.
	 */
	public function setCurrency(string $currency): self {
		$this->data['currency'] = strtoupper($currency);

		return $this;
	}

	/**
	 * Required. The ISO 3166-1 country code of the market.
	 */
	public function setMarket(string $market): self {
		$this->data['market'] = strtoupper($market);

		return $this;
	}

	/**
	 * Optional. Price only this method: `PaymentMethod::PAY_LATER`, `PaymentMethod::INSTALLMENTS`,
	 * `PaymentMethod::INSTALLMENTS_STATIC_AMOUNT` or `PaymentMethod::MONTHLY_INVOICE`. Left out, every consumer credit method the merchant can offer for the amount
	 * is priced. Other methods are rejected by the API.
	 */
	public function setPaymentMethod(string $paymentMethod): self {
		$this->data['paymentMethod'] = $paymentMethod;

		return $this;
	}

	/**
	 * Language (with locale) of the returned texts, one of `Zaver\SDK\Config\Language`. Defaults to the market's
	 * language on Zaver's side.
	 */
	public function setLanguage(string $language): self {
		$this->data['language'] = $language;

		return $this;
	}
}
