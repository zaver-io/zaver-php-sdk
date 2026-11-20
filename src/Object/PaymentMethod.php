<?php
namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * PaymentMethod as found in `PaymentMethodsResponse`
 * 
 * @method string         getPaymentMethod()         The Payment Method identifier.
 * @method ?string        getPaymentMethodVariant()  Optional variant of the payment method, e.g. `14_DAYS`, `30_DAYS` or `90_DAYS` for `PAY_LATER`. Only present for merchants with several variants of a payment method; each variant is listed as its own entry.
 * @method string         getTitle()            The title of the payment method for display purposes.
 * @method string         getDescription()      A short text that describes the payment method.
 * @method string         getIconSvgSrc()       A URL to an SVG icon for the payment method.
 * @method ?string        getIconOnlySvgSrc()   A URL to the icon without its badge, for recolouring.
 * @method array          getLocalizations()    Array of localizations for title, descriptions and icon corresponding to the selected market.
 *
 * Consumer credit methods (`PAY_LATER`, `INSTALLMENTS`, `MONTHLY_INVOICE`) listed for an amount also carry the texts
 * a shop shows under the method: `getCreditWarningHtml()` and `getRepresentativeExample()`.
 */
class PaymentMethod extends DataObject {

	/**
	 * Alias for `getPaymentMethod`
	 */
	public function getIdentifier(): string {
		return $this->getPaymentMethod();
	}
	/**
	 * Consumer credit methods, when the request carried an amount: the credit warning the market requires next to
	 * the method, as an HTML fragment (a strong heading, the text and the market's image where one is prescribed).
	 * Render it as HTML, do not escape it. Null for other methods, without an amount and in markets without a
	 * warning.
	 *
	 * @param string $language `local` (the language of the request, default) or `en-GB`
	 */
	public function getCreditWarningHtml(string $language = 'local'): ?string {
		return $this->text('creditWarningHtml', $language);
	}

	/**
	 * Consumer credit methods, when the request carried an amount: the representative example (standard information)
	 * of the method's cheapest option for that amount, to show under the credit warning. For an invoice the example
	 * of its duration, for installments and monthly invoices the payment plan with the lowest monthly amount. Null
	 * otherwise.
	 *
	 * @param string $language `local` (the language of the request, default) or `en-GB`
	 */
	public function getRepresentativeExample(string $language = 'local'): ?string {
		return $this->text('representativeExample', $language);
	}

	private function text(string $field, string $language): ?string {
		$texts = $this->data[$field] ?? null;

		return is_array($texts) && isset($texts[$language]) ? (string)$texts[$language] : null;
	}
}
