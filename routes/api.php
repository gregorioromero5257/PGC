<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['user' => 'API\UserController']);
Route::get('user-delete/{id}','API\UserController@deleteUser');
Route::put('user-data-additonial/{id}','API\UserController@additionalData');
Route::get('dowload-avatar/{id}','API\UserController@dowloadAvatar');
Route::get('active-user', 'API\UserController@activeUser');
Route::get('active-users', 'API\UserController@activeUsers');
Route::get('partners', 'API\UserController@partners');
Route::get('get-members-project/{id}','API\UserController@members');
Route::post('save-members','API\UserController@savemembers');
Route::post('delete-members','API\UserController@deletemembers');
Route::post('find-users', 'API\UserController@findUser');
Route::get('active-desactive-user/{id}','API\UserController@userActiveDesactive');
Route::get('delete-user/{id}','API\UserController@userDelete');
Route::post('upload-avatar','API\UserController@uploadAvatar');


Route::apiResources(['user-file' => 'API\UserFileController']);
Route::get('download-user-file/{id}', 'API\UserFileController@downloadFile');

Route::apiResources(['project' => 'API\ProjectController']);
Route::get('project-idea','API\ProjectController@getProjectIdea');
Route::get('get-projects','API\ProjectController@getProjects');
Route::get('delete-project/{id}','API\ProjectController@deleteProject');
Route::post('project-aditional','API\ProjectController@aditional');
Route::post('project-codes-save','API\ProjectController@codes');
Route::put('download-project', 'API\ProjectController@download');
Route::post('assigned-project', 'API\ProjectController@asingProjectUser');
Route::post('add-new-project', 'API\ProjectController@addProject');
Route::post('find-project', 'API\ProjectController@findProject');

Route::get('get-clients-project/{id}','API\ProjectController@getClients');
Route::post('save-client','API\ProjectController@saveClients');
Route::get('delete-client/{id}','API\ProjectController@deleteClient');

Route::post('save-contact','API\ProjectController@saveContact');
Route::post('edit-contact','API\ProjectController@editContact');
Route::get('delete-contact/{id}','API\ProjectController@deleteContact');

Route::get('get-funders-project/{id}','API\ProjectController@getFunders');
Route::post('save-funder','API\ProjectController@saveFunders');
Route::get('delete-funder/{id}','API\ProjectController@deleteFunder');

Route::get('get-suppliers-project/{id}','API\ProjectController@getSuppliers');
Route::post('save-supplier','API\ProjectController@saveSuppliers');
Route::get('delete-supplier/{id}','API\ProjectController@deleteSupplier');

Route::get('get-consortions-project/{id}','API\ProjectController@getConsortions');
Route::post('save-consortions','API\ProjectController@saveConsortion');
Route::get('delete-consortions/{id}','API\ProjectController@deleteConsortion');

Route::get('get-members-extern-project/{id}','API\ProjectController@getMembersExtern');
Route::post('save-members-extern','API\ProjectController@saveMembersExtern');
Route::get('delete-members-extern/{id}','API\ProjectController@deleteMembersExtern');

Route::post('find-filters','API\ProjectController@findFilters');
Route::post('get-with-filters','API\ProjectController@getWithFilters');
Route::post('get-with-filters-users','API\UserController@getWithFilters');
// Route::get('fixed-contacts','API\ProjectController@fixed');
Route::get('delete-publications/{id}','API\ProjectController@deletePublication');
Route::post('save-edit-publications','API\ProjectController@saveEditPublication');

Route::apiResources(['project-file' => 'API\ProjectFileController']);
Route::post('project-file-dropbox','API\ProjectFileController@dropbox');
Route::get('download-project-file/{id}', 'API\ProjectFileController@downloadFile');
Route::get('download-project-file-publication/{id}', 'API\ProjectFileController@downloadFilePublication');
Route::get('clear-project-file-publication/{id}', 'API\ProjectFileController@clearFilePublication');

Route::post('savepublicvacancy','API\NewVacancyController@vacancySavePublic');
Route::get('change-status-vacancies/{id}','API\NewVacancyController@changeStatus');
Route::post('find-vacancies', 'API\NewVacancyController@findVacancies');
Route::post('get-with-filters-vacancies','API\NewVacancyController@getWithFilters');

Route::post('save-file-web-publications','API\ProjectController@saveFileWeb');
Route::post('data-web-store','API\ProjectController@dataWeb');
Route::post('data-web-store-file','API\ProjectController@dataWebFile');
Route::get('delete-image-single/{id}','API\ProjectController@deleteImageSingle');



Route::apiResources(['company' => 'API\CompanyController']);

Route::apiResources(['privacy-option' => 'API\PrivacyOptionController']);

Route::apiResources(['stackeholder' => 'API\StackeholderController']);

Route::apiResources(['location' => 'API\LocationController']);

Route::apiResources(['type' => 'API\TypeController']);

Route::apiResources(['methodology' => 'API\MethodologyController']);

Route::apiResources(['tematics' => 'API\TematicController']);
Route::get('tematics-idea', 'API\TematicController@tematicsIdea');

Route::apiResources(['topic' => 'API\TopicController']);

Route::apiResources(['contact' => 'API\ContactController']);

Route::apiResources(['currency' => 'API\CurrencyController']);

Route::apiResources(['contract' => 'API\ContractController']);

Route::apiResources(['satisfaction_document' => 'API\SatisfactionDocumentController']);

Route::apiResources(['sh-description' => 'API\ShDescriptionController']);

Route::apiResources(['role' => 'API\RoleController']);

Route::apiResources(['role-times' => 'API\RoleTimeController']);

Route::apiResources(['role-radar' => 'API\RoleRadarController']);

Route::apiResources(['position' => 'API\PositionController']);

Route::apiResources(['folder' => 'API\FolderController']);
Route::get('main-folders', 'API\FolderController@mainFolders');
Route::get('delete-folder/{id}', 'API\FolderController@deleteFolder');
Route::post('change-name-folder', 'API\FolderController@changeNameFolder');

Route::apiResources(['resource' => 'API\ResourceController']);
Route::get('download-resource/{id}', 'API\ResourceController@downloadFile');
Route::get('savelocalfile/{id}', 'API\ResourceController@saveLocaFile');
Route::get('delete-resource/{id}', 'API\ResourceController@deleteRFile');

Route::post('find-folder-file','API\ResourceController@findRF');
Route::get('get-track-folder/{id}','API\ResourceController@getTrackFolder');
Route::get('get-track-file/{id}','API\ResourceController@getTrackFile');

Route::apiResources(['position' => 'API\PositionController']);

Route::apiResources(['department' => 'API\DepartmentController']);

Route::apiResources(['vacancy' => 'API\VacancyController']);
Route::get('active-vacancies', 'API\VacancyController@activeVacancies');
Route::get('vacancy-by-slug/{slug}', 'API\VacancyController@vacancyBySlug');
Route::put('edit-vacancy/{slug}', 'API\VacancyController@updatebyslug');


Route::apiResources(['project-codes' => 'API\ProjectCodeController']);

Route::post('save-publication-web-idea-pgc', 'API\IdeaWebController@updateWeb');

Route::get('get-totaly-all-data','API\HomeController@index');
