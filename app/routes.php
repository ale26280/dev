<?php

/*
|--------------------------------------------------------------------------
| Aplicacion
|--------------------------------------------------------------------------
|
*/

Route::get('/', 'FrontController@getHome');		# Landing page




/*
|--------------------------------------------------------------------------
| Cuentas de usuario
|--------------------------------------------------------------------------
*/

Route::post('registro', 'SvcCuentaController@postRegistro');
Route::post('login', 'SvcCuentaController@postLogin');
Route::get('confirm/{email}/{token}', array('as' => 'cuenta_confirmacion', 'uses' => 'SvcCuentaController@getConfirmacion') );
Route::post('recovery-req', array('as' => 'cuenta_recovery_req', 'uses' => 'SvcCuentaController@postRecoveryReq') );
Route::get('recovery-confirm/{email}/{token}', array('as' => 'cuenta_recovery_confirm', 'uses' => 'FrontController@getRecoveryConfirmacion') );
Route::post('recovery-change', array('as' => 'cuenta_recovery_change', 'uses' => 'SvcCuentaController@postRecoveryChange') );




/*
|--------------------------------------------------------------------------
| Perfiles de usuario
|--------------------------------------------------------------------------
*/

Route::get('perfil/{id}', array('as' => 'usuario_perfil', 'uses' =>'SvcUsuarioController@getPerfil') );
Route::post('upload-foto', array('as' => 'usuario_upload_foto', 'uses' =>'SvcUsuarioController@postUploadFoto') );





/*
|--------------------------------------------------------------------------
| Administrador
|--------------------------------------------------------------------------
*/

