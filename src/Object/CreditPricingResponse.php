<?php

namespace Zaver\SDK\Object;
use Zaver\SDK\Config\PaymentMethod;
use Zaver\SDK\Utils\DataObject;

/**
 * Zaver's answer to a `CreditPricingRequest`: the consumer credit options a shop may show for the amount, plus the
 * texts, links and logos needed to present them.
 *
 * @method float  getPrincipalAmount()  The requested amount.
 * @method string getCurrency()         The ISO currency code.
 * @method string getMarket()           The ISO 3166-1 country code of the market.
 * @method string getLanguage()         Language of every `local` text, e.g. "sv-SE".
 */
class CreditPricingResponse extends DataObject {

	/**
	 * The options the merchant may offer: the single invoices first (one per invoice the merchant offers, shortest
	 * due date first), then installments and monthly invoices with the shortest term first. Empty when none can be
	 * offered, which is a normal answer, not an error.
	 *
	 * @param ?string $paymentMethod only the options of this method, e.g. `PaymentMethod::INSTALLMENTS`
	 * @return CreditOption[]
	 */
	public function getOptions(?string $paymentMethod = null): array {
		$options = [];
		foreach ((array)($this->data['options'] ?? []) as $option) {
			if (is_array($option) && ($paymentMethod === null || ($option['paymentMethod'] ?? null) === $paymentMethod)) {
				$options[] = CreditOption::create($option);
			}
		}

		return $options;
	}

	/**
	 * The single invoice options (`PAY_LATER`), shortest due date first.
	 *
	 * @return CreditOption[]
	 */
	public function getPayLaterOptions(): array {
		return $this->getOptions(PaymentMethod::PAY_LATER);
	}

	/**
	 * The installment options, shortest term first.
	 *
	 * @return CreditOption[]
	 */
	public function getInstallmentOptions(): array {
		return $this->getOptions(PaymentMethod::INSTALLMENTS);
	}

	/**
	 * The lowest monthly amount over all options, for a "from X per month" badge. Null when no option is paid monthly.
	 */
	public function getLowestMonthlyAmount(): ?float {
		return isset($this->data['lowestMonthlyAmount']) ? (float)$this->data['lowestMonthlyAmount'] : null;
	}

	/**
	 * The option with the lowest monthly amount, or null when no option is paid monthly.
	 */
	public function getLowestMonthlyOption(): ?CreditOption {
		$lowest = null;
		foreach ($this->getOptions() as $option) {
			$monthly = $option->getMonthlyAmount();
			if ($monthly !== null && ($lowest === null || $monthly < $lowest->getMonthlyAmount())) {
				$lowest = $option;
			}
		}

		return $lowest;
	}

	/**
	 * URL of Zaver's general terms for the market.
	 */
	public function getTermsUrl(): ?string {
		return isset($this->data['termsLink']['url']) ? (string)$this->data['termsLink']['url'] : null;
	}

	/**
	 * Link text for `getTermsUrl()`, e.g. "Se villkor." / "View Terms.".
	 *
	 * @param string $language `local` for the language of the response, or `en-GB`
	 */
	public function getTermsLinkText(string $language = 'local'): ?string {
		return isset($this->data['termsLink'][$language]) ? (string)$this->data['termsLink'][$language] : null;
	}

	/**
	 * URL of the market's general Standard European Consumer Credit Information (SECCI) document.
	 */
	public function getSecciUrl(): ?string {
		return isset($this->data['secciLink']['url']) ? (string)$this->data['secciLink']['url'] : null;
	}

	/**
	 * The abbreviation of the SECCI document as link text for `getSecciUrl()`: "SEKKI" in Swedish, "SECCI" otherwise.
	 *
	 * @param string $language `local` for the language of the response, or `en-GB`
	 */
	public function getSecciLinkText(string $language = 'local'): ?string {
		return isset($this->data['secciLink'][$language]) ? (string)$this->data['secciLink'][$language] : null;
	}

	/**
	 * A short general conditions text Zaver suggests to show next to the options.
	 *
	 * @param string $language `local` for the language of the response, or `en-GB`
	 */
	public function getSuggestedCopy(string $language = 'local'): ?string {
		return isset($this->data['suggestedCopy'][$language]) ? (string)$this->data['suggestedCopy'][$language] : null;
	}

	/**
	 * The warning the market requires next to every credit offer, as an HTML fragment: a `<strong>` heading, a short
	 * text and, where the market prescribes one, an `<img>` (inline SVG or a cdn.zaver.com link). Only `strong`, `br`
	 * and `img` are used. Print it unescaped wherever an option is shown. Sweden and the Netherlands have a prescribed
	 * wording in their own language, the same in both texts. Null for markets without a warning (Norway).
	 *
	 * @param string $language `local` for the language of the response, or `en-GB`
	 */
	public function getCreditWarningHtml(string $language = 'local'): ?string {
		return isset($this->data['creditWarningHtml'][$language]) ? (string)$this->data['creditWarningHtml'][$language] : null;
	}

	/**
	 * URLs of Zaver's logos, keyed `logoSvg`, `logoSvgWhite`, `logoPng` and `logoPngWhite`.
	 *
	 * @return array<string, string>
	 */
	public function getZaverAssets(): array {
		return (array)($this->data['zaverAssets'] ?? []);
	}

	/**
	 * URL of one Zaver logo, e.g. `logoSvg`.
	 */
	public function getZaverAsset(string $name): ?string {
		return isset($this->data['zaverAssets'][$name]) ? (string)$this->data['zaverAssets'][$name] : null;
	}
}
