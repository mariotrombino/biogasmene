<?php

use Dwij\Laraadmin\Helpers\LAHelper;
use App\Models\ProdottiAssociati;

/* ================== Homepage ================== */
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');
//Route::get('/mobile', 'MobileController@index');
/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/
$we = "";
Route::group(['middleware' => 'mobile'],function(){
    Route::get('/mobile/login', 'Mobile\LoginController@showLoginForm');
    Route::post('/mobile/login', 'Mobile\LoginController@login');
    Route::get('/mobile', 'MobileController@index');
    Route::post('/mobile/caricoterzisti', 'MobileController@caricoterzisti');
    Route::post('/mobile/caricosocio', 'MobileController@caricosocio');
    Route::post('/mobile/scaricosocio', 'MobileController@scaricosocio');
    Route::post('/mobile/scaricoterzisti', 'MobileController@scaricoterzisti');
    Route::get('/mobile/logout','MobileController@logout');
    Route::get('/mobile/ingresso/{id}','MobileController@prodingressoajax');
    Route::get('/mobile/uscita/{id}','MobileController@produscitaajax');
    Route::get('/mobile/ingressojson','MobileController@ingressojson');
    Route::get('/mobile/uscitajson','MobileController@uscitajson');
});
$as = "";
if(LAHelper::laravel_ver() == 5.3 || LAHelper::laravel_ver() == 5.4) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {
	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::resource(config('laraadmin.adminRoute') . '/admin', 'LA\AdminController');
	Route::post(config('laraadmin.adminRoute') . '/admin_change_password/{id}', 'LA\AdminController@admin_change_password');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtajax');
	Route::get(config('laraadmin.adminRoute') . '/prodajax/{id}', 'LA\EmployeesController@prodajax');
	Route::get(config('laraadmin.adminRoute') . '/prodotti/ingresso/{id}', 'LA\EmployeesController@prodottiingressoajax');
	Route::get(config('laraadmin.adminRoute') . '/prodotti/uscita/{id}', 'LA\EmployeesController@prodottiuscitaajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	Route::post(config('laraadmin.adminRoute') . '/soci_cambia_carico/{id}', 'LA\EmployeesController@change_carico');
	Route::post(config('laraadmin.adminRoute') . '/soci_cambia_scarico/{id}', 'LA\EmployeesController@change_scarico');
	Route::post(config('laraadmin.adminRoute') . '/soci_cambia_tara/{id}', 'LA\EmployeesController@change_tara');
	
	/* ================== Ingresso/Uscita =========== */
	Route::resource(config('laraadmin.adminRoute').'/ingresso', 'LA\IngressoController');
	Route::resource(config('laraadmin.adminRoute').'/uscita', 'LA\UscitaController');
	
	/* ================== Prodotti Associati =========== */
	Route::resource(config('laraadmin.adminRoute').'/prodottiassociati', 'LA\ProdottiAssociatiController');
	Route::resource(config('laraadmin.adminRoute').'/prodottiassociatiterzisti', 'LA\ProdottiAssociatiTerzistiController');
	
	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

    /* ================== Terzisti ================== */
    Route::resource(config('laraadmin.adminRoute') . '/terzisti', 'LA\TerzistiController');
    Route::get(config('laraadmin.adminRoute') . '/terzisti_dt_ajax', 'LA\TerzistiController@dtajax');
    Route::get(config('laraadmin.adminRoute') . '/proddipajax/{id}', 'LA\TerzistiController@prodajax');
    Route::post(config('laraadmin.adminRoute') . '/cambia_password/{id}', 'LA\TerzistiController@change_password');
    Route::post(config('laraadmin.adminRoute') . '/cambia_carico/{id}', 'LA\TerzistiController@change_carico');
    Route::post(config('laraadmin.adminRoute') . '/cambia_scarico/{id}', 'LA\TerzistiController@change_scarico');
    Route::post(config('laraadmin.adminRoute') . '/cambia_tara/{id}', 'LA\TerzistiController@change_tara');
    /* ================== Prodotti ================== */
    Route::resource(config('laraadmin.adminRoute') . '/prodotti', 'LA\ProdottiController');
    Route::get(config('laraadmin.adminRoute') . '/prodotti_dt_ajax', 'LA\ProdottiController@dtajax');
    
    Route::get(config('laraadmin.adminRoute') . '/ingresso/prodotti/{id}',function($id){
        $prodotti = ProdottiAssociati::where('users_id','=', $id)->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->where('prodotti.tipo','=','ingresso')->select('prodotti.*')->get();
        return response()->json($prodotti);
    });
        Route::get(config('laraadmin.adminRoute') . '/uscita/prodotti/{id}',function($id){
            $prodotti = ProdottiAssociati::where('users_id','=', $id)->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->where('prodotti.tipo','=','uscita')->select('prodotti.*')->get();
            return response()->json($prodotti);
        });
});