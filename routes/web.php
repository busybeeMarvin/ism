<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'DashboardController@index')->name('home');
    Route::post('/home/instock', 'DashboardController@inStock')->name('home.instock');
    Route::post('/home/outofstock', 'DashboardController@outOfStock')->name('home.outofstock');
    Route::post('/home/returned', 'DashboardController@returnedSO')->name('home.returned');
    Route::post('/home/po', 'DashboardController@orderedPO')->name('home.po');
    Route::post('/home/so', 'DashboardController@quoteSO')->name('home.so');
    Route::post('/home/total/so', 'DashboardController@totalSO')->name('home.total.so');
    Route::post('/home/total/po', 'DashboardController@totalPO')->name('home.total.po');
    Route::get('/home/assets/printable', 'DashboardController@assetsPrintable')->name('home.assets.printable');
    Route::get('/home/po/printable/{start}/{end}', 'DashboardController@poTotalPrintable')->name('home.po.printable');
    Route::get('/home/so/printable/{start}/{end}', 'DashboardController@soTotalPrintable')->name('home.so.printable');
    Route::get('/home/qtn/printable/{start}/{end}', 'DashboardController@qtnTotalPrintable')->name('home.qtn.printable');

    Route::get('/purchase', 'PurchaseInfoController@index')->name('purchase')->middleware('can:purchaseorder');
    Route::get('/purchase/create', 'PurchaseInfoController@create')->name('purchase.create')->middleware('can:purchaseordercreate');
    Route::get('/purchase/view/{id}', 'PurchaseInfoController@show')->name('purchase.view')->middleware('can:purchaseorderretrieve');
    Route::get('/purchase/detail/{id}', 'PurchaseInfoController@show')->name('purchase.detail')->middleware('can:purchaseorderupdate');
    Route::post('/purchase/destroy', 'PurchaseInfoController@destroy')->name('purchase.destroy')->middleware('can:purchaseorderdelete');
    Route::post('/purchase/table', 'PurchaseInfoController@table')->name('purchase.table');
    Route::post('/purchase/update', 'PurchaseInfoController@update')->name('purchase.update');
    Route::post('/purchase/store', 'PurchaseInfoController@store')->name('purchase.store');
    Route::post('/purchase/status/update', 'PurchaseInfoController@updateStatus')->name('purchase.status.update');
    Route::get('/purchase/print/{id}', 'PurchaseInfoController@printable')->name('purchase.print');
    Route::get('/purchase/preview/{id}', 'PurchaseInfoController@previewPO')->name('purchase.preview');
    Route::post('/purchase/payment/status/update', 'PurchaseInfoController@updatePaymentStatus')->name('purchase.payment.status.update');

    Route::get('/sales', 'SalesOrderController@index')->name('sales')->middleware('can:sales_order');
    Route::get('/sales/create', 'SalesOrderController@create')->name('sales.create')->middleware('can:salesordercreate');
    Route::get('/sales/view/{id}', 'SalesOrderController@show')->name('sales.view')->middleware('can:salesorderretrieve');
    Route::get('/sales/detail/{id}', 'SalesOrderController@show')->name('sales.detail')->middleware('can:salesorderupdate');
    Route::post('/sales/destroy', 'SalesOrderController@destroy')->name('sales.destroy')->middleware('can:salesorderdelete');
    Route::post('/sales/table', 'SalesOrderController@table')->name('sales.table');
    Route::post('/sales/update', 'SalesOrderController@update')->name('sales.update');
    Route::post('/sales/store', 'SalesOrderController@store')->name('sales.store');
    Route::post('/sales/status/update', 'SalesOrderController@updateStatus')->name('sales.status.update')->middleware('can:salesstatusupdate');
    Route::get('/sales/print/{id}', 'SalesOrderController@printable')->name('sales.print');
    Route::get('/sales/quote/{id}', 'SalesOrderController@quote')->name('sales.quote');
    Route::get('/sales/deliver/{id}', 'SalesOrderController@deliver')->name('sales.deliver');
    Route::get('/sales/preview/{id}', 'SalesOrderController@previewSO')->name('sales.preview');
    Route::post('/sales/shipped/list', 'SalesOrderController@getListShipped')->name('sales.shipped.list');

    Route::get('/vendors', 'VendorController@index')->name('vendor')->middleware('can:vendors');
    Route::get('/vendors/create', 'VendorController@create')->name('vendor.create')->middleware('can:vendorscreate');
    Route::get('/vendors/view/{id}', 'VendorController@show')->name('vendor.view')->middleware('can:vendorsretrieve');
    Route::get('/vendors/detail/{id}', 'VendorController@show')->name('vendor.detail')->middleware('can:vendorsupdate');
    Route::post('/vendors/destroy', 'VendorController@destroy')->name('vendor.destroy')->middleware('can:vendorsdelete');
    Route::get('/vendors/printable', 'VendorController@printable')->name('vendor.printable');
    Route::post('/vendors/table', 'VendorController@table')->name('vendor.table');
    Route::post('/vendors/list', 'VendorController@getList')->name('vendor.list');
    Route::post('/vendors/update', 'VendorController@update')->name('vendor.update');
    Route::post('/vendors/store', 'VendorController@store')->name('vendor.store');

