<?php
namespace Santi\Customstockstatus\Plugin;

use Magento\Framework\View\Element\Template;

class ProductStockStatusPlugin
{
    protected $stockRegistry;

    public function __construct(\Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry)
    {
        $this->stockRegistry = $stockRegistry;
    }

    public function beforeToHtml(Template $subject)
    {
        if ($product = $subject->getProduct()) {
            $subject->setData('stock_item', $this->stockRegistry->getStockItem($product->getId()));
        }
    }
}
