<?php

/**
 * Flash notice.
 *
 * @param string $message
 * @param string $type
 *
 * @return string
 */
function flash($message, $type = 'success')
{
    $flash = app(App\Helpers\Flash::class);

    return $flash->message($message, $type);
}

/**
 * Set active class per menu.
 *
 * @param string $path
 * @param string $active
 *
 * @return string
 */
function setActive($path, $active = 'active')
{
    return request()->is('admin/' . $path) ? "class=$active" : '';
}

/**
 * @param array  $paths
 * @param string $active
 *
 * @return string
 */
function setActiveSubmenu(array $paths, $active = 'active')
{
    foreach ($paths as $path) {
        if (request()->is('admin/' . $path . '*')) {
            return "$active";
        }
    }

    return '';
}

function setRequestUuid($uuid)
{
    return (isset($uuid) && strlen($uuid) > 0) ? $uuid : Uuid::generate(4)->string;
}

function generateUuid()
{
    return Uuid::generate(4)->string;
}

/**
 * @param int $isActive
 *
 * @return string
 */
function showActiveState($isActive)
{
    $color = 'danger';
    $status = 'Inactive';

    if ($isActive) {
        $color = 'success';
        $status = 'Active';
    }

    return "<span class='label label-{$color} center bigger-110'>{$status}</span>";
}

function getActiveState($state)
{
    return (bool) $state ? 'Active' : 'Inactive';
}

/**
 * @param string $route
 *
 * @return string
 */
function showGeneralButton($route, $title = '', $icon = '')
{
    $form = app(App\Forms\Form::class);

    return $form::showGeneralButton($route, $title, $icon);
}

/**
 * Action status buttons.
 *
 * @param string $editRoute
 * @param string $resource
 * @param string $resourceId
 * @param string $isActive
 *
 * @return string
 */
function actionStatusButtons($editRoute, $resource, $resourceId, $isActive)
{
    $form = app(App\Forms\Form::class);

    return $form::actionStatusButtons($editRoute, $resource, $resourceId, $isActive);
}

/**
 * @param string $route
 *
 * @return string
 */
function showPriceButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showPriceButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showStockIncomeButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showStockIncomeButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showStockTransferButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showStockTransferButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showSalesButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showSalesButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showWarehouseButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showWarehouseButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showInvoiceButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showInvoiceButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showEditButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showEditButton($route);
}

/**
 * @param string $route
 *
 * @return string
 */
function showDeleteButton($route)
{
    $form = app(App\Forms\Form::class);

    return $form::showDeleteButton($route);
}

/**
 * @param string $userId
 *
 * @return string
 */
function rolesButton($userId)
{
    $form = app(App\Forms\UserForm::class);

    return $form::rolesButton($userId);
}

/**
 * @param string $userId
 *
 * @return string
 */
function changePasswordButton($userId)
{
    $form = app(App\Forms\UserForm::class);

    return $form::changePasswordButton($userId);
}

/**
 * @param string $roleId
 *
 * @return string
 */
function permissionsButton($roleId)
{
    $form = app(App\Forms\RoleForm::class);

    return $form::permissionsButton($roleId);
}

/**
 * Check if action is 'add'.
 *
 * @param string $action
 *
 * @return bool
 */
function isAddAction($action)
{
    return $action === 'add';
}

/**
 * Check if action is 'remove'.
 *
 * @param $action
 *
 * @return bool
 */
function isRemoveAction($action)
{
    return $action === 'remove';
}

/**
 * Create date from format.
 *
 * @param $date
 * @param string $format
 *
 * @return int|static
 */
function createDateFromFormat($date, $format = 'd/m/Y')
{
    return $date !== '' ? (Carbon\Carbon::createFromFormat($format, $date)->startOfDay())->toDateString() : 0;
}

/**
 * @param string $date
 * @param string $format
 *
 * @return string
 */
function createDateFromDotFormat($date, $format = 'd.m.Y')
{
    return createDateFromFormat($date, $format);
}

/**
 * Create datetime from format.
 *
 * @param $datetime
 * @param string $format
 *
 * @return int|static
 */
function createDateTimeFromFormat($datetime, $format = 'd/m/Y H:i')
{
    return $datetime !== '' ? Carbon\Carbon::createFromFormat($format, $datetime) : 0;
}

/**
 * Get formatted date.
 *
 * @param $date
 *
 * @return bool|string
 */
function formattedDate($date)
{
    return ($date === '0000-00-00') ? '' : date('d/m/Y', strtotime($date));
}

function money($amount)
{
    return number_format($amount, 2, '.', ',');
}

/**
 * @param string $text
 *
 * @return string
 */
function stripSpaces($text)
{
    return preg_replace('/\s+/', '', $text);
}
