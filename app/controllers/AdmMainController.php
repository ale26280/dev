<?php

/*
 *
 * 		Controller General de Administracion de Contenidos
 *
 */
class AdmMainController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $usuario = NULL;

	

	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		# CONTROL DE PERMISOS - Si el usuario no es de perfil ADMINISTRADOR forzamos la salida
		
		if ( Auth::check() && Auth::user()->perfil_id == 1 ) {
		
			$this->usuario =Administrador::find( Auth::id() );

		} else {
		
			exit("No tiene permiso para acceder a este sitio");
		}
	}
	
	
	
	/*
	
		DASHBOARD del CMS
		
	*/
	Public function getDashboard() {
	

	
		Return View::make('admin/dashboard');
	}
	
	
	
} // END CLASS


?>