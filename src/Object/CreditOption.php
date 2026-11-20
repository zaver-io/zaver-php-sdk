<?php

namespace Zaver\SDK\Object;
use Zaver\SDK\Config\PaymentMethod;
use Zaver\SDK\Utils\DataObject;

/**
 * One consumer credit option of a `CreditPricingResponse`: a single invoice (`PAY_LATER`, with `getDueInDays()`),
 * or installments / a monthly invoice (`INSTALLMENTS`, `MONTHLY_INVOICE`, with `getNumberOfInstallments()`).
 * Amounts are in the currency of the response, rates per year.
 *
 * @method string getPaymentMethod()  `PaymentMethod::PAY_LATER`, `PaymentMethod::INSTALLMENTS` or `PaymentMethod::MONTHLY_INVOICE`.
 */
class CreditOption extends DataObject {

	public function isPayLater(): bool {
		return ($this->data['paymentMethod'] ?? null) === PaymentMethod::PAY_LATER;
	}

	/**
	 * The term in months. Null for a single invoice.
	 */
	public function getNumberOfInstallments(): ?int {
		return isset($this->data['numberOfInstallments']) ? (int)$this->data['numberOfInstallments'] : null;
	}

	/**
	 * The days the payer has to pay the invoice. Null unless the option is a single invoice.
	 */
	public function getDueInDays(): ?int {
		return isset($this->data['dueInDays']) ? (int)$this->data['dueInDays'] : null;
	}

	/**
	 * The amount financed. For a payment plan conversion option the full invoice amount.
	 */
	public function getPrincipalAmount(): float {
		return (float)($this->data['principalAmount'] ?? 0);
	}

	/**
	 * The fee paid once. For a single invoice the invoice fee.
	 */
	public function getStartFee(): float {
		return (float)($this->data['startFee'] ?? 0);
	}

	/**
	 * The fee paid with every installment. Zero for a single invoice.
	 */
	public function getRecurringFee(): float {
		return (float)($this->data['recurringFee'] ?? 0);
	}

	/**
	 * What the payer pays per month; the first and last installment can differ. Null for a single invoice.
	 */
	public function getMonthlyAmount(): ?float {
		return isset($this->data['monthlyAmount']) ? (float)$this->data['monthlyAmount'] : null;
	}

	/**
	 * What the payer pays in total, fees included.
	 */
	public function getTotalAmount(): float {
		return (float)($this->data['totalAmount'] ?? 0);
	}

	/**
	 * Nominal annual interest rate for display, in percent with the sign and without trailing zeros: "10.99%", "2.5%", "0%".
	 */
	public function getNominalAnnualInterestRatePercent(): string {
		return (string)($this->data['nominalAnnualInterestRatePercent'] ?? '0%');
	}

	/**
	 * Effective annual interest rate (APR) for display, in the same format: "18.82%".
	 */
	public function getEffectiveAnnualInterestRatePercent(): string {
		return (string)($this->data['effectiveAnnualInterestRatePercent'] ?? '0%');
	}

	/**
	 * Nominal annual interest rate for calculation, as a fraction: 0.1099 for 10.99 %.
	 */
	public function getNominalAnnualInterestRateFraction(): float {
		return (float)($this->data['nominalAnnualInterestRateFraction'] ?? 0);
	}

	/**
	 * Effective annual interest rate (APR) for calculation, as a fraction: 0.1882 for 18.82 %.
	 */
	public function getEffectiveAnnualInterestRateFraction(): float {
		return (float)($this->data['effectiveAnnualInterestRateFraction'] ?? 0);
	}

	/**
	 * The representative example (standard information) to show with the option, as plain text. For a single
	 * invoice that can be converted it covers both the invoice and the payment plan. Null when there is no credit
	 * cost to show.
	 *
	 * @param string $language `local` for the language of the response, or `en-GB`
	 */
	public function getRepresentativeExample(string $language = 'local'): ?string {
		$example = $this->data['representativeExample'] ?? [];

		return is_array($example) && isset($example[$language]) ? (string)$example[$language] : null;
	}

	/**
	 * Single invoice only: the payment plan the unpaid balance is converted to on the due date, as declared in the
	 * loan terms. Priced on the full amount over the longest term, since the payer may pay any part of the invoice
	 * before then. Longest term first, usually one entry; empty when the invoice cannot be converted.
	 *
	 * @return CreditOption[]
	 */
	public function getPaymentPlanConversionOptions(): array {
		$options = [];
		foreach ((array)($this->data['paymentPlanConversionOptions'] ?? []) as $option) {
			if (is_array($option)) {
				$options[] = self::create($option);
			}
		}

		return $options;
	}

	/**
	 * Whether the unpaid balance of this invoice is converted to a payment plan on the due date.
	 */
	public function isConvertible(): bool {
		return $this->getPaymentPlanConversionOptions() !== [];
	}
}
