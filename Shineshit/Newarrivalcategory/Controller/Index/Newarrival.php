<?php
namespace Shineshit\Newarrivalcategory\Controller\Index;
use Shineshit\Newarrivalcategory\Block\Index\Collection;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Newarrival extends Action
{
    /** @var PageFactory */
    protected $pageFactory;
    /** @var  \Magento\Catalog\Model\ResourceModel\Product\Collection */
    protected $productCollection;    
    /** @var  \Shineshit\Newarrivalcategory\Block\Index\Collection */
    protected $collection;
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Shineshit\Newarrivalcategory\Block\Index\Collection $collection
    )
    {
        $this->pageFactory = $pageFactory;
        $this->productCollection = $collectionFactory->create();
        $this->collection = $collection;
        parent::__construct($context);
    }
    public function execute()
    {
		$result = $this->pageFactory->create();
		$collection = $this->productCollection;
		$collection->addFieldToSelect('*');				
        $collection->addAttributeToSort('entity_id' , 'desc');                
		$this->collection->setProductCollection($collection);
		return $result;
    }
}
