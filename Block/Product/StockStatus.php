<?php
namespace Santi\Customstockstatus\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;

class StockStatus extends Template
{
    protected $registry;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }
}
