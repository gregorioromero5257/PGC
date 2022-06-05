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

Route::get('/', function () {return redirect('/login');});
/* Route::get('/proyectos/{id}', function () {return view('welcome');});
Route::get('/quienes-somos', function () {return view('welcome');});
Route::get('/que-hacemos', function () {return view('welcome');});
Route::get('/vacantes', function () {return view('welcome');});
Route::get('/vacantes/{slug}', function () {return view('welcome');});
Route::get('/aviso-de-privacidad', function () {return view('welcome');});

Route::get('/pgc', function () {return redirect('/login');}); */

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/projects', 'HomeController@index');
Route::get('/projects/create', 'HomeController@index');
Route::get('/projects/edit/{id}', 'HomeController@index');
Route::get('/projects/{id}', 'HomeController@index');
Route::get('/staff', 'HomeController@index');
Route::get('/staff/{id}', 'HomeController@index');
Route::get('/staff/create', 'HomeController@index');
Route::get('/staff/edit/{id}', 'HomeController@index');
Route::get('/resources', 'HomeController@index');
Route::get('/resources/folder/{id}', 'HomeController@index');
Route::get('/vacancies', 'HomeController@index');
Route::get('/vacancies/create', 'HomeController@index');
Route::get('/vacancies/{slug}', 'HomeController@index');
Route::get('/vacancies/edit/{slug}', 'HomeController@index');
Route::get('fixed-contacts','HomeController@fixed');
Route::get('fixed-files/{id}','HomeController@fileSave');
Route::get('fixed-contacts-dos','HomeController@createJson');
Route::get('projects-list','HomeController@download');
Route::get('r_vacancies','RequestPublicController@vacancy');
Route::get('r_vacancies_idea','RequestPublicController@vacancyI');
Route::get('r_vacancies/{id}','RequestPublicController@getvacancy');
Route::get('r_vacancies_idea/{id}','RequestPublicController@getvacancyI');
Route::get('send_email/{id}','RequestPublicController@send');
Route::get('get_projects','RequestPublicController@getProjects');
Route::get('get_projects_principal','RequestPublicController@getProjectsPrincipal');
Route::get('get_projects/{id}','RequestPublicController@getsProject');
Route::get('get_projects-diferent/{id}','RequestPublicController@getProjectsDiferent');
Route::get('fix-date-in','RequestPublicController@fixedDateIn');

Route::prefix('manage')->group(function () {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::prefix('projects')->group(function () {
        Route::get('/privacy_options', 'HomeController@index');
        Route::get('/locations', 'HomeController@index');
        Route::get('/companies', 'HomeController@index');
        Route::get('/contracts', 'HomeController@index');
        Route::get('/satisfaction_documents', 'HomeController@index');
        Route::get('/currencies', 'HomeController@index');
        Route::get('/types', 'HomeController@index');
        Route::get('/methodologies', 'HomeController@index');
        Route::get('/topics', 'HomeController@index');
    });
    Route::get('/stackeholders', 'HomeController@index');
    Route::prefix('staff')->group(function () {
        Route::get('/roles', 'HomeController@index');
        Route::get('/positions', 'HomeController@index');
    });
    Route::get('/codigos', 'HomeController@index');

});
Route::get('/profile', 'HomeController@index');
Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('testdrive', function() {
    Storage::disk('google')->put('test.txt', 'Hello World');
});
