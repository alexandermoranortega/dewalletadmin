<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::group(['middleware' => 'guest'], function () {
	Route::get('/', function(){
		return view('Login.login');
	})->name('/');

	Route::get('/exitoso', function(){
		return view('Cambio');
	})->name('/exitoso');

	Route::post('password/email', 'LoginController@sendResetLinkEmail')->name('password.email');
	Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
	Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');

	Route::post('/ingresar', 'LoginController@ingresar');
	Route::get('/recuperar',function(){
		return view('Login.recuperar');
	});

	Route::get('/register', 'LoginController@registration');
	Route::post('/registerform', 'LoginController@customRegistration');
});

Route::group(['middleware' => 'auth'], function () {

	Route::post('/logout', 'LoginController@logout');

	Route::get('/home',  function(){
			return view('plantilla');
	})->name('home');

	Route::resource('/local','LocalController')->except('show');
	Route::get('local/consultar','LocalController@consulta_data');

	Route::resource('/vendedor','VendedorController')->except('show');
	Route::get('vendedor/consultar','VendedorController@consulta_data');

	Route::resource('/producto','ProductoController')->except('show');
	Route::get('producto/consultar','ProductoController@consulta_data');

	Route::resource('/promociones','PromocionesController')->except('show');
	Route::get('promociones/consultar','PromocionesController@consulta_data');

});

