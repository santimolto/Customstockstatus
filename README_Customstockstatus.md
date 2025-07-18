# Santi_Customstockstatus

Este módulo permite mostrar un mensaje de stock personalizado debajo del precio en la página de producto y en la vista de listado de categorías en Magento 2 con Hyvä.

## 🧩 Requisitos

- Magento 2.4.7 (compatible con parches recientes)
- Hyvä Theme
- El atributo personalizado `custom_stock_status` debe estar presente en los productos

## 📦 Instalación

### Opción 1: Manual

1. Copiar el módulo en `app/code/Santi/Customstockstatus/`
2. Ejecutar:

```bash
bin/magento module:enable Santi_Customstockstatus
bin/magento setup:upgrade
bin/magento cache:flush
```

### Opción 2: Composer desde GitHub

Agrega en `composer.json`:

```json
"repositories": {
  "santi-customstockstatus": {
    "type": "vcs",
    "url": "https://github.com/santimolto/Customstockstatus.git"
  }
}
```

Luego instala con:

```bash
composer require santi/customstockstatus:dev-main
bin/magento setup:upgrade
```

## 🛠️ Modificaciones necesarias para Hyvä

Para que el mensaje de stock se muestre correctamente en Hyvä, hay que realizar los siguientes pasos:

### 1. Modificar `catalog_product_view_type_simple.xml`

Ubicado en:  
`app/code/Santi/Customstockstatus/view/frontend/layout/catalog_product_view_type_simple.xml`

Agrega el bloque justo debajo del contenedor de precio:

```xml
<referenceBlock name="product.price.final">
    <block class="Magento\Framework\View\Element\Template"
           name="custom.stock.status.block"
           template="Santi_Customstockstatus::stockstatus.phtml"
           after="-"
           arguments="true">
        <arguments>
            <argument name="product" xsi:type="object">Magento\Catalog\Model\Product</argument>
        </arguments>
    </block>
</referenceBlock>
```

### 2. Modificar `price.phtml` en Hyvä

Ubica el archivo:

```
app/design/frontend/Vendor/Hyva/Magento_Catalog/templates/product/view/price.phtml
```

Agrega donde desees que se renderice el stock personalizado, por ejemplo justo después del precio:

```php
<?= $block->getChildHtml('custom.stock.status.block') ?>
```

### 3. stockstatus.phtml

El archivo `stockstatus.phtml` renderiza el mensaje. Ubicación:

```
view/frontend/templates/stockstatus.phtml
```

Ejemplo de contenido funcional:

```php
<div id="santi_test-id" class="santi_test" style="border:1px solid red;">
    Bloque de test de stock personalizado
</div>
<?php
$product = $block->getProduct() ?? $block->getData('product');
if (!$product || !$product->getId()) return;

$stockStatus = $product->getCustomStockStatus();
try {
    $stockRegistry = \Magento\Framework\App\ObjectManager::getInstance()
        ->get(\Magento\CatalogInventory\Api\StockRegistryInterface::class);
    $stockItem = $stockRegistry->getStockItem($product->getId());
    $qty = $stockItem && $stockItem->getItemId() ? (int)$stockItem->getQty() : null;
} catch (\Exception $e) {
    $qty = null;
}

if ($stockStatus):
    if ($qty !== null && strpos($stockStatus, '{qty}') !== false):
        $translated = str_replace('{qty}', $qty, __('%1 in stock', $qty));
    else:
        $translated = __($stockStatus);
    endif;
    ?>
    <div class="custom-stock-status text-sm text-gray-700 font-medium my-1">
        <?= $block->escapeHtml($translated) ?>
    </div>
<?php endif; ?>
```

## ✅ Resultado esperado

Se mostrará un mensaje personalizado como:

> "Disponible en almacén"  
o  
> "Quedan solo 3 unidades"

...dependiendo del contenido del atributo `custom_stock_status`, que puede incluir `{qty}` para mostrar unidades disponibles.

---

**Autor:** Santi Moltó  
**Licencia:** MIT