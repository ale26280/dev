<?php

/*
 *
 * 		Controller para administracion de "PORTFOLIO"
 *
 */
class AdmConfiguracionController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $configuracion = NULL;
	
	

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
	
		EDITAR SLIDER :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$configuracion = Configuracion::find( $id )->toArray();
		

		# Vista
		
		Return View::make('admin/configuracion/editar')
			->with('registro', $configuracion );
	}
	
	
	
	/*
	
		SLIDER EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {

	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'titulo' => 'required',
			'descripcion' => '',
			'metadatos' => '',
			'analytics' => '',
			'video' => 'required',
			'telefono' => '',
			'contacto' => 'required|email',
			'contacto_copia' => 'email',
			'latitud' => '',
			'longitud' => '',
			'url_facebook' => 'url',
			'url_twitter' => 'url',
			'url_googleplus' => 'url',
			'url_linkedin' => 'url'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('configuracion_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}		
		
		
		
		/** PROCESAR LA URL DE VIDEO Y DISTINGUIR VIMEO / YOUTUBE **/
		
		$videoCode = "";
		$videoSitio = "";
		$arUrl = explode("/", $input['video']);
		
		if ( strlen($arUrl[ count($arUrl) - 1 ]) == 8 && is_numeric( $arUrl[ count($arUrl) - 1 ] ) ) {
			
			$videoCode = $arUrl[ count($arUrl) - 1 ];
			$videoSrc = "vimeo";
		
		} else {
		
			$arUrl = explode("=", $arUrl[ count($arUrl) - 1 ]);
			
			if ( strlen($arUrl[ count($arUrl) - 1 ]) == 11 ) {
			
				$videoCode = $arUrl[ count($arUrl) - 1 ];
				$videoSrc = "youtube";
				
			} else {
			
				$this->response['error'] = '1';
				$this->response['error_message'] = $arUrl[ count($arUrl) - 1 ];
				echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
				exit();
			}
		
		}
		
		unset($arUrl);
			
			
		
		# Actualizacion del registro
		
		$configuracion = Configuracion::find( $id );
		$configuracion->titulo = $input['titulo'];
		$configuracion->video = $videoCode;
		$configuracion->descripcion = $input['descripcion'];
		$configuracion->metadatos = $input['metadatos'];
		$configuracion->analytics = $input['analytics'];
		$configuracion->telefono = $input['telefono'];		
		$configuracion->contacto = $input['contacto'];
		$configuracion->contacto_copia = $input['contacto_copia'];
		$configuracion->latitud = $input['latitud'];
		$configuracion->longitud = $input['longitud'];
		$configuracion->url_facebook = $input['url_facebook'];
		$configuracion->url_twitter = $input['url_twitter'];
		$configuracion->url_googleplus = $input['url_googleplus'];
		$configuracion->url_linkedin = $input['url_linkedin'];
		
		$configuracion->save();
		
		Return Redirect::to( route('configuracion_editar', 1) );
	}
	
	
	
	
	
} // END CLASS


?>