Route::group( array('before' => 'auth' ), function() {

	Route::get('adm/dashboard', array('as' => 'dashboard', 'uses' => 'AdmMainController@getDashboard') );

	# MODULO 'Administradores'
	
	Route::get('adm/administradores', array('as' => 'administrador_listar', 'uses' => 'AdmAdministradorController@getListar') );
	Route::get('adm/administradores/agregar', array('as' => 'administrador_agregar', 'uses' => 'AdmAdministradorController@getAgregar') );
	Route::post('adm/administradores/agregar', array('as' => 'administrador_agregar_post', 'uses' => 'AdmAdministradorController@postAgregar') );	
	Route::get('adm/administradores/{id}', array('as' => 'administrador_editar', 'uses' => 'AdmAdministradorController@getEditar') );
	Route::post('adm/administradores/{id}/editar', array('as' => 'administrador_editar_post', 'uses' =>'AdmAdministradorController@postEditar') );
	Route::get('adm/administradores/{id}/borrar', array('as' => 'administrador_borrar', 'uses' => 'AdmAdministradorController@getBorrar') );
	
	# MODULO 'Fichas'
	
	Route::get('adm/fichas', array('as' => 'ficha_listar', 'uses' => 'AdmFichaController@getListar') );
	Route::get('adm/fichas/agregar', array('as' => 'ficha_agregar', 'uses' => 'AdmFichaController@getAgregar') );
	Route::post('adm/fichas/agregar', array('as' => 'ficha_agregar_post', 'uses' => 'AdmFichaController@postAgregar') );	
	Route::get('adm/fichas/{id}', array('as' => 'ficha_editar', 'uses' => 'AdmFichaController@getEditar') );
	Route::post('adm/ficha/{id}/editar', array('as' => 'ficha_editar_post', 'uses' =>'AdmFichaController@postEditar') );
	Route::get('adm/fichas/{id}/borrar', array('as' => 'ficha_borrar', 'uses' => 'AdmFichaController@getBorrar') );
	
	Route::any('adm/fichas/video/agregar/{ficha_id?}', array('as' => 'ficha_video_agregar', 'uses' => 'AdmFichaController@postVideoAgregar') );				
	Route::any('adm/fichas/foto/agregar/{ficha_id?}', array('as' => 'ficha_foto_upload', 'uses' => 'AdmFichaController@postFotoUpload') );			
	Route::post('adm/fichas/video/reordenar/{tipo?}', array('as' => 'ficha_video_reordenar', 'uses' => 'AdmFichaController@postVideosReorder') );	
	Route::post('adm/fichas/foto/reordenar/{tipo?}', array('as' => 'ficha_foto_reordenar', 'uses' => 'AdmFichaController@postFotosReorder') );	

	
	# MODULO 'Sliders'
	
	Route::get('adm/sliders', array('as' => 'slider_listar', 'uses' => 'AdmSliderController@getListar') );
	Route::get('adm/sliders/agregar', array('as' => 'slider_agregar', 'uses' => 'AdmSliderController@getAgregar') );
	Route::post('adm/sliders/agregar', array('as' => 'slider_agregar_post', 'uses' => 'AdmSliderController@postAgregar') );	
	Route::get('adm/sliders/{id}', array('as' => 'slider_editar', 'uses' => 'AdmSliderController@getEditar') );
	Route::post('adm/slider/{id}/editar', array('as' => 'slider_editar_post', 'uses' =>'AdmSliderController@postEditar') );
	Route::get('adm/sliders/{id}/borrar', array('as' => 'slider_borrar', 'uses' => 'AdmSliderController@getBorrar') );
	
	Route::any('adm/slider/foto/agregar/{portfolio_id?}', array('as' => 'slider_foto_upload', 'uses' => 'AdmSliderController@postFotoUpload') );			
	Route::any('adm/slider/foto/reordenar/{tipo?}', array('as' => 'slider_foto_reordenar', 'uses' => 'AdmSliderController@postFotosReorder') );	
	Route::get('adm/slider/foto/get/{id}/{temporal?}', array('as' => 'slider_foto_get', 'uses' => 'AdmSliderController@postFotoGet') );	
	Route::get('adm/slider/foto/editar/{temporal?}', array('as' => 'slider_foto_editar', 'uses' => 'AdmSliderController@postFotoEditar') );		
	Route::get('adm/slider/{tipo}/{id}/borrar', array('as' => 'galeria_borrar', 'uses' => 'AdmSliderController@getBorrarUpload') );	
	

	# UNICAMENTE para galerias de foto / vide

	Route::any('adm/{elemento}/{tipo}/{id}/borrar', array('as' => 'galeria_borrar', 'uses' => 'AdmFichaController@getBorrarUpload') );	
	
	
	# MODULO 'Newsletter'
	
	Route::get('adm/newsletters/export', array('as' => 'newsletter_export', 'uses' => 'AdmNewsletterController@getExport') );	
	Route::get('adm/newsletters', array('as' => 'newsletter_listar', 'uses' => 'AdmNewsletterController@getListar') );
	Route::get('adm/newsletters/agregar', array('as' => 'newsletter_agregar', 'uses' => 'AdmNewsletterController@getAgregar') );
	Route::post('adm/newsletters/agregar', array('as' => 'newsletter_agregar_post', 'uses' => 'AdmNewsletterController@postAgregar') );	
	Route::get('adm/newsletters/{id}', array('as' => 'newsletter_editar', 'uses' => 'AdmNewsletterController@getEditar') );
	Route::post('adm/newsletter/{id}/editar', array('as' => 'newsletter_editar_post', 'uses' =>'AdmNewsletterController@postEditar') );
	Route::get('adm/newsletters/{id}/borrar', array('as' => 'newsletter_borrar', 'uses' => 'AdmNewsletterController@getBorrar') );
	
	
	# MODULO 'Tags'
	
	Route::get('adm/tags', array('as' => 'tag_listar', 'uses' => 'AdmTagController@getListar') );
	Route::get('adm/tags/agregar', array('as' => 'tag_agregar', 'uses' => 'AdmTagController@getAgregar') );
	Route::post('adm/tags/agregar', array('as' => 'tag_agregar_post', 'uses' => 'AdmTagController@postAgregar') );	
	Route::get('adm/tags/{id}', array('as' => 'tag_editar', 'uses' => 'AdmTagController@getEditar') );
	Route::post('adm/tag/{id}/editar', array('as' => 'tag_editar_post', 'uses' =>'AdmTagController@postEditar') );
	Route::get('adm/tags/{id}/borrar', array('as' => 'tag_borrar', 'uses' => 'AdmTagController@getBorrar') );
	
	
	# MODULO 'Ficha Categoria'
	
	Route::get('adm/categorias/export', array('as' => 'fichacategoria_export', 'uses' => 'AdmFichaCategoriaController@getExport') );	
	Route::get('adm/categorias', array('as' => 'fichacategoria_listar', 'uses' => 'AdmFichaCategoriaController@getListar') );
	Route::get('adm/categorias/agregar', array('as' => 'fichacategoria_agregar', 'uses' => 'AdmFichaCategoriaController@getAgregar') );
	Route::post('adm/categorias/agregar', array('as' => 'fichacategoria_agregar_post', 'uses' => 'AdmFichaCategoriaController@postAgregar') );	
	Route::get('adm/categorias/{id}', array('as' => 'fichacategoria_editar', 'uses' => 'AdmFichaCategoriaController@getEditar') );
	Route::post('adm/fichacategoria/{id}/editar', array('as' => 'fichacategoria_editar_post', 'uses' =>'AdmFichaCategoriaController@postEditar') );
	Route::get('adm/categorias/{id}/borrar', array('as' => 'fichacategoria_borrar', 'uses' => 'AdmFichaCategoriaController@getBorrar') );
	Route::get('adm/categorias/tags/{id?}/{parent_id?}', array('as' => 'fichacategoria_tags', 'uses' => 'AdmFichaCategoriaController@getTags') );
	
	
	# MODULO 'Configuracion'
	
	Route::get('adm/configuracion/{id}', array('as' => 'configuracion_editar', 'uses' => 'AdmConfiguracionController@getEditar') );
	Route::post('adm/configuracion/{id}/editar', array('as' => 'configuracion_editar_post', 'uses' =>'AdmConfiguracionController@postEditar') );
	
});



/*
|--------------------------------------------------------------------------
| Servicios
|--------------------------------------------------------------------------
*/


Route::get('/svc/generico/{elemento}/{id?}', 'SvcGenericoController@getServicio');




/*
|--------------------------------------------------------------------------
| ADMIN Login / Logout
|--------------------------------------------------------------------------
*/

Route::get('adm/login', array('as' => 'adm_login', 'uses' => 'AdmLoginController@getLogin') );

Route::post('adm/login', 'AdmLoginController@postLogin');

Route::get('logout', array('as' => 'logout', function()
{
	Auth::logout();
	return Redirect::to( route('adm_login') );
}));