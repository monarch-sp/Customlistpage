<?php
namespace Shineshit\Newarrivalcategory\Block\Index;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection;
class Newarrival extends ListProduct{
   const XML_PATH_BESTSELLER = 'catalog/customlistpage/newarrivallimit';

    protected $scopeConfig;
    protected $_customerSession;
    protected $categoryFactory;
    protected $helper;
    protected $_productCollectionFactory;

    public function __construct(
    \Magento\Catalog\Block\Product\Context $context,
    \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
    \Magento\Catalog\Model\Layer\Resolver $layerResolver,
    \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
    \Magento\Framework\Url\Helper\Data $urlHelper,
    array $data = [],
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;  
        $this->_customerSession = $customerSession;
        $this->categoryFactory = $categoryFactory;    
        $this->scopeConfig = $scopeConfig;    
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }   
    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        /* @var $layer Layer */
        if ($this->getShowRootCategory()) {
            $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        }

        // if this is a product view page
        if ($this->_coreRegistry->registry('product')) {
            // get collection of categories this product is associated with
            $categories = $this->_coreRegistry->registry('product')
                ->getCategoryCollection()->setPage(1, 1)
                ->load();
            // if the product is associated with any category
            if ($categories->count()) {
                // show products from this category
                $this->setCategoryId($categories->getIterator()->current()->getId());
            }
        }

        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }
        $collection = $layer->getProductCollection();

        //$collection = $collection->addAttributeToFilter('entity_id' ,array('gt' => 3930));                
        //$collection = $collection->clear()->getSelect()->reset(\Zend_Db_Select::WHERE)->reset(\Zend_Db_Select::ORDER);

        $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );

        return $collection;
    }



    public function setCollection($collection)
    {
        $collection = $collection->getSelect() -> reset(\Zend_Db_Select::ORDER)->reset(\Zend_Db_Select::WHERE);

        $this->_productCollection = $collection;
        return $this;
    }
    
    protected function _getProductCollection()    
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }
        $collection = $this->_productCollection;   
        $collection->addAttributeToSort('entity_id' , 'desc');            
       /* $entityId = $this->getMaxEntityId(); 
        $collectionOffset=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $collectionLimit = 16;
        if($collectionOffset > 1) {
            $collectionOffset = $collectionOffset -1;
            $collectionOffset = $collectionLimit * $collectionOffset;
        }
        $collection->addAttributeToFilter('entity_id' , array('gt' => $entityId));
        $collection->clear()->getSelect()->limit($collectionLimit, $collectionOffset)->reset(\Zend_Db_Select::WHERE)->reset(\Zend_Db_Select::ORDER)->Order('entity_id DESC');
        $collection->addAttributeToFilter('entity_id' , array('gt' => $entityId));        */
        //echo $collection->getSelect();
        return $collection;
    }

  
   
   

}
