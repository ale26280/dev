<?php

/*
 *
 * 		SERVICIOS CONTROLLER
 *
 */
Class SvcUsuarioController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'total' => 0, 'totalRegistros' => 0, 'data' => array());
	
	Private $usuario = NULL;
	
	
	
	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		if ( !Auth::check() )
		{			
			exit("Debe ingresar al sistema");
		}
		
		$this->usuario = Usuario::find( Auth::id() );
		
		return true;
	}
	

	
	/*
	
		OBTENER EL PERFIL DE OTRO USUARIO
	
	*/
	Public function getPerfil( $perfilId ) {
	
		# Validacion por ID de perfil
		
		if ( !is_numeric($perfilId) ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "Perfil inexistente";
			exit();		
		}
		
		
		# Busco el perfil
		
		$usuario = Usuario::where("perfil_id", "=", 2)->where("active", "=", 1)->where("id", "=", $perfilId)->get();
		
		if ( count($usuario) == 1 ) {
		
			$fotos = $usuario[0]->fotos->toArray();
			$ficha = $usuario[0]->ficha->toArray();
			$ficha['fotos'] = $fotos;
			
		
			$this->response['success'] = 1;
			$this->response['data'] = $ficha;
		}

	}



	
	/*
	
		CARGAR UNA FOTO EN LA GALERIA
	
	*/
	Public function postUploadFoto() {
	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'file' => 'required|mimes:jpeg,jpg,png,gif',
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			$this->response['error'] = 1;
			$this->response['error_message'] = "El archivo especificado no es valido.";
			exit();
		}
		
		
		# Procesamiento de uploads: imagen principal
		
		if ( ($file = Input::file( 'file' )) ) {
		
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fotos/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['file'] = $filename;
			}
		}
		
		
		# Guardamos la foto
		
		$foto = new UsuarioFoto();
		$foto->fuente = $input['file'];
		$foto->usuario_id = Auth::user()->id;
		$foto->save();
		
		
		# Respuesta
		
		$this->response['success'] = 1;
		$this->response['data'] = $foto->toArray();

	}

	
	/*
	
		DESTRUIR OBJETO - Al final de cualquier servicio imprimo un JSON
	
	*/
	Public function __destruct()  {
	
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		
	}
	
	
} // END CLASS


?>