<?php
namespace Zaver\SDK\Object;
use Zaver\SDK\Utils\DataObject;

/**
 * Line item for `PaymentCreationRequest`
 *
 * @method string getId()                A unique id for the line item. Set by Zaver.
 * @method string getName()              Name of the item being sold.
 * @method int    getQuantity()          Total number of units being paid.
 * @method float  getUnitPrice()         Price per unit being paid, including tax.
 * @method float  getTotalAmount()       Total amount of line item including tax. Must satisfy `totalAmount = unitPrice x quantity`.
 * @method float  getTaxRatePercent()    Tax percentage for a line item - e.g. `25.0`.
 * @method float  getTaxAmount()         Total amount of tax (e.g. VAT) included in the line item.
 * @method string getDescription()       Longer description of the line item.
 * @method string getItemType()          One of: `PHYSICAL`, `DIGITAL`, `SERVICE`, `SHIPPING`, `FEE`, `DISCOUNT`
 * @method string getMerchantReference() Your reference for a line item, e.g. a SKU.
 * @method array  getMerchantMetadata()  An associative array of merchant-defined key-value pairs.
 * @method string getQuantityUnit()      The unit in which quantity is measured - e.g. pcs, kgs.
 */
class LineItem extends DataObject {

	/**
	 * Optional. The id of the line item.
	 * This is set by Zaver when the payment request is created.
	 * Should only be set when performing a payment capture of a line item in order
	 * to match the line item created in the payment request.
	 *
	 * Recommendation:
	 * Create a payment request with line items that have [merchantReference] set to
	 * a unique identifier in your system. Then, in the response or, when calling the
	 * getInfo endpoint, the combination of merchantReference & id will be returned.
	 * This id can then be used to capture the original line items (from the payment
	 * request creation).
	 */
	public function setId(string $id): self {
		$this->data['id'] = $id;

		return $this;
	}


	/**
	 * Required. Name of the item being sold.
	 */
	public function setName(string $name): self {
		$this->data['name'] = $name;

		return $this;
	}

	/**
	 * Required. Total number of units being paid.
	 */
	public function setQuantity(int $quantity): self {
		$this->data['quantity'] = $quantity;

		return $this;
	}

	/**
	 * Required. Price per unit being paid, including tax.
	 */
	public function setUnitPrice(float $unitPrice): self {
		$this->data['unitPrice'] = $unitPrice;

		return $this;
	}

	/**
	 * Required. Total amount of line item including tax. Must satisfy `totalAmount = unitPrice x quantity`.
	 */
	public function setTotalAmount(float $totalAmount): self {
		$this->data['totalAmount'] = $totalAmount;

		return $this;
	}

	/**
	 * Required. Tax percentage for a line item - e.g. `25.0`.
	 */
	public function setTaxRatePercent(float $taxRatePercent): self {
		$this->data['taxRatePercent'] = $taxRatePercent;

		return $this;
	}

	/**
	 * Total amount of tax (e.g. VAT) included in the line item.
	 */
	public function setTaxAmount(float $taxAmount): self {
		$this->data['taxAmount'] = $taxAmount;

		return $this;
	}

	/**
	 * Longer description of the line item.
	 */
	public function setDescription(string $description): self {
		$this->data['description'] = $description;

		return $this;
	}

	/**
	 * One of: `PHYSICAL`, `DIGITAL`, `SERVICE`, `SHIPPING`, `FEE`, `DISCOUNT`
	 */
	public function setItemType(string $itemType): self {
		$this->data['itemType'] = $itemType;

		return $this;
	}

	/**
	 * Your reference for a line item, e.g. a SKU.
	 */
	public function setMerchantReference(string $merchantReference): self {
		$this->data['merchantReference'] = $merchantReference;

		return $this;
	}

	/**
	 * An associative array of merchant-defined key-value pairs. These are returned with the Payment Status Response.
	 * A Maximum of 20 pairs is allowed, each key and value with a maximum length of 200 characters.
	 *
	 * @deprecated Use `lineItemMetadata` instead, deprecated since 2.0.0
	 */
	public function setMerchantMetadata(array $merchantMetadata): self {
		error_log('Deprecated method `Zaver\SDK\Object\LineItem::setMerchantMetadata` called. Use `Zaver\SDK\Object\LineItem::setLineItemMetadata` instead. Deprecated since version 2.0.0');
		return $this->setLineItemMetadata($merchantMetadata);
	}

	/**
	 * The unit in which quantity is measured - e.g. pcs, kgs.
	 */
	public function setQuantityUnit(string $quantityUnit): self {
		$this->data['quantityUnit'] = $quantityUnit;

		return $this;
	}

	/**
	 * An associative array of merchant-defined key-value pairs. These are returned with the Payment Status Response.
	 * A Maximum of 20 pairs is allowed, each key and value with a maximum length of 200 characters.
	 */
	public function setLineItemMetadata(array $lineItemMetadata): self {
		$this->data['lineItemMetadata'] = $lineItemMetadata;

		return $this;
	}
}
