<?php

namespace Web4pro\Links\Block\Adminhtml\Grid\Edit\Tab;

//use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Widget\Grid\Extended;

class Conditions extends Extended implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_cmsPage;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Cms\Model\Page $cmsPage
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Cms\Model\Page $cmsPage,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory,
        \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_cmsPage = $cmsPage;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('cmsPageGrid');
        $this->setDefaultSort('page_id');
        //$this->setDefaultDir('ASC');
        //$this->setUseAjax(true);
        //var_dump($this->_cmsPage);exit();
    }

    /*protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'indexAA') {
            $pageIds = $this->_getSelectedProducts();
            if (empty($pageIds)) {
                $pageIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('page_id', ['in' => $pageIds]);
            } else {
                if ($pageIds) {
                    $this->getCollection()->addFieldToFilter('page_id', ['nin' => $pageIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }*/

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Magento\Cms\Model\ResourceModel\Page\Collection */
        //$collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
   /* public function getPage()
    {
        return $this->_coreRegistry->registry('current_page');
    }*/
    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'pages[]',
            [
                /*'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction',*/
                'type' => 'checkbox',
                'values' => 'page_id'/*$this->getCollection()*/,
                'index' => 'page_id'
                /*'use_index' => true*/

            ]
        );

        $this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);

        $this->addColumn('identifier', ['header' => __('URL Key'), 'index' => 'identifier']);

        $this->addColumn(
            'page_layout',
            [
                'header' => __('Layout'),
                'index' => 'page_layout',
                'type' => 'options',
                'options' => $this->pageLayoutBuilder->getPageLayoutsConfig()->getOptions()
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                [
                    'header' => __('Store View'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback' => [$this, '_filterStoreCondition']
                ]
            );
        }

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => $this->_cmsPage->getAvailableStatuses()
            ]
        );

        $this->addColumn(
            'creation_time',
            [
                'header' => __('Created'),
                'index' => 'creation_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        $this->addColumn(
            'update_time',
            [
                'header' => __('Modified'),
                'index' => 'update_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        /*$this->addColumn(
            'page_actions',
            [
                'header' => __('Action'),
                'sortable' => false,
                'filter' => false,
                'renderer' => \Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action::class,
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );*/

        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store condition
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @param \Magento\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \Magento\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

  /*protected function _getSelectedPages()
    {

        $pages = array_keys($this->getSelectedPages());

        return $pages;
    }
    public function getSelectedPages()
    {
        $tm_id = $this->getRequest()->getParam('id');
        if(!isset($tm_id)) {
            $tm_id = 0;
        }



        $collection = $this->_collectionFactory->create()->load($tm_id);
        $data =  $collection->getPageId();
        $pages = explode(',',$data);

        $proIds = array();

        foreach($pages as $page) {
            $proIds[$page] = array('id'=>$page);
        }

        return $proIds;
    }*/
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Grid links');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Grid links');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     */

}