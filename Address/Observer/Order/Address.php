<?php

namespace Web4pro\Address\Observer\Order;

class Address implements \Magento\Framework\Event\ObserverInterface
{
    protected $_objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManagerInterface)
    {
        $this->_objectManager = $objectManagerInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($address = $observer->getEvent()->getAddress()){
            if($attributes = $address->getExtensionAttributes()){
                $customAddress = $this->_objectManager->create('\Web4pro\Address\Model\Order\Address');
                $customAddress->setType($attributes->getType())->setEntityId($address->getId())->save();
            }
        }
    }

}