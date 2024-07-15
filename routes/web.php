<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* ****************************** */
/* START - RUTAS DE PUBLICACIONES */
/* ****************************** */


//Route::get('/create-symlink', [App\Http\Controllers\StorageLinkController::class, 'createSymlink']);

Route::get('/', [App\Http\Controllers\SiteController::class, 'home']
)->name('/');

Route::get('/publications', [App\Http\Controllers\Site\PublicationController::class, 'index']
)->name('publications');


Route::get('/publication/{pub?}', [App\Http\Controllers\Site\PublicationController::class, 'publication']
)->name('publication');

Route::get('/quienesSomos', [App\Http\Controllers\SiteController::class, 'quienesSomos']
)->name('quienesSomos');

Route::get('/contactanos', [App\Http\Controllers\SiteController::class, 'contact']
)->name('contactanos');

Route::post('/publication/setLike', [App\Http\Controllers\Site\PublicationController::class, 'setLike']
)->name('publication.setLike');

Route::post('/sendContact', [App\Http\Controllers\Site\PublicationController::class, 'sendContact']
)->name('sendContact');

/*Route::get('/publications', [App\Http\Controllers\Site\PublicationController::class, 'index']
)->name('publications');*/

/* **************************** */
/* END - RUTAS DE PUBLICACIONES */
/* **************************** */

/*Route::get('/', [App\Http\Controllers\SiteController::class, 'index']
)->name('home');*/

Route::get('/games', [App\Http\Controllers\SiteController::class, 'games']
)->name('games');

Route::get('/stablishments', [App\Http\Controllers\SiteController::class, 'index']
)->name('stablishments.home');

Route::get('/termsAndConditions', [App\Http\Controllers\SiteController::class, 'termsAndConditions']
)->name('termsAndConditions');

Route::get('/stablishments/{sec?}', [App\Http\Controllers\SiteController::class, 'stablishments']
)->name('stablishments');

Route::get('/stablishment/{stab?}', [App\Http\Controllers\SiteController::class, 'stablishment']
)->name('stablishment');

Route::get('/stablishmentsMap/{stab?}', [App\Http\Controllers\SiteController::class, 'stablishmentsMap']
)->name('cercaDeTi');

Route::get('/stablishmentsJobs/', [App\Http\Controllers\SiteController::class, 'stablishmentsJobs']
)->name('stablishmentsJobs');

Route::get('/myspace', [
    App\Http\Controllers\SiteController::class, 
    'mySpace'
  ]
)->name('myspace')->middleware('auth');

Route::post('/loadRegisterTags', [
    App\Http\Controllers\SiteController::class, 
    'loadRegisterTags'
  ]
)->name('loadRegisterTags');

Route::post('/myspace/addStab', [
    App\Http\Controllers\SiteController::class, 
    'addStab'
  ]
)->name('myspace.addStab')->middleware('auth');

Route::post("/myspace/getJobTypes", [
    App\Http\Controllers\SiteController::class, 
    "getJobTypes"
  ]
)->name("myspace.getJobTypes")->middleware("auth");

Route::post("/myspace/getJobSubTypes", [
    App\Http\Controllers\SiteController::class, 
    "getJobSubTypes"
  ]
)->name("myspace.getJobSubTypes")->middleware("auth");

Route::post('/myspace/addJob', [
    App\Http\Controllers\SiteController::class, 
    'addJob'
  ]
)->name('myspace.addJob')->middleware('auth');

Route::post('/myspace/loadMenus', [
    App\Http\Controllers\SiteController::class, 
    'loadMenus'
  ]
)->name('myspace.loadMenus')->middleware('auth');

Route::post('/myspace/addMenu', [
    App\Http\Controllers\SiteController::class, 
    'addMenu'
  ]
)->name('myspace.addMenu')->middleware('auth');

Route::post('/myspace/loadProducts', [
    App\Http\Controllers\SiteController::class, 
    'loadProducts'
  ]
)->name('myspace.loadProducts')->middleware('auth');

Route::post('/myspace/delProduct', [
    App\Http\Controllers\SiteController::class, 
    'delProduct'
  ]
)->name('myspace.delProduct')->middleware('auth');

Route::post('/myspace/addMenuObj', [
    App\Http\Controllers\SiteController::class, 
    'addMenuObj'
  ]
)->name('myspace.addMenuObj')->middleware('auth');

Route::post('/myspace/delMenuObj', [
    App\Http\Controllers\SiteController::class, 
    'delMenuObj'
  ]
)->name('myspace.delMenuObj')->middleware('auth');

