<?php

namespace Web4pro\Address\Model\Quote\Address\ResourceModel\Address;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Web4pro\Address\Model\Quote\Address\Address',
            'Web4pro\Address\Model\Quote\Address\ResourceModel\Address'
        );
    }
}