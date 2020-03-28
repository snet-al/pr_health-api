<?php

namespace App\Helpers;

class FcmNotificationConstants
{
    const ORDERS = 'orders';
    const DAILY_MENU = 'dailyMenu';
    const GREENHOUSE = 'greenhouse';
    const TRANSFER_CREDITS = 'transferCredits';
    const PRODUCT_DETAILS = 'productDetails';
    const ORDER_DETAILS = 'orderDetails';
    const TRACKING = 'tracking';
    const SALE = 'sale';
    const ACCOUNT_CREDITED = 'accountCredited';

    const NOTIFICATION_MAPPING = [
        self::ORDERS => 1,
        self::DAILY_MENU => 2,
        self::GREENHOUSE => 3,
        self::TRANSFER_CREDITS => 4,
        self::PRODUCT_DETAILS => 5,
        self::ORDER_DETAILS => 6,
        self::TRACKING => 7,
        self::SALE => 8,
        self::ACCOUNT_CREDITED => 9,
    ];
}
/*
 * TODO: IF ANOTHER CONSTANT IS ADDED PLEASE UPDATE SEEDERS AND RUN THEM IN LIVE IN mobile_app_settings table.
 */