// RUTAS DE MI ESPACIO PARA LA EMPRESA
Route::post('/myspace/loadStab', [
    App\Http\Controllers\SiteController::class, 
    'loadStab'
  ]
)->name('myspace.loadStab')->middleware('auth');

Route::post('/myspace/updateStablishment', [
    App\Http\Controllers\SiteController::class, 
    'updateStablishment'
  ]
)->name('myspace.updateStablishment')->middleware('auth');

Route::post('/myspace/storeGallery', [
    App\Http\Controllers\SiteController::class, 
    'storeGallery'
  ]
)->name('myspace.storeGallery')->middleware('auth');

Route::post('/myspace/loadGallery', [
  App\Http\Controllers\SiteController::class, 
  'loadGallery'
]
)->name('myspace.loadGallery')->middleware('auth');

Route::post('/myspace/updJob', [
    App\Http\Controllers\SiteController::class, 
    'updJob'
  ]
)->name('myspace.updJob')->middleware('auth');

Route::post('/myspace/delJob', [
    App\Http\Controllers\SiteController::class, 
    'delJob'
  ]
)->name('myspace.delJob')->middleware('auth');

Route::post('/myspace/myJobs', [
    App\Http\Controllers\SiteController::class, 
    'myJobs'
  ]
)->name('myspace.myJobs')->middleware('auth');

Route::post('/myspace/addAd', [
    App\Http\Controllers\SiteController::class, 
    'addAd'
  ]
)->name('myspace.addAd')->middleware('auth');


Route::post('/myspace/updAd', [
    App\Http\Controllers\SiteController::class, 
    'updAd'
  ]
)->name('myspace.updAd')->middleware('auth');


Route::post('/myspace/delAd', [
    App\Http\Controllers\SiteController::class, 
    'delAd'
  ]
)->name('myspace.delAd')->middleware('auth');

Route::post('/myspace/myDatas', [
    App\Http\Controllers\SiteController::class, 
    'myDatas'
  ]
)->name('myspace.myDatas')->middleware('auth');

Route::post('/myspace/enableChat', [
    App\Http\Controllers\SiteController::class, 
    'enableChat'
  ]
)->name('myspace.enableChat')->middleware('auth');

Route::post('/myspace/enableDisableStab', [
    App\Http\Controllers\SiteController::class, 
    'enableDisableStab'
  ]
)->name('myspace.enableDisableStab')->middleware('auth');

Route::post('/myspace/storeSocial', [
    App\Http\Controllers\SiteController::class, 
    'storeSocial'
  ]
)->name('myspace.storeSocial')->middleware('auth');

Route::post('/myspace/loadSocial', [
    App\Http\Controllers\SiteController::class, 
    'loadSocial'
  ]
)->name('myspace.loadSocial')->middleware('auth');




/* ******************************************* */
/* START - RUTAS DE MI ESPACIO PARA EL USUARIO */
/* ******************************************* */

Route::post('/myspace/loadCv', [
    App\Http\Controllers\SiteController::class, 
    'loadCv'
  ]
)->name('myspace.loadCv')->middleware('auth');

Route::post('/myspace/addCv', [
    App\Http\Controllers\SiteController::class, 
    'addCv'
  ]
)->name('myspace.addCv')->middleware('auth');

Route::post('/myspace/updateCv', [
    App\Http\Controllers\SiteController::class, 
    'updateCv'
  ]
)->name('myspace.updateCv')->middleware('auth');

/* ******************************************* */
/* END - RUTAS DE MI ESPACIO PARA EL USUARIO */
/* ******************************************* */






Route::post('/addStablishment', [
    App\Http\Controllers\SiteController::class, 
    'addStablishment'
  ]
)->name('addStablishment');

Route::post('/delStablishment', [App\Http\Controllers\SiteController::class, 'delStablishment']
)->name('delStablishment');

Route::post('/setFilter', [App\Http\Controllers\SiteController::class, 'setFilter']
)->name('setFilter');

Route::post('/chat/loadAllMessages', [
    App\Http\Controllers\ChatController::class, 
    'loadAllMessages'
  ]
)->name('chat.loadAllMessages')->middleware('auth');

Route::post('/chat/loadMessages', [
    App\Http\Controllers\ChatController::class, 
    'loadMessages'
  ]
)->name('chat.loadMessages')->middleware('auth');

Route::post('/chat/messageSave', [
    App\Http\Controllers\ChatController::class, 
    'messageSave'
  ]
)->name('chat.messageSave')->middleware('auth');

Route::post('/chat/loadAllUsers', [
    App\Http\Controllers\ChatController::class, 
    'loadAllUsers'
  ]
)->name('chat.loadAllUsers')->middleware('auth');

