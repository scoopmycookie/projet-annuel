<?php

// File generated from our OpenAPI spec

namespace Stripe\V2\MoneyManagement;

/**
 * ReceivedDebit resource.
 *
 * @property string $id Unique identifier for the ReceivedDebit.
 * @property string $object String representing the object's type. Objects of the same type share the same value of the object field.
 * @property \Stripe\StripeObject $amount Amount and currency of the ReceivedDebit.
 * @property null|(object{financial_address: string, payment_method_type: string, statement_descriptor: null|string, us_bank_account: (object{bank_name: null|string, network: string, routing_number: null|string}&\Stripe\StripeObject)}&\Stripe\StripeObject) $bank_transfer This object stores details about the originating banking transaction that resulted in the ReceivedDebit. Present if <code>type</code> field value is <code>bank_transfer</code>.
 * @property null|(object{authorization: null|(object{amount: \Stripe\StripeObject, issuing_authorization_v1: string}&\Stripe\StripeObject), card_transactions: (object{amount: \Stripe\StripeObject, issuing_transaction_v1: string}&\Stripe\StripeObject)[], card_v1_id: string}&\Stripe\StripeObject) $card_spend This object stores details about the issuing transactions that resulted in the ReceivedDebit. Present if <code>type</code> field value is <code>card_spend</code>.
 * @property int $created The time at which the ReceivedDebit was created. Represented as a RFC 3339 date &amp; time UTC value in millisecond precision, for example: <code>2022-09-18T13:22:18.123Z</code>.
 * @property null|string $description Freeform string sent by the originator of the ReceivedDebit.
 * @property string $financial_account Financial Account on which funds for ReceivedDebit were debited.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|string $receipt_url A link to the Stripe-hosted receipt for this ReceivedDebit.
 * @property string $status Open Enum. The status of the ReceivedDebit.
 * @property null|(object{failed: (object{reason: string}&\Stripe\StripeObject)}&\Stripe\StripeObject) $status_details Detailed information about the status of the ReceivedDebit.
 * @property null|(object{canceled_at: null|int, failed_at: null|int, succeeded_at: null|int}&\Stripe\StripeObject) $status_transitions The time at which the ReceivedDebit transitioned to a particular status.
 * @property string $type Open Enum. The type of the ReceivedDebit.
 */
class ReceivedDebit extends \Stripe\ApiResource
{
    const OBJECT_NAME = 'v2.money_management.received_debit';

    const STATUS_CANCELED = 'canceled';
    const STATUS_FAILED = 'failed';
    const STATUS_PENDING = 'pending';
    const STATUS_RETURNED = 'returned';
    const STATUS_SUCCEEDED = 'succeeded';

    const TYPE_BANK_TRANSFER = 'bank_transfer';
    const TYPE_CARD_SPEND = 'card_spend';
    const TYPE_EXTERNAL_DEBIT = 'external_debit';
}
