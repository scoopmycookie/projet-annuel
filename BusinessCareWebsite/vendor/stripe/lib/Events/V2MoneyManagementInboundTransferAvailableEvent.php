<?php

// File generated from our OpenAPI spec

namespace Stripe\Events;

/**
 * @property \Stripe\RelatedObject $related_object Object containing the reference to API resource relevant to the event
 * @property \Stripe\EventData\V2MoneyManagementInboundTransferAvailableEventData $data data associated with the event
 */
class V2MoneyManagementInboundTransferAvailableEvent extends \Stripe\V2\Event
{
    const LOOKUP_TYPE = 'v2.money_management.inbound_transfer.available';

    /**
     * Retrieves the related object from the API. Make an API request on every call.
     *
     * @return \Stripe\V2\MoneyManagement\InboundTransfer
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function fetchRelatedObject()
    {
        $apiMode = \Stripe\Util\Util::getApiMode($this->related_object->url);
        list($object, $options) = $this->_request(
            'get',
            $this->related_object->url,
            [],
            ['stripe_account' => $this->context],
            [],
            $apiMode
        );

        return \Stripe\Util\Util::convertToStripeObject($object, $options, $apiMode);
    }

    public static function constructFrom($values, $opts = null, $apiMode = 'v2')
    {
        $evt = parent::constructFrom($values, $opts, $apiMode);
        if (null !== $evt->data) {
            $evt->data = \Stripe\EventData\V2MoneyManagementInboundTransferAvailableEventData::constructFrom($evt->data, $opts, $apiMode);
        }

        return $evt;
    }
}
