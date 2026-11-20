<?php

namespace Zaver\SDK\Config;

/**
 * Languages (with locale) Zaver can render customer facing text in, e.g. the representative examples of a credit pricing.
 */
class Language {
	const SV_SE = 'sv-SE';
	const DA_DK = 'da-DK';
	const DE_DE = 'de-DE';
	const EN_GB = 'en-GB';
	const NB_NO = 'nb-NO';
	const FI_FI = 'fi-FI';
	const NL_NL = 'nl-NL';

	const LANGUAGES = [
		self::SV_SE,
		self::DA_DK,
		self::DE_DE,
		self::EN_GB,
		self::NB_NO,
		self::FI_FI,
		self::NL_NL,
	];
}
