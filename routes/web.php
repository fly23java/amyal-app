<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VehicleTypesController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\GoodsTypesController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\ContractDetailsController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ShipmentDeliveryDetailsController;

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
    return view('auth.login');
});

Route::get('/test', function () {
    return view('wey_bill.show');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::resource('country', CountryController::class);


Route::group([
     'prefix' => 'accounts',
 ], function () {
     Route::get('/', [AccountsController::class, 'index'])
          ->name('accounts.account.index');
     Route::get('/create', [AccountsController::class, 'create'])
          ->name('accounts.account.create');
     Route::get('/show/{account}',[AccountsController::class, 'show'])
          ->name('accounts.account.show');
     Route::get('/{account}/edit',[AccountsController::class, 'edit'])
          ->name('accounts.account.edit');
     Route::post('/', [AccountsController::class, 'store'])
          ->name('accounts.account.store');
     Route::put('account/{account}', [AccountsController::class, 'update'])
          ->name('accounts.account.update');
     Route::delete('/account/{account}',[AccountsController::class, 'destroy'])
          ->name('accounts.account.destroy');
 });

Route::group([
    'prefix' => 'vehicle_types',
], function () {
    Route::get('/', [VehicleTypesController::class, 'index'])
         ->name('vehicle_types.vehicle_type.index');
    Route::get('/create', [VehicleTypesController::class, 'create'])
         ->name('vehicle_types.vehicle_type.create');
    Route::get('/show/{vehicleType}',[VehicleTypesController::class, 'show'])
         ->name('vehicle_types.vehicle_type.show')->where('id', '[0-9]+');
    Route::get('/{vehicleType}/edit',[VehicleTypesController::class, 'edit'])
         ->name('vehicle_types.vehicle_type.edit')->where('id', '[0-9]+');
    Route::post('/', [VehicleTypesController::class, 'store'])
         ->name('vehicle_types.vehicle_type.store');
    Route::put('vehicle_type/{vehicleType}', [VehicleTypesController::class, 'update'])
         ->name('vehicle_types.vehicle_type.update')->where('id', '[0-9]+');
    Route::delete('/vehicle_type/{vehicleType}',[VehicleTypesController::class, 'destroy'])
         ->name('vehicle_types.vehicle_type.destroy')->where('id', '[0-9]+');
});


Route::group([
    'prefix' => 'vehicles',
], function () {
    Route::get('/', [VehiclesController::class, 'index'])
         ->name('vehicles.vehicle.index');
    Route::get('/create', [VehiclesController::class, 'create'])
         ->name('vehicles.vehicle.create');
    Route::get('/show/{vehicle}',[VehiclesController::class, 'show'])
         ->name('vehicles.vehicle.show');
    Route::get('/{vehicle}/edit',[VehiclesController::class, 'edit'])
         ->name('vehicles.vehicle.edit');
    Route::post('/', [VehiclesController::class, 'store'])
         ->name('vehicles.vehicle.store');
    Route::put('vehicle/{vehicle}', [VehiclesController::class, 'update'])
         ->name('vehicles.vehicle.update');
    Route::delete('/vehicle/{vehicle}',[VehiclesController::class, 'destroy'])
         ->name('vehicles.vehicle.destroy');
});

Route::group([
    'prefix' => 'drivers',
], function () {
    Route::get('/', [DriversController::class, 'index'])
         ->name('drivers.driver.index');
    Route::get('/create', [DriversController::class, 'create'])
         ->name('drivers.driver.create');
    Route::get('/show/{driver}',[DriversController::class, 'show'])
         ->name('drivers.driver.show');
    Route::get('/{driver}/edit',[DriversController::class, 'edit'])
         ->name('drivers.driver.edit');
    Route::post('/', [DriversController::class, 'store'])
         ->name('drivers.driver.store');
    Route::put('driver/{driver}', [DriversController::class, 'update'])
         ->name('drivers.driver.update');
    Route::delete('/driver/{driver}',[DriversController::class, 'destroy'])
         ->name('drivers.driver.destroy');
});

Route::group([
    'prefix' => 'countries',
], function () {
    Route::get('/', [CountriesController::class, 'index'])
         ->name('countries.country.index');
    Route::get('/create', [CountriesController::class, 'create'])
         ->name('countries.country.create');
    Route::get('/show/{country}',[CountriesController::class, 'show'])
         ->name('countries.country.show');
    Route::get('/{country}/edit',[CountriesController::class, 'edit'])
         ->name('countries.country.edit');
    Route::post('/', [CountriesController::class, 'store'])
         ->name('countries.country.store');
    Route::put('country/{country}', [CountriesController::class, 'update'])
         ->name('countries.country.update');
    Route::delete('/country/{country}',[CountriesController::class, 'destroy'])
         ->name('countries.country.destroy');
});

Route::group([
    'prefix' => 'regions',
], function () {
    Route::get('/', [RegionsController::class, 'index'])
         ->name('regions.region.index');
    Route::get('/create', [RegionsController::class, 'create'])
         ->name('regions.region.create');
    Route::get('/show/{region}',[RegionsController::class, 'show'])
         ->name('regions.region.show');
    Route::get('/{region}/edit',[RegionsController::class, 'edit'])
         ->name('regions.region.edit');
    Route::post('/', [RegionsController::class, 'store'])
         ->name('regions.region.store');
    Route::put('region/{region}', [RegionsController::class, 'update'])
         ->name('regions.region.update');
    Route::delete('/region/{region}',[RegionsController::class, 'destroy'])
         ->name('regions.region.destroy');
});

Route::group([
    'prefix' => 'cities',
], function () {
    Route::get('/', [CitiesController::class, 'index'])
         ->name('cities.city.index');
    Route::get('/create', [CitiesController::class, 'create'])
         ->name('cities.city.create');
    Route::get('/show/{city}',[CitiesController::class, 'show'])
         ->name('cities.city.show');
    Route::get('/{city}/edit',[CitiesController::class, 'edit'])
         ->name('cities.city.edit');
    Route::post('/', [CitiesController::class, 'store'])
         ->name('cities.city.store');
    Route::put('city/{city}', [CitiesController::class, 'update'])
         ->name('cities.city.update');
    Route::delete('/city/{city}',[CitiesController::class, 'destroy'])
         ->name('cities.city.destroy');
});

Route::group([
     'prefix' => 'units',
 ], function () {
     Route::get('/', [UnitsController::class, 'index'])
          ->name('units.unit.index');
     Route::get('/create', [UnitsController::class, 'create'])
          ->name('units.unit.create');
     Route::get('/show/{unit}',[UnitsController::class, 'show'])
          ->name('units.unit.show');
     Route::get('/{unit}/edit',[UnitsController::class, 'edit'])
          ->name('units.unit.edit');
     Route::post('/', [UnitsController::class, 'store'])
          ->name('units.unit.store');
     Route::put('unit/{unit}', [UnitsController::class, 'update'])
          ->name('units.unit.update');
     Route::delete('/unit/{unit}',[UnitsController::class, 'destroy'])
          ->name('units.unit.destroy');
 });
 
 Route::group([
     'prefix' => 'goods_types',
 ], function () {
     Route::get('/', [GoodsTypesController::class, 'index'])
          ->name('goods_types.goods_type.index');
     Route::get('/create', [GoodsTypesController::class, 'create'])
          ->name('goods_types.goods_type.create');
     Route::get('/show/{goodsType}',[GoodsTypesController::class, 'show'])
          ->name('goods_types.goods_type.show');
     Route::get('/{goodsType}/edit',[GoodsTypesController::class, 'edit'])
          ->name('goods_types.goods_type.edit');
     Route::post('/', [GoodsTypesController::class, 'store'])
          ->name('goods_types.goods_type.store');
     Route::put('goods_type/{goodsType}', [GoodsTypesController::class, 'update'])
          ->name('goods_types.goods_type.update');
     Route::delete('/goods_type/{goodsType}',[GoodsTypesController::class, 'destroy'])
          ->name('goods_types.goods_type.destroy');
 });
 
 Route::group([
     'prefix' => 'goods',
 ], function () {
     Route::get('/', [GoodsController::class, 'index'])
          ->name('goods.goods.index');
     Route::get('/create', [GoodsController::class, 'create'])
          ->name('goods.goods.create');
     Route::get('/show/{goods}',[GoodsController::class, 'show'])
          ->name('goods.goods.show');
     Route::get('/{goods}/edit',[GoodsController::class, 'edit'])
          ->name('goods.goods.edit');
     Route::post('/', [GoodsController::class, 'store'])
          ->name('goods.goods.store');
     Route::put('goods/{goods}', [GoodsController::class, 'update'])
          ->name('goods.goods.update');
     Route::delete('/goods/{goods}',[GoodsController::class, 'destroy'])
          ->name('goods.goods.destroy');
 });
 
 Route::group([
     'prefix' => 'users',
 ], function () {
     Route::get('/', [UsersController::class, 'index'])
          ->name('users.user.index');
     Route::get('/create', [UsersController::class, 'create'])
          ->name('users.user.create');
     Route::get('/show/{user}',[UsersController::class, 'show'])
          ->name('users.user.show');
     Route::get('/{user}/edit',[UsersController::class, 'edit'])
          ->name('users.user.edit');
     Route::post('/', [UsersController::class, 'store'])
          ->name('users.user.store');
     Route::put('user/{user}', [UsersController::class, 'update'])
          ->name('users.user.update');
     Route::delete('/user/{user}',[UsersController::class, 'destroy'])
          ->name('users.user.destroy');
 });

 Route::group([
     'prefix' => 'contracts',
 ], function () {
     Route::get('/', [ContractsController::class, 'index'])
          ->name('contracts.contract.index');
     Route::get('/create', [ContractsController::class, 'create'])
          ->name('contracts.contract.create');
     Route::get('/show/{contract}',[ContractsController::class, 'show'])
          ->name('contracts.contract.show');
     Route::get('/{contract}/edit',[ContractsController::class, 'edit'])
          ->name('contracts.contract.edit');
     Route::post('/', [ContractsController::class, 'store'])
          ->name('contracts.contract.store');
     Route::put('contract/{contract}', [ContractsController::class, 'update'])
          ->name('contracts.contract.update');
     Route::delete('/contract/{contract}',[ContractsController::class, 'destroy'])
          ->name('contracts.contract.destroy');
 });
 
 Route::group([
     'prefix' => 'contract_details',
 ], function () {
     Route::get('/', [ContractDetailsController::class, 'index'])
          ->name('contract_details.contract_detail.index');
     Route::get('/create', [ContractDetailsController::class, 'create'])
          ->name('contract_details.contract_detail.create');
     Route::get('/show/{contractDetail}',[ContractDetailsController::class, 'show'])
          ->name('contract_details.contract_detail.show');
     Route::get('/{contractDetail}/edit',[ContractDetailsController::class, 'edit'])
          ->name('contract_details.contract_detail.edit');
     Route::post('/', [ContractDetailsController::class, 'store'])
          ->name('contract_details.contract_detail.store');
     Route::put('contract_detail/{contractDetail}', [ContractDetailsController::class, 'update'])
          ->name('contract_details.contract_detail.update');
     Route::delete('/contract_detail/{contractDetail}',[ContractDetailsController::class, 'destroy'])
          ->name('contract_details.contract_detail.destroy');
 });
 
 Route::group([
     'prefix' => 'statuses',
 ], function () {
     Route::get('/', [StatusesController::class, 'index'])
          ->name('statuses.status.index');
     Route::get('/create', [StatusesController::class, 'create'])
          ->name('statuses.status.create');
     Route::get('/show/{status}',[StatusesController::class, 'show'])
          ->name('statuses.status.show');
     Route::get('/{status}/edit',[StatusesController::class, 'edit'])
          ->name('statuses.status.edit');
     Route::post('/', [StatusesController::class, 'store'])
          ->name('statuses.status.store');
     Route::put('status/{status}', [StatusesController::class, 'update'])
          ->name('statuses.status.update');
     Route::delete('/status/{status}',[StatusesController::class, 'destroy'])
          ->name('statuses.status.destroy');
 });
 
 Route::group([
     'prefix' => 'payment_methods',
 ], function () {
     Route::get('/', [PaymentMethodsController::class, 'index'])
          ->name('payment_methods.payment_method.index');
     Route::get('/create', [PaymentMethodsController::class, 'create'])
          ->name('payment_methods.payment_method.create');
     Route::get('/show/{paymentMethod}',[PaymentMethodsController::class, 'show'])
          ->name('payment_methods.payment_method.show');
     Route::get('/{paymentMethod}/edit',[PaymentMethodsController::class, 'edit'])
          ->name('payment_methods.payment_method.edit');
     Route::post('/', [PaymentMethodsController::class, 'store'])
          ->name('payment_methods.payment_method.store');
     Route::put('payment_method/{paymentMethod}', [PaymentMethodsController::class, 'update'])
          ->name('payment_methods.payment_method.update');
     Route::delete('/payment_method/{paymentMethod}',[PaymentMethodsController::class, 'destroy'])
          ->name('payment_methods.payment_method.destroy');
 });
 
 Route::group([
     'prefix' => 'shipments',
 ], function () {
     Route::get('/', [ShipmentsController::class, 'index'])
          ->name('shipments.shipment.index');
     Route::get('/create', [ShipmentsController::class, 'create'])
          ->name('shipments.shipment.create');
     Route::get('/show/{shipment}',[ShipmentsController::class, 'show'])
          ->name('shipments.shipment.show');
     Route::get('/{shipment}/edit',[ShipmentsController::class, 'edit'])
          ->name('shipments.shipment.edit');
     Route::post('/', [ShipmentsController::class, 'store'])
          ->name('shipments.shipment.store');
     Route::put('shipment/{shipment}', [ShipmentsController::class, 'update'])
          ->name('shipments.shipment.update');
     Route::delete('/shipment/{shipment}',[ShipmentsController::class, 'destroy'])
          ->name('shipments.shipment.destroy');
      Route::get('/shipment/getPrice',[ShipmentsController::class, 'getPrice'])
          ->name('shipments.shipment.getPrice');
      Route::get('/shipment/getVehcile',[ShipmentsController::class, 'getVehcile'])
          ->name('shipments.shipment.getVehcile');
      Route::get('/shipment/getCarrierPrice',[ShipmentsController::class, 'getCarrierPrice'])
          ->name('shipments.shipment.getCarrierPrice');
      Route::get('/shipment/getDatahipmentdetails',[ShipmentsController::class, 'getDatahipmentdetails'])
          ->name('shipments.shipment.getDatahipmentdetails');
      Route::post('/shipment/getAddVehcileToShipment',[ShipmentsController::class, 'getAddVehcileToShipment'])
          ->name('shipments.shipment.getAddVehcileToShipment');
      Route::get('/shipment/shipmentDetails',[ShipmentsController::class, 'shipmentDetails'])
          ->name('shipments.shipment.shipmentDetails');
      Route::get('/shipment/pdf',[ShipmentsController::class, 'pdf'])
          ->name('shipments.shipment.pdf');
 });


 Route::group([
    'prefix' => 'shipment_delivery_details',
], function () {
    Route::get('/', [ShipmentDeliveryDetailsController::class, 'index'])
         ->name('shipment_delivery_details.shipment_delivery_detail.index');
    Route::get('/create', [ShipmentDeliveryDetailsController::class, 'create'])
         ->name('shipment_delivery_details.shipment_delivery_detail.create');
    Route::get('/show/{shipmentDeliveryDetail}',[ShipmentDeliveryDetailsController::class, 'show'])
         ->name('shipment_delivery_details.shipment_delivery_detail.show');
    Route::get('/{shipmentDeliveryDetail}/edit',[ShipmentDeliveryDetailsController::class, 'edit'])
         ->name('shipment_delivery_details.shipment_delivery_detail.edit');
    Route::post('/', [ShipmentDeliveryDetailsController::class, 'store'])
         ->name('shipment_delivery_details.shipment_delivery_detail.store');
    Route::put('shipment_delivery_detail/{shipmentDeliveryDetail}', [ShipmentDeliveryDetailsController::class, 'update'])
         ->name('shipment_delivery_details.shipment_delivery_detail.update');
    Route::delete('/shipment_delivery_detail/{shipmentDeliveryDetail}',[ShipmentDeliveryDetailsController::class, 'destroy'])
         ->name('shipment_delivery_details.shipment_delivery_detail.destroy');
});


