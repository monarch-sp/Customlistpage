<?php
namespace Shineshit\Newarrivalcategory\Block\Index;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection;
class Collection extends ListProduct{
    public function setProductCollection(AbstractCollection $collection)
    {
        $this->_productCollection = $collection;
    }

    public function getLoadedProductCollection()
    {
        return $this->_productCollection;
    }
    public function customProductCollection()
    {
     return $this->_productCollection;   
    }

}
