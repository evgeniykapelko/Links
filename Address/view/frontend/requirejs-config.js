var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Web4pro_Address/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/model/place-order': {
                'Web4pro_Address/js/model/place-order-mixin': true
            }
        }
    }
};