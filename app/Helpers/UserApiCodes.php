<?php

namespace App\Helpers;

class UserApiCodes
{
    const USERNAME_OR_PASSWORD_INVALID = 100001;
    const USER_NOT_FOUND = 100002;
    const USER_EXISTS = 100003;
    const USER_IS_INACTIVE = 100004;
    const USER_CAN_NOT_BE_MODIFIED = 100005;
    const EMAIL_EXISTS = 100006;
    const PHONE_NUMBER_EXISTS = 100007;
    const ACCOUNT_NOT_CONFIRMED = 100008;
    const UNCONFIRMED_ACCOUNT_EXISTS = 100009;
    const USER_REGISTERED_WITH_GMAIL = 1000010;
    const USER_REGISTERED_WITH_FACEBOOK = 1000011;

    public static function username_or_password_invalid()
    {
        return __('api.users.username_or_password_invalid');
    }

    public static function user_exists()
    {
        return __('api.users.user_exists');
    }

    public static function user_not_found()
    {
        return __('api.user.user_not_found');
    }

    public static function user_is_inactive()
    {
        return __('api.user.user_is_inactive');
    }

    public static function user_can_not_be_modified()
    {
        return __('api.user.user_can_not_be_modified');
    }

    public static function email_exists()
    {
        return __('api.user.email_exists');
    }

    public static function phone_number_exists()
    {
        return __('api.user.phone_number_exists');
    }

    public static function account_not_confirmed()
    {
        return __('api.user.account_not_confirmed');
    }

    public static function unconfirmed_account_exists()
    {
        return __('api.user.unconfirmed_account_exists');
    }
}
