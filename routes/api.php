<?php

Route::post('oauth/token', 'Auth\AccessTokenController@issueToken');
Route::get('/global-configuration', 'Api\GlobalConfigurationsController@index');

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'LoginController@register');
    Route::get('/signup/activate/{token}', 'RegisterController@signupActivate');

    Route::post('/{user}/change-email', 'RegisterController@changeEmail');
    Route::post('/account-confirmation', 'RegisterController@confirm');
    Route::post('/resend-confirmation-code', 'RegisterController@sendConfirmationCode');
    Route::post('/resend-confirmation-email', 'RegisterController@resendConfirmationEmail');
    Route::get('/terms-conditions', 'TermsConditionsController@index');
    Route::post('/reset-password', 'UsersController@resetPassword');

    /* Application Version */
    Route::get('application-version', 'ApplicationVersionsController@index');

    /* Products */
    Route::get('/products/', 'ProductsController@index');
    Route::get('/products/{id}', 'ProductsController@show');
    Route::get('/products/{id}/similar', 'ProductsController@getSimilarProducts');
    Route::get('/products/{id}/same-group', 'ProductsController@sameGroup');
    // best deals
    Route::get('/best-deals/', 'ProductsController@bestDeals');

    /* Product Groups */
    Route::get('/product-groups', 'ProductGroupsController@index');

    /* Business Sectors */
    Route::get('/business-sectors', 'BusinessSectorsController@index');
});

Route::group(['middleware' => ['auth:api', 'active_user'], 'namespace' => 'Api'], function () {
    Route::post('/logout', 'LogoutController@delete');

    /* Braintree */
    Route::get('/generate-braintree-token', 'BrainTreeController@getBraintreeToken')->name('generate_braintree_token');
    Route::post('/generate-braintree-transaction', 'BrainTreeController@createBraintreeTransaction')->name('generate_braintree_transaction');
    /* Easypay Routes */
    Route::post('/generate-easypay-transaction', 'EasypayController@createTransaction')->name('generate_easypay_transaction');

    /*Clients*/
    Route::post('/clients/update', 'ClientsController@update')->middleware(['client.validation']);
    Route::post('/clients/confirm-mobile-number', 'ClientsController@confirmMobileNumber');
    Route::get('/clients/send-mobile-confirmation-sms', 'ClientsController@sendMobileConfirmationSms');
    Route::get('/clients/by-uuid', 'ClientsController@getByUuid');

    /* Users */
    Route::get('/users/send-sms-confirm-phone', 'PhoneConfirmationController@edit');
    Route::post('/users/confirm-phone', 'PhoneConfirmationController@update');
    Route::put('/users/change-password', 'UsersController@changePassword');

    Route::get('/users/get-auth-user', 'UsersController@getAuthUser');
    Route::get('/users', 'UsersController@index');
    Route::get('/users/favorite-products', 'UsersController@getFavorites');
    Route::get('/users/points-credits-coupons', 'OrdersController@getPointsCreditsCoupons');
    Route::get('/users/{userId}', 'UsersController@show');

    Route::put('/users/{user}', 'UsersController@update');
    Route::put('/users/{user}/change-status', 'UsersController@changeStatus');
    Route::post('/users/{userId}/add-photo', 'UsersController@addPhoto');

    /* Map Addresses */
    Route::get('/map-addresses', 'MapAddressesController@index');

    /* Addresses */
    Route::get('/addresses', 'AddressesController@index');
    Route::post('/addresses', 'AddressesController@store');
    Route::put('/addresses/{addressId}/default', 'AddressesController@makeDefault');
    Route::put('/addresses/{addressId}', 'AddressesController@update');
    Route::delete('/addresses/{addressId}', 'AddressesController@destroy');
});
