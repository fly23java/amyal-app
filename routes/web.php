<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    VehicleTypesController,
    VehiclesController,
    DriversController,
    CountriesController,
    RegionsController,
    CitiesController,
    AccountController,
    UnitsController,
    GoodsTypesController,
    GoodsController,
    UsersController,
    ContractsController,
    ContractDetailsController,
    StatusesController,
    PaymentMethodsController,
    ShipmentsController,
    AccountsController,
    ShipmentDeliveryDetailsController,
    PrintWaybillController,
    ReturnPricesController,
    RetrunShipmentInTabsByStatusController,
    VehicleGoodsExtractorController,
    PricesController,
    PriceDetailsController,
    ShipmentNotActiveController,
    ShipmentsCompletedController,
    ReportShipmentController,
    UpdateSelectedFieldsController,
    HomeController
};

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

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// Authentication routes
Auth::routes(['verify' => true]);

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // API routes for AJAX requests
    Route::prefix('api')->name('api.')->group(function () {
        // Shipments API
        Route::prefix('shipments')->name('shipments.')->group(function () {
            Route::get('/statistics', [ShipmentsController::class, 'statistics'])->name('statistics');
            Route::get('/export', [ShipmentsController::class, 'export'])->name('export');
            Route::post('/bulk-update', [ShipmentsController::class, 'bulkUpdate'])->name('bulk-update');
            Route::delete('/bulk-delete', [ShipmentsController::class, 'bulkDelete'])->name('bulk-delete');
        });

        // Users API
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/statistics', [UsersController::class, 'statistics'])->name('statistics');
            Route::get('/online', [UsersController::class, 'online'])->name('online');
        });

        // Reports API
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/shipments', [ReportShipmentController::class, 'shipments'])->name('shipments');
            Route::get('/financial', [ReportShipmentController::class, 'financial'])->name('financial');
            Route::get('/performance', [ReportShipmentController::class, 'performance'])->name('performance');
        });
    });

    // Main application routes
    Route::middleware(['permission:view_shipments'])->group(function () {
        
        // Shipments routes
        Route::resource('shipments', ShipmentsController::class)
             ->names([
                 'index' => 'shipments.index',
                 'create' => 'shipments.create',
                 'store' => 'shipments.store',
                 'show' => 'shipments.show',
                 'edit' => 'shipments.edit',
                 'update' => 'shipments.update',
                 'destroy' => 'shipments.destroy'
             ])
             ->except(['destroy'])
             ->middleware([
                 'create' => 'permission:create_shipments',
                 'store' => 'permission:create_shipments',
                 'edit' => 'permission:edit_shipments',
                 'update' => 'permission:edit_shipments'
             ]);

        // Shipment deletion (separate for permission control)
        Route::delete('/shipments/{shipment}', [ShipmentsController::class, 'destroy'])
             ->name('shipments.destroy')
             ->middleware('permission:delete_shipments');

        // Shipment delivery details
        Route::resource('shipment-delivery-details', ShipmentDeliveryDetailsController::class)
             ->names([
                 'index' => 'shipment-delivery-details.index',
                 'create' => 'shipment-delivery-details.create',
                 'store' => 'shipment-delivery-details.store',
                 'show' => 'shipment-delivery-details.show',
                 'edit' => 'shipment-delivery-details.edit',
                 'update' => 'shipment-delivery-details.update',
                 'destroy' => 'shipment-delivery-details.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_shipments',
                 'store' => 'permission:create_shipments',
                 'edit' => 'permission:edit_shipments',
                 'update' => 'permission:edit_shipments',
                 'destroy' => 'permission:delete_shipments'
             ]);

        // Shipment status management
        Route::prefix('shipments/{shipment}')->name('shipments.')->group(function () {
            Route::patch('/status', [ShipmentsController::class, 'updateStatus'])->name('update-status');
            Route::patch('/deliver', [ShipmentsController::class, 'markAsDelivered'])->name('deliver');
            Route::post('/assign-vehicle', [ShipmentsController::class, 'assignVehicle'])->name('assign-vehicle');
        })->middleware('permission:edit_shipments');

        // Shipment reports
        Route::prefix('shipments')->name('shipments.')->group(function () {
            Route::get('/completed', [ShipmentsCompletedController::class, 'index'])->name('completed');
            Route::get('/not-active', [ShipmentNotActiveController::class, 'index'])->name('not-active');
            Route::get('/overdue', [ShipmentsController::class, 'overdue'])->name('overdue');
        });
    });

    // Users management (admin only)
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::resource('users', UsersController::class)
             ->names([
                 'index' => 'users.index',
                 'create' => 'users.create',
                 'store' => 'users.store',
                 'show' => 'users.show',
                 'edit' => 'users.edit',
                 'update' => 'users.update',
                 'destroy' => 'users.destroy'
             ]);

        // User profile management
        Route::prefix('users/{user}')->name('users.')->group(function () {
            Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
            Route::patch('/profile', [UsersController::class, 'updateProfile'])->name('update-profile');
            Route::patch('/password', [UsersController::class, 'updatePassword'])->name('update-password');
            Route::patch('/status', [UsersController::class, 'updateStatus'])->name('update-status');
            Route::post('/roles', [UsersController::class, 'assignRoles'])->name('assign-roles');
        });
    });

    // Accounts management
    Route::middleware(['permission:view_accounts'])->group(function () {
        Route::resource('accounts', AccountsController::class)
             ->names([
                 'index' => 'accounts.index',
                 'create' => 'accounts.create',
                 'store' => 'accounts.store',
                 'show' => 'accounts.show',
                 'edit' => 'accounts.edit',
                 'update' => 'accounts.update',
                 'destroy' => 'accounts.destroy'
             ])
             ->except(['destroy'])
             ->middleware([
                 'create' => 'permission:create_accounts',
                 'store' => 'permission:create_accounts',
                 'edit' => 'permission:edit_accounts',
                 'update' => 'permission:edit_accounts'
             ]);

        Route::delete('/accounts/{account}', [AccountsController::class, 'destroy'])
             ->name('accounts.destroy')
             ->middleware('permission:delete_accounts');
    });

    // Vehicles management
    Route::middleware(['permission:view_vehicles'])->group(function () {
        Route::resource('vehicles', VehiclesController::class)
             ->names([
                 'index' => 'vehicles.index',
                 'create' => 'vehicles.create',
                 'store' => 'vehicles.store',
                 'show' => 'vehicles.show',
                 'edit' => 'vehicles.edit',
                 'update' => 'vehicles.update',
                 'destroy' => 'vehicles.destroy'
             ])
             ->except(['destroy'])
             ->middleware([
                 'create' => 'permission:create_vehicles',
                 'store' => 'permission:create_vehicles',
                 'edit' => 'permission:edit_vehicles',
                 'update' => 'permission:edit_vehicles'
             ]);

        Route::delete('/vehicles/{vehicle}', [VehiclesController::class, 'destroy'])
             ->name('vehicles.destroy')
             ->middleware('permission:delete_vehicles');

        // Vehicle types
        Route::resource('vehicle-types', VehicleTypesController::class)
             ->names([
                 'index' => 'vehicle-types.index',
                 'create' => 'vehicle-types.create',
                 'store' => 'vehicle-types.store',
                 'show' => 'vehicle-types.show',
                 'edit' => 'vehicle-types.edit',
                 'update' => 'vehicle-types.update',
                 'destroy' => 'vehicle-types.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_vehicles',
                 'store' => 'permission:create_vehicles',
                 'edit' => 'permission:edit_vehicles',
                 'update' => 'permission:edit_vehicles',
                 'destroy' => 'permission:delete_vehicles'
             ]);
    });

    // Drivers management
    Route::middleware(['permission:view_drivers'])->group(function () {
        Route::resource('drivers', DriversController::class)
             ->names([
                 'index' => 'drivers.index',
                 'create' => 'drivers.create',
                 'store' => 'drivers.store',
                 'show' => 'drivers.show',
                 'edit' => 'drivers.edit',
                 'update' => 'drivers.update',
                 'destroy' => 'drivers.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_drivers',
                 'store' => 'permission:create_drivers',
                 'edit' => 'permission:edit_drivers',
                 'update' => 'permission:edit_drivers',
                 'destroy' => 'permission:delete_drivers'
             ]);
    });

    // Goods management
    Route::middleware(['permission:view_goods'])->group(function () {
        Route::resource('goods', GoodsController::class)
             ->names([
                 'index' => 'goods.index',
                 'create' => 'goods.create',
                 'store' => 'goods.store',
                 'show' => 'goods.show',
                 'edit' => 'goods.edit',
                 'update' => 'goods.update',
                 'destroy' => 'goods.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_goods',
                 'store' => 'permission:create_goods',
                 'edit' => 'permission:edit_goods',
                 'update' => 'permission:edit_goods',
                 'destroy' => 'permission:delete_goods'
             ]);

        Route::resource('goods-types', GoodsTypesController::class)
             ->names([
                 'index' => 'goods-types.index',
                 'create' => 'goods-types.create',
                 'store' => 'goods-types.store',
                 'show' => 'goods-types.show',
                 'edit' => 'goods-types.edit',
                 'update' => 'goods-types.update',
                 'destroy' => 'goods-types.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_goods',
                 'store' => 'permission:create_goods',
                 'edit' => 'permission:edit_goods',
                 'update' => 'permission:edit_goods',
                 'destroy' => 'permission:delete_goods'
             ]);
    });

    // Pricing management
    Route::middleware(['permission:view_prices'])->group(function () {
        Route::resource('prices', PricesController::class)
             ->names([
                 'index' => 'prices.index',
                 'create' => 'prices.create',
                 'store' => 'prices.store',
                 'show' => 'prices.show',
                 'edit' => 'prices.edit',
                 'update' => 'prices.update',
                 'destroy' => 'prices.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_prices',
                 'store' => 'permission:create_prices',
                 'edit' => 'permission:edit_prices',
                 'update' => 'permission:edit_prices',
                 'destroy' => 'permission:delete_prices'
             ]);

        Route::resource('price-details', PriceDetailsController::class)
             ->names([
                 'index' => 'price-details.index',
                 'create' => 'price-details.create',
                 'store' => 'price-details.store',
                 'show' => 'price-details.show',
                 'edit' => 'price-details.edit',
                 'update' => 'price-details.update',
                 'destroy' => 'price-details.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_prices',
                 'store' => 'permission:create_prices',
                 'edit' => 'permission:edit_prices',
                 'update' => 'permission:edit_prices',
                 'destroy' => 'permission:delete_prices'
             ]);
    });

    // Contracts management
    Route::middleware(['permission:view_contracts'])->group(function () {
        Route::resource('contracts', ContractsController::class)
             ->names([
                 'index' => 'contracts.index',
                 'create' => 'contracts.create',
                 'store' => 'contracts.store',
                 'show' => 'contracts.show',
                 'edit' => 'contracts.edit',
                 'update' => 'contracts.update',
                 'destroy' => 'contracts.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_contracts',
                 'store' => 'permission:create_contracts',
                 'edit' => 'permission:edit_contracts',
                 'update' => 'permission:edit_contracts',
                 'destroy' => 'permission:delete_contracts'
             ]);

        Route::resource('contract-details', ContractDetailsController::class)
             ->names([
                 'index' => 'contract-details.index',
                 'create' => 'contract-details.create',
                 'store' => 'contract-details.store',
                 'show' => 'contract-details.show',
                 'edit' => 'contract-details.edit',
                 'update' => 'contract-details.update',
                 'destroy' => 'contract-details.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_contracts',
                 'store' => 'permission:create_contracts',
                 'edit' => 'permission:edit_contracts',
                 'update' => 'permission:edit_contracts',
                 'destroy' => 'permission:delete_contracts'
             ]);
    });

    // Statuses management
    Route::middleware(['permission:view_statuses'])->group(function () {
        Route::resource('statuses', StatusesController::class)
             ->names([
                 'index' => 'statuses.index',
                 'create' => 'statuses.create',
                 'store' => 'statuses.store',
                 'show' => 'statuses.show',
                 'edit' => 'statuses.edit',
                 'update' => 'statuses.update',
                 'destroy' => 'statuses.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_statuses',
                 'store' => 'permission:create_statuses',
                 'edit' => 'permission:edit_statuses',
                 'update' => 'permission:edit_statuses',
                 'destroy' => 'permission:delete_statuses'
             ]);
    });

    // Payment methods management
    Route::middleware(['permission:view_payment_methods'])->group(function () {
        Route::resource('payment-methods', PaymentMethodsController::class)
             ->names([
                 'index' => 'payment-methods.index',
                 'create' => 'payment-methods.create',
                 'store' => 'payment-methods.store',
                 'show' => 'payment-methods.show',
                 'edit' => 'payment-methods.edit',
                 'update' => 'payment-methods.update',
                 'destroy' => 'payment-methods.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_payment_methods',
                 'store' => 'permission:create_payment_methods',
                 'edit' => 'permission:edit_payment_methods',
                 'update' => 'permission:edit_payment_methods',
                 'destroy' => 'permission:delete_payment_methods'
             ]);
    });

    // Geographic data management
    Route::middleware(['permission:view_geographic_data'])->group(function () {
        Route::resource('countries', CountriesController::class)
             ->names([
                 'index' => 'countries.index',
                 'create' => 'countries.create',
                 'store' => 'countries.store',
                 'show' => 'countries.show',
                 'edit' => 'countries.edit',
                 'update' => 'countries.update',
                 'destroy' => 'countries.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_geographic_data',
                 'store' => 'permission:create_geographic_data',
                 'edit' => 'permission:edit_geographic_data',
                 'update' => 'permission:edit_geographic_data',
                 'destroy' => 'permission:delete_geographic_data'
             ]);

        Route::resource('regions', RegionsController::class)
             ->names([
                 'index' => 'regions.index',
                 'create' => 'regions.create',
                 'store' => 'regions.store',
                 'show' => 'regions.show',
                 'edit' => 'regions.edit',
                 'update' => 'regions.update',
                 'destroy' => 'regions.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_geographic_data',
                 'store' => 'permission:create_geographic_data',
                 'edit' => 'permission:edit_geographic_data',
                 'update' => 'permission:edit_geographic_data',
                 'destroy' => 'permission:delete_geographic_data'
             ]);

        Route::resource('cities', CitiesController::class)
             ->names([
                 'index' => 'cities.index',
                 'create' => 'cities.create',
                 'store' => 'cities.store',
                 'show' => 'cities.show',
                 'edit' => 'cities.edit',
                 'update' => 'cities.update',
                 'destroy' => 'cities.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_geographic_data',
                 'store' => 'permission:create_geographic_data',
                 'edit' => 'permission:edit_geographic_data',
                 'update' => 'permission:edit_geographic_data',
                 'destroy' => 'permission:delete_geographic_data'
             ]);
    });

    // Units management
    Route::middleware(['permission:view_units'])->group(function () {
        Route::resource('units', UnitsController::class)
             ->names([
                 'index' => 'units.index',
                 'create' => 'units.create',
                 'store' => 'units.store',
                 'show' => 'units.show',
                 'edit' => 'units.edit',
                 'update' => 'units.update',
                 'destroy' => 'units.destroy'
             ])
             ->middleware([
                 'create' => 'permission:create_units',
                 'store' => 'permission:create_units',
                 'edit' => 'permission:edit_units',
                 'update' => 'permission:edit_units',
                 'destroy' => 'permission:delete_units'
             ]);
    });

    // Reports
    Route::middleware(['permission:view_reports'])->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportShipmentController::class, 'index'])->name('index');
            Route::get('/shipments', [ReportShipmentController::class, 'shipments'])->name('shipments');
            Route::get('/financial', [ReportShipmentController::class, 'financial'])->name('financial');
            Route::get('/performance', [ReportShipmentController::class, 'performance'])->name('performance');
            Route::get('/export/{type}', [ReportShipmentController::class, 'export'])->name('export');
        });
    });

    // Print and utilities
    Route::middleware(['permission:view_shipments'])->group(function () {
        Route::prefix('print')->name('print.')->group(function () {
            Route::get('/waybill/{shipment}', [PrintWaybillController::class, 'waybill'])->name('waybill');
            Route::get('/invoice/{shipment}', [PrintWaybillController::class, 'invoice'])->name('invoice');
            Route::get('/receipt/{shipment}', [PrintWaybillController::class, 'receipt'])->name('receipt');
        });
    });

    // Return shipments
    Route::middleware(['permission:view_shipments'])->group(function () {
        Route::prefix('return-shipments')->name('return-shipments.')->group(function () {
            Route::get('/', [RetrunShipmentInTabsByStatusController::class, 'index'])->name('index');
            Route::get('/by-status/{status}', [RetrunShipmentInTabsByStatusController::class, 'byStatus'])->name('by-status');
        });

        Route::prefix('return-prices')->name('return-prices.')->group(function () {
            Route::get('/', [ReturnPricesController::class, 'index'])->name('index');
            Route::get('/create', [ReturnPricesController::class, 'create'])->name('create');
            Route::post('/', [ReturnPricesController::class, 'store'])->name('store');
        });
    });

    // Vehicle goods extractor
    Route::middleware(['permission:view_vehicles'])->group(function () {
        Route::prefix('vehicle-goods')->name('vehicle-goods.')->group(function () {
            Route::get('/', [VehicleGoodsExtractorController::class, 'index'])->name('index');
            Route::post('/extract', [VehicleGoodsExtractorController::class, 'extract'])->name('extract');
        });
    });

    // Update selected fields
    Route::middleware(['permission:edit_shipments'])->group(function () {
        Route::prefix('update-fields')->name('update-fields.')->group(function () {
            Route::get('/', [UpdateSelectedFieldsController::class, 'index'])->name('index');
            Route::post('/bulk-update', [UpdateSelectedFieldsController::class, 'bulkUpdate'])->name('bulk-update');
        });
    });

    // User profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UsersController::class, 'profile'])->name('show');
        Route::get('/edit', [UsersController::class, 'editProfile'])->name('edit');
        Route::patch('/', [UsersController::class, 'updateProfile'])->name('update');
        Route::patch('/password', [UsersController::class, 'updatePassword'])->name('password');
        Route::patch('/preferences', [UsersController::class, 'updatePreferences'])->name('preferences');
    });

    // System settings (admin only)
    Route::middleware(['permission:manage_system'])->group(function () {
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [HomeController::class, 'settings'])->name('index');
            Route::get('/general', [HomeController::class, 'generalSettings'])->name('general');
            Route::get('/notifications', [HomeController::class, 'notificationSettings'])->name('notifications');
            Route::get('/security', [HomeController::class, 'securitySettings'])->name('security');
            Route::patch('/general', [HomeController::class, 'updateGeneralSettings'])->name('update-general');
            Route::patch('/notifications', [HomeController::class, 'updateNotificationSettings'])->name('update-notifications');
            Route::patch('/security', [HomeController::class, 'updateSecuritySettings'])->name('update-security');
        });
    });
});

// Fallback route for 404 errors
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['message' => 'الصفحة غير موجودة'], 404);
    }
    
    return response()->view('errors.404', [], 404);
});


