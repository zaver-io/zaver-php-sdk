<?php

namespace Zaver\SDK\Config;

/**
 * Payment method identifiers of the Zaver API v2.
 *
 * `DIRECT_DEBIT` is the former name of `BANK_TRANSFER` and will disappear from the API; treat it as `BANK_TRANSFER`.
 *
 * Credit pricing (`Checkout::getCreditPricing()`) prices `PAY_LATER`, `INSTALLMENTS` and `MONTHLY_INVOICE`; more
 * methods may be priced later, so the SDK does not restrict the value.
 */
class PaymentMethod {
	const SWISH = 'SWISH';
	const VIPPS = 'VIPPS';
	const BANK_TRANSFER = 'BANK_TRANSFER';
	const PAY_LATER = 'PAY_LATER';
	const INSTALLMENTS = 'INSTALLMENTS';
	const INSTALLMENTS_STATIC_AMOUNT = 'INSTALLMENTS_STATIC_AMOUNT';
	const B2B_R2P = 'B2B_R2P';
	const B2B_PAY_LATER = 'B2B_PAY_LATER';
	const B2B_INSTALLMENTS = 'B2B_INSTALLMENTS';
	const DIRECT_DEBIT = 'DIRECT_DEBIT';
	const INSTANT_DIRECT_DEBIT = 'INSTANT_DIRECT_DEBIT';
	const MONTHLY_INVOICE = 'MONTHLY_INVOICE';
	const OTHER = 'OTHER';

	const METHODS = [
		self::SWISH,
		self::VIPPS,
		self::BANK_TRANSFER,
		self::PAY_LATER,
		self::INSTALLMENTS,
		self::INSTALLMENTS_STATIC_AMOUNT,
		self::B2B_R2P,
		self::B2B_PAY_LATER,
		self::B2B_INSTALLMENTS,
		self::DIRECT_DEBIT,
		self::INSTANT_DIRECT_DEBIT,
		self::MONTHLY_INVOICE,
		self::OTHER,
	];
}
