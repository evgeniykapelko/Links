<?php

namespace Web4pro\Address\Model\Address;

class Type extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const VALUE_RESIDENCE = 1;

    const VALUE_BUSINESS = 2;
    
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Please Select'), 'value' => '0'],
                ['label' => __('Residence'), 'value' => self::VALUE_RESIDENCE],
                ['label' => __('Business'), 'value' => self::VALUE_BUSINESS],
            ];
        }
        return $this->_options;
    }
}