//    Route::get('/inquiry', 'InquiryController@index')->name('inquiry');
//    Route::post('/inquiry/table', 'InquiryController@table')->name('inquiry.table');
//    Route::post('/inquiry/destroy', 'InquiryController@destroy')->name('inquiry.destroy');

    Route::get('/customer', 'CustomerController@index')->name('customer')->middleware('can:customer');
    Route::get('/customer/create', 'CustomerController@create')->name('customer.create')->middleware('can:customercreate');
    Route::get('/customer/view/{id}', 'CustomerController@show')->name('customer.view')->middleware('can:customerretrieve');
    Route::get('/customer/detail/{id}', 'CustomerController@show')->name('customer.detail')->middleware('can:customerupdate');
    Route::post('/customer/destroy', 'CustomerController@destroy')->name('customer.destroy')->middleware('can:customerdelete');
    Route::get('/customer/printable', 'CustomerController@printable')->name('customer.printable');
    Route::post('/customer/table', 'CustomerController@table')->name('customer.table');
    Route::post('/customer/list', 'CustomerController@getList')->name('customer.list');
    Route::post('/customer/update', 'CustomerController@update')->name('customer.update');
    Route::post('/customer/store', 'CustomerController@store')->name('customer.store');

    Route::get('/products', 'ProductController@index')->name('products')->middleware('can:products');
    Route::get('/products/create', 'ProductController@create')->name('product.create')->middleware('can:productscreate');
    Route::get('/product/view/{id}', 'ProductController@show')->name('product.view')->middleware('can:productsretrieve');
    Route::get('/product/detail/{id}', 'ProductController@show')->name('product.detail')->middleware('can:productsupdate');
    Route::post('/product/destroy', 'ProductController@destroy')->name('product.destroy')->middleware('can:productsdelete');
    Route::post('/product/find', 'ProductController@findProduct')->name('product.find')->middleware('can:productsupdate');
    Route::post('/products/table', 'ProductController@table')->name('product.table');
    Route::post('/product/list', 'ProductController@getList')->name('product.list');
    Route::post('/product/store', 'ProductController@store')->name('product.store');
    Route::post('/product/update', 'ProductController@update')->name('product.update');
    Route::post('/product/image/upload', 'ProductController@imageUpload')->name('product.image.upload');
    Route::post('/product/so/list', 'ProductController@getSOList')->name('product.so.list');

    Route::post('/category/list', 'CategoryController@getList')->name('category.list');
    Route::post('/category/destroy', 'CategoryController@destroy')->name('category.delete')->middleware('can:productsupdate');
    Route::post('/category/store', 'CategoryController@store')->name('category.store')->middleware('can:productsupdate');

    Route::get('/supply', 'SupplyController@index')->name('supply')->middleware('can:supplies');
    Route::post('/supply/table', 'SupplyController@table')->name('supply.table');
    Route::post('/supply/po', 'SupplyController@getPOLinks')->name('supply.po.links');
    Route::post('/supply/so', 'SupplyController@getSOLinks')->name('supply.so.links');
    Route::post('/supply/update/quantity', 'SupplyController@updateQuantity')->name('supply.update.quantity');

    Route::get('/users', 'UserController@index')->name('users')->middleware('can:useraccounts');
    Route::get('/user/detail/{id}', 'UserController@show')->name('user.detail')->middleware('can:useraccountsupdate');
    Route::get('/user/create', 'UserController@create')->name('user.create')->middleware('can:useraccountscreate');
    Route::post('/user/destroy', 'UserController@destroy')->name('user.destroy')->middleware('can:useraccountsdelete');
    Route::post('/user/change/pass', 'UserController@changePass')->name('user.change.pass')->middleware('can:useraccountschangepass');
    Route::post('/user/assign', 'UserController@assignUserRole')->name('user.assign')->middleware('can:userassign');
    Route::post('/users/table', 'UserController@table')->name('user.table');
    Route::post('/user/store', 'UserController@store')->name('user.store');
    Route::post('/user/update', 'UserController@update')->name('user.update');
    Route::post('/users/logo/upload', 'UserController@logoUpload')->name('user.logo.upload');
    Route::post('/user/role', 'UserController@getUserRole')->name('user.role');

    Route::get('/role', 'SecurityController@roles')->name('role')->middleware('can:securityview');
    Route::get('/role/create', 'SecurityController@create')->name('role.create')->middleware('can:securitycreate');
    Route::get('/role/detail/{id}', 'SecurityController@show')->name('role.detail')->middleware('can:securityupdate');
    Route::post('/role/destroy', 'SecurityController@destroy')->name('role.destroy')->middleware('can:securitydelete');
    Route::post('/role/table', 'SecurityController@table')->name('role.table');
    Route::post('/role/store', 'SecurityController@store')->name('role.store');
    Route::post('/role/abilities', 'SecurityController@update')->name('role.abilities');

    Route::get('/orderform', 'OrderFormController@index')->name('orderform')->middleware('can:orderform');
    Route::get('/orderform/view/{id}', 'OrderFormController@show')->name('orderform.view')->middleware('can:orderformretrieve');
    Route::get('/orderform/create', 'OrderFormController@create')->name('orderform.create')->middleware('can:orderformcreate');
    Route::post('/orderform/destroy', 'OrderFormController@destroy')->name('orderform.destroy')->middleware('can:orderformdelete');
    Route::post('/orderform/table', 'OrderFormController@table')->name('orderform.table');
    Route::post('/orderform/store', 'OrderFormController@store')->name('orderform.store');

    Route::get('/preference', 'PreferenceController@index')->name('preference')->middleware('can:preference');
    Route::post('/preference/update', 'PreferenceController@update')->name('preference.update');

    Route::get('/return', 'ProductReturnController@index')->name('return')->middleware('can:productreturn');
    Route::post('/return/table', 'ProductReturnController@table')->name('return.table');
    Route::get('/return/create', 'ProductReturnController@create')->name('return.create')->middleware('can:productreturncreate');
    Route::post('/return/destroy', 'ProductReturnController@destroy')->name('return.destroy')->middleware('can:productreturndelete');
    Route::post('/return/store', 'ProductReturnController@store')->name('return.store');
    Route::get('/return/view/{id}', 'ProductReturnController@show')->name('return.view');

    Route::get('/pricelist', 'PriceListController@index')->name('pricelist')->middleware('can:pricelist');
    Route::post('/pricelist/upload', 'PriceListController@upload')->name('pricelist.upload')->middleware('can:pricelistupload');
    Route::post('/pricelist/destroy', 'PriceListController@destroy')->name('pricelist.destroy')->middleware('can:pricelistdestroy');
    Route::get('/download/pricelist/{id}', 'PriceListController@download')->name('pricelist.download');
    Route::post('/pricelist/table', 'PriceListController@table')->name('pricelist.table');
    Route::post('/pricelist/destroy', 'PriceListController@destroy')->name('pricelist.destroy')->middleware('can:orderformdelete');
});