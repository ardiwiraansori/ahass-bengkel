<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\ServiceJobController;
use App\Http\Controllers\Master\PartController;
use App\Http\Controllers\Master\MechanicController;
use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\VehicleController;
use App\Http\Controllers\Transaction\ServiceAdvisorController;
use App\Http\Controllers\Transaction\WorkOrderController;
use App\Http\Controllers\Transaction\WorkOrderJobController;
use App\Http\Controllers\Transaction\WorkOrderPartController;

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


Route::group(['middleware' => 'auth'], function () {

	Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

	Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

	Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

	Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
	Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');

	Route::prefix('master/jobs')
		->name('master.jobs.')
		->controller(ServiceJobController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::post('/', 'store')->name('store');

			Route::get('/{serviceJob}', 'show')
				->where('serviceJob', '[A-Za-z0-9]+')
				->name('show');

			Route::put('/{serviceJob}', 'update')
				->where('serviceJob', '[A-Za-z0-9]+')
				->name('update');

			Route::patch('/{serviceJob}/toggle-status', 'toggleStatus')
				->where('serviceJob', '[A-Za-z0-9]+')
				->name('toggle-status');
		});
	Route::prefix('master/parts')
		->name('master.parts.')
		->controller(PartController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::post('/', 'store')->name('store');

			Route::get('/{part}', 'show')
				->where('part', '[A-Za-z0-9]+')
				->name('show');

			Route::put('/{part}', 'update')
				->where('part', '[A-Za-z0-9]+')
				->name('update');

			Route::patch('/{part}/toggle-status', 'toggleStatus')
				->where('part', '[A-Za-z0-9]+')
				->name('toggle-status');
		});
	Route::prefix('master/mechanics')
		->name('master.mechanics.')
		->controller(MechanicController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::post('/', 'store')->name('store');

			Route::get('/{mechanic}', 'show')
				->where('mechanic', '[A-Za-z0-9\-]+')
				->name('show');

			Route::put('/{mechanic}', 'update')
				->where('mechanic', '[A-Za-z0-9\-]+')
				->name('update');

			Route::patch('/{mechanic}/toggle-status', 'toggleStatus')
				->where('mechanic', '[A-Za-z0-9\-]+')
				->name('toggle-status');
		});

	Route::prefix('master/customers')
		->name('master.customers.')
		->controller(CustomerController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::get('/detail', 'show')->name('show');

			Route::post('/', 'store')->name('store');
			Route::put('/', 'update')->name('update');
		});

	Route::prefix('master/vehicles')
		->name('master.vehicles.')
		->controller(VehicleController::class)
		->group(function () {
			Route::get('/data', 'data')->name('data');
			Route::post('/', 'store')->name('store');

			Route::get('/{vehicle}', 'show')
				->whereNumber('vehicle')
				->name('show');

			Route::put('/{vehicle}', 'update')
				->whereNumber('vehicle')
				->name('update');
		});

	Route::prefix('transactions/service-advisors')
		->name('transactions.service-advisors.')
		->controller(ServiceAdvisorController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::get('/detail', 'show')->name('show');

			Route::post('/', 'store')->name('store');
			Route::put('/', 'update')->name('update');
			Route::patch('/cancel', 'cancel')->name('cancel');
		});

	Route::prefix('transactions/work-orders')
		->name('transactions.work-orders.')
		->controller(WorkOrderController::class)
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/data', 'data')->name('data');
			Route::get('/detail', 'show')->name('show');

			Route::post('/', 'store')->name('store');
			Route::put('/', 'update')->name('update');
		});

	Route::prefix('transactions/work-order-jobs')
		->name('transactions.work-order-jobs.')
		->controller(WorkOrderJobController::class)
		->group(function () {
			Route::post('/', 'store')->name('store');

			Route::put('/{workOrderJob}', 'update')
				->whereNumber('workOrderJob')
				->name('update');

			Route::delete('/{workOrderJob}', 'destroy')
				->whereNumber('workOrderJob')
				->name('destroy');
		});

	Route::prefix('transactions/work-order-parts')
		->name('transactions.work-order-parts.')
		->controller(WorkOrderPartController::class)
		->group(function () {
			Route::post('/', 'store')
				->name('store');

			Route::put('/{workOrderPart}', 'update')
				->whereNumber('workOrderPart')
				->name('update');

			Route::delete('/{workOrderPart}', 'destroy')
				->whereNumber('workOrderPart')
				->name('destroy');
		});
});



Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
	return view('session/login-session');
})->name('login');