Route::post('/chat/loadNewMsgGeneral', [
    App\Http\Controllers\ChatController::class, 
    'loadNewMsgGeneral'
  ]
)->name('chat.loadNewMsgGeneral')->middleware('auth');




Route::get('/test1', [App\Http\Controllers\SiteController::class, 'test1']
)->name('test1');

Route::post('/test2', [App\Http\Controllers\SiteController::class, 'test2']
)->name('test2');

/* RUTAS PARA EL ADMINISTRADOR */

/* PUBLICATIONS - publicaciones */
Route::get('/admin/publications', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'index']
)->name('admin.publications');

Route::post('/admin/publications/getPublications', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'getPublications']
)->name('admin.publications.getPublications');

Route::post('/admin/publications/getEstados', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'getEstados']
)->name('admin.publications.getEstados');

Route::post('/admin/publications/getMunicipios', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'getMunicipios']
)->name('admin.publications.getMunicipios');

Route::post('/admin/publications/getSections', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'getSections']
)->name('admin.publications.getSections');

Route::post('/admin/publications/getTags', 
  [App\Http\Controllers\Admin\PublicationController::class, 
  'getTags']
)->name('admin.publications.getTags');

Route::post('/admin/publications/store',
  [App\Http\Controllers\Admin\PublicationController::class, 
  'store']
)->name('admin.publications.store');

/* STABLISHMENTS - establecimientos */
Route::get('/admin/stablishments', 
  [App\Http\Controllers\StablishmentController::class, 
  'index']
)->name('admin.stablishments');

Route::get(
  '/admin/stablishments/create',
  [App\Http\Controllers\StablishmentController::class, 
  'create']
)->name('admin.stablishments.create');

Route::post(
  '/admin/stablishments/create',
  [App\Http\Controllers\StablishmentController::class, 
  'store']
)->name('admin.stablishments.create');

Route::get(
  '/admin/stablishments/{stab}/edit',
  [App\Http\Controllers\StablishmentController::class, 
  'edit']
)->name('admin.stablishments.edit');

Route::patch(
  '/admin/stablishments/{stab}',
  [App\Http\Controllers\StablishmentController::class, 
  'update']
)->name('admin.stablishments.update');

Route::delete(
  '/admin/stablishments/{stab}',
  [App\Http\Controllers\StablishmentController::class, 
  'destroy']
)->name('admin.stablishments.destroy');

Route::post(
  '/admin/stablishments/elimination',
  [App\Http\Controllers\StablishmentController::class,
  'destroyAll']
)->name('admin.stablishments.elimination');

Route::post(
  '/admin/stablishments/addVisitsAll',
  [App\Http\Controllers\StablishmentController::class,
  'addVisitsAll']
)->name('admin.stablishments.addVisitsAll');

Route::post(
  '/admin/stablishments/enabledGlobalStab',
  [App\Http\Controllers\StablishmentController::class,
  'enabledGlobalStab']
)->name('admin.stablishments.enabledGlobalStab');

/* ADVERTISEMENTS - anuncios */
Route::get('/admin/advertisements', 
  [App\Http\Controllers\AdvertisementController::class, 
  'index']
)->name('admin.advertisements');

Route::get(
  '/admin/advertisements/create',
  [App\Http\Controllers\AdvertisementController::class, 
  'create']
)->name('admin.advertisements.create');

Route::post(
  '/admin/advertisements/create',
  [App\Http\Controllers\AdvertisementController::class, 
  'store']
)->name('admin.advertisements.create');

Route::get(
  '/admin/advertisements/{adv}/edit',
  [App\Http\Controllers\AdvertisementController::class, 
  'edit']
)->name('admin.advertisements.edit');

Route::patch(
  '/admin/advertisements/{adv}',
  [App\Http\Controllers\AdvertisementController::class, 
  'update']
)->name('admin.advertisements.update');

Route::delete(
  '/admin/advertisements/{stab}',
  [App\Http\Controllers\AdvertisementController::class, 
  'destroy']
)->name('admin.advertisements.destroy');

Route::post(
  '/admin/advertisements/elimination',
  [App\Http\Controllers\AdvertisementController::class,
  'destroyAll']
)->name('admin.advertisements.elimination');



// RUTAS GENERALES PARA SER LLAMADAS POR AJAX
Route::post(
  '/loadTags',
  [App\Http\Controllers\StablishmentController::class,
  'loadTags']
)->name('loadTags');

Route::post(
  '/loadTagsAndChecks',
  [App\Http\Controllers\StablishmentController::class,
  'loadTagsAndChecks']
)->name('loadTagsAndChecks');