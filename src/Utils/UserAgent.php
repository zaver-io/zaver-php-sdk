<?php
namespace Zaver\SDK\Utils;
use InvalidArgumentException;
use Zaver\SDK\Config\Version;

/**
 * Builds the `User-Agent` header that is sent with every request to the Zaver API, so Zaver can see
 * which SDK, platform and plugin versions an integration runs.
 *
 * The header is a list of RFC 9110 product tokens. The SDK token comes first and the PHP version last;
 * everything in between is optional and added by the integration:
 *
 *     UserAgent::create()
 *         ->addProduct('Shopware', '6.6.10.6')
 *         ->addProduct('zaver-shopware6-plugin', '1.0.11');
 *     // zaver-php-sdk/3.1.0 Shopware/6.6.10.6 zaver-shopware6-plugin/1.0.11 PHP/8.3.12
 */
class UserAgent {
	const SDK_PRODUCT = 'zaver-php-sdk';

	/** @var array<string, string> Product name => version, in insertion order */
	private $products = [];

	static public function create(): self {
		return new self();
	}

	/**
	 * Add a product token, e.g. the shop platform and its version, or the plugin and its version.
	 * Characters that are not allowed in a token are replaced with "-". Adding a name twice replaces its version.
	 */
	public function addProduct(string $name, string $version): self {
		$name = self::token($name);

		if($name === '') {
			throw new InvalidArgumentException('Product name must not be empty');
		}

		$this->products[$name] = self::token($version) ?: 'unknown';

		return $this;
	}

	/** @return array<string, string> Product name => version, without the SDK and PHP tokens */
	public function getProducts(): array {
		return $this->products;
	}

	public function __toString(): string {
		$tokens = [self::SDK_PRODUCT . '/' . Version::SDK];

		foreach($this->products as $name => $version) {
			$tokens[] = $name . '/' . $version;
		}

		$tokens[] = 'PHP/' . self::token(PHP_VERSION);

		return implode(' ', $tokens);
	}

	/** Reduce a string to the characters allowed in an RFC 9110 token. */
	static private function token(string $value): string {
		return trim((string)preg_replace('/[^A-Za-z0-9!#$%&\'*+.^_`|~-]+/', '-', $value), '-');
	}
}
