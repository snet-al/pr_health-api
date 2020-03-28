<?php

namespace App\Helpers;

class ApiCodes
{
    const SUCCESS = 0;
    const USER_AUTHENTICATION_FAILED = 1;
    const VALIDATION_FAILED = 2;
    const RESOURCE_NOT_FOUND = 3;
    const CLIENT_NOT_FOUND = 3;
    const RESOURCE_EXISTS = 4;
    const GENERAL_ERROR = 5;
    const RESOURCE_INACTIVE = 6;
    const RESOURCE_CAN_NOT_BE_MODIFIED = 5;
    const WAREHOUSE_NOT_ACCESSABLE = 7;
    const TOO_MANY_LOGIN_ATTEMPTS = 8;
    const GROUP_HAS_PRODUCTS = 13;
    const BRAINTREE_TRANSACTION_FAILED = 800002;
    const EASYPAY_OREDER_ID_NOT_UNIQUE = 900001;
    const USER_DOES_NOT_HAVE_ENOUGH_POINTS = 1000001;
    const CLIENT_DOES_NOT_HAVE_ENOUGH_CREDITS = 2000001;
    const CLIENT_VALIDATION_CODE_INCORRECT = 2000002;
    const PUSH_NOTIFICATION_SETTING_NOT_FOUND = 3000001;
    const USER_EMAIL_EXISTS = 1000002;
    const USER_PASSWORD_EXPIRED = 1000003;
    const USER_EXCEEDED_MAX_NUMBER_OF_SMS_PER_DAY = 1000004;
    const USER_NOT_FOUND = 1000005;
    const OUTDATED_APPLICATION_VERSION = 10;

    public static function notEnoughPointsMessage()
    {
        return __('points.not_enough');
    }

    public static function notEnoughCreditsMessage()
    {
        return __('credits.not_enough_credit_to_transfer');
    }

    public static function getSuccessMessage()
    {
        return __('api.success_message');
    }

    public static function getResourceNotFoundMessage()
    {
        return __('api.resource_not_found');
    }

    public static function getUserAuthenticationFailedMessage()
    {
        return __('api.user_authentication_failed_message');
    }

    public static function getUserNotFoundMessage()
    {
        return __('api.user_not_found_message');
    }

    public static function getValidationFailedMessage()
    {
        return __('api.validation_failed_message');
    }

    // SALES
    public static function getSaleNotFoundMessage()
    {
        return __('api.sale_not_found_message');
    }

    public static function getSaleCanNotBeModifiedMessage()
    {
        return __('api.sale_can_not_be_modified_message');
    }

    public static function getSaleExistsMessage()
    {
        return __('api.sale_exists_message');
    }

    public static function getResourceExistsMessage()
    {
        return __('api.resource_exists');
    }

    public static function getGeneralErrorMessage()
    {
        return __('api.general_error_message');
    }

    public static function getResourceInactiveMessage()
    {
        return __('api.resource_inactive_message');
    }

    public static function getClientExistsMessage()
    {
        return __('api.client_exists_message');
    }

    public static function getClientNotFoundMessage()
    {
        return __('api.client_not_found_message');
    }

    public static function getClientInactiveMessage()
    {
        return __('api.client_inactive_message');
    }

    public static function getProductExistsMessage()
    {
        return __('api.product_exists_message');
    }

    public static function getProductNotFoundMessage()
    {
        return __('api.product_not_found_message');
    }

    public static function getProductInactiveMessage()
    {
        return __('api.product_inactive_message');
    }

    public static function getSaleInactiveMessage()
    {
        return __('api.sale_inactive_message');
    }

    public static function getSupplierExistsMessage()
    {
        return __('api.supplier_exists_message');
    }

    public static function getSupplierNotFoundMessage()
    {
        return __('api.supplier_not_found_message');
    }

    public static function getSupplierInactiveMessage()
    {
        return __('api.supplier_inactive_message');
    }

    public static function getWarehouseNotAccessableMessage()
    {
        return __('api.warehouse_not_accessable');
    }

    public function getAddressNotFoundMessage()
    {
        return __('api.address_not_found_message');
    }

    public static function getBraintreeTransactionFailedMessage()
    {
        return __('api.braintree.transaction_failed');
    }

    public static function getNewAccountRegisteredMessage()
    {
        return __('info.new_account_registered');
    }

    public function getSuccessMessageSent()
    {
        return __('sms.success');
    }

    public static function getEasyPayOrderNotUniqueMessage()
    {
        return __('easypay.order_id_not_unique');
    }

    public static function getSpecificValidationFailedMessage($field)
    {
        return __('api.validation_failed').', '.$field.' is required';
    }

    public static function groupHasProducts()
    {
        return __('api.group_has_products');
    }
}
