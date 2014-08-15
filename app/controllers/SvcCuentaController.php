<?php

/*
 *
 * 		SERVICIOS DE CUENTAS DE USUARIO
 *
 */
Class SvcCuentaController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'total' => 0, 'data' => array());
	
	/*
	
			# Custom error messages
			
			if ( $validation->messages()->first('email') ) {
				$this->response['error_message'] = $validation->messages()->first('file');
			}
	
	*/
	
	/*
	
		CONTROL DE SESION - Cuando sea necesario
		
	*/
	Public function __construct() {
	
	}
	
	

	/*
	
		REGISTRO
	
	*/
	Public function postRegistro( ) {
	
	
		# Validación
		
		$input = Input::all();
		
		$rules = array(
			'email' => 'required|email',
			'nombre' => 'required',
			'apellido' => 'required',
			'password' => 'required|min:6',
			'password_confirm' => 'required|min:6',
			'mobile_id' => ''
			
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {
		
			$this->response['error'] = 1;

			foreach ( $validation->messages()->toArray() as $msg ) {
				
				$this->response['error_message'][] = $msg[0];	
			}
			
		}
		
		
		# Validacion contraseñas
		
		if ( $input['password'] != "" && $input['password'] != $input['password_confirm'] ) {

			$this->response['error'] = 1;
			$this->response['error_message'][] =  'Los passwords no coinciden.';
		}
		
		


		
		
		# Agrego el nuevo usuario
		
		if ( $this->response['error'] == 0 ) {
		
			try { 
			
				# Verificamos que no exista ya el usuario / email
				
				$check = Usuario::where("username", "=", Input::get('email') )->count();
				
				if ( $check >= 1 ) {
				
					$this->response['error'] = 1;
					$this->response['error_message'][] =  'El email ingresado ya esta registrado.';
					
					exit();
				}			

				
				# Usuario
				
				$usuario = new Usuario();
				
				$usuario->perfil_id = 2;
				$usuario->username = Input::get('email');
				$usuario->password = Hash::make( Input::get('password') );
				$usuario->last_ip = $_SERVER['REMOTE_ADDR'];
				$usuario->last_login = date("Y-m-d h:i:s");
				
				$usuario->save();	
				
				
				# Ficha de usuario
				
				$ficha = new UsuarioFicha();
				
				$ficha->usuario_id = $usuario->id;
				$ficha->email = Input::get('email');
				$ficha->nombre = Input::get('nombre');
				$ficha->apellido = Input::get('apellido');
				$ficha->fecha_nacimiento = Input::get('fecha_nacimiento');
				$ficha->fecha_registro =  date("Y-m-d h:i:s");
				$ficha->last_mobile_id = Input::get('mobile_id');	

				$ficha->save();

				
				# User hash
				$usuario->remember_token = Hash::make( $usuario->id . time() );
				$usuario->save();
				
				
				
				# Envio el email de confirmacion
				
				
				
				
			
			} catch ( Exception $e ) {
			
				$this->response['error'] = 1;
				$this->response['error_message'] = $e->getMessage();
			}
		
		}
		

		# Enviamos respuesta satisfactoria
		
		if ( $this->response['error'] == 0 ) {
		
			$this->response['success'] = 1;			
			$this->response['data'] = $usuario->toArray();
		}
		
	}



	
	/*
	
		LOGIN
	
	*/
	Public function postLogin( ) {
	
	
		# Validación
		
		$input = Input::all();
		
		$rules = array(
			'email' => 'required|email',
			'password' => 'required|min:6',
			'mobile_id' => 'required'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {
		
			$this->response['error'] = 1;

			foreach ( $validation->messages()->toArray() as $msg ) {
				
				$this->response['error_message'][] = $msg[0];	
			}
			
		}

		
		
		# Busco el usuario
		
		try { 

			if ( Auth::attempt( array('username' => $input['email'], 'password' => $input['password'], 'active' => 1, 'perfil_id' => 2) ) ) {				
				
				# Si es un usuario valido
				$this->response['success'] = 1;				
				
			} else {
			
				$this->response['error'] = 1;
				$this->response['error_message'] = "El usuario o la contrase&ntilde;a son invalidos";
			}
		
		
		} catch ( Exception $e ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}
		
	}

	
	
	
	/*
	
		CONFIRMACION
	
	*/
	Public function getConfirmacion( $email, $token ) {
	
	
		# Validación
		
		$input = array(
			'email' => $email,
			'token' => $token
		);
		
		$rules = array(
			'email' => 'required|email',
			'token' => 'required'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "Los datos de confirmaci&oacute;n son inv&aacute;lidos o han expirado";

			exit();
		}
	
	
	
	
		# Elimino registros que tengan mas de 24hs
		
		$ayer = date("Y-m-d H:i:s", strtotime("-1 day"));
		
		Usuario::where("perfil_id", "=", 2)->where("active", "=", 0)->where("created_at", "<=", $ayer)->delete();
		
		
		# Buscamos el usuario
		
		$usuario = Usuario::where("username", "=", $input['email'])->where("remember_token", "=", $input['token'])->where("active", "=", 0)->get();
		

		# Si el usuario existe
		
		if ( count($usuario) == 1 ) {
		
			$usuario[0]->active = 1;
			$usuario[0]->save();
			
			$this->response['success'] = 1;
			$this->response['data'] = $usuario->toArray();
		
		} else {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "El registro es inexistente o ha expirado";
		}
		
	}
	
	
	
	
	/*
	
		RECOVERY REQUEST
	
	*/
	Public function postRecoveryReq() {
	
	
		# Validación
		
		$input = Input::all();
		
		$rules = array(
			'email' => 'required|email'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "El email ingresado es inv&aacute;lido.";

			exit();
		}
		
		
		# Busco la cuenta de usuario
	
		$usuario = Usuario::where("perfil_id", "=", 2)->where("active", "=", 1)->where("username", "=", $input['email'])->get();
		
		if ( count($usuario) == 1 ) {
		
			$usuario[0]->recovery = 1;
			$usuario[0]->remember_token = Hash::make( $usuario[0]->id . time() );
			$usuario[0]->recovery_date = Date("Y-m-d H:i:s");
			$usuario[0]->save();
			
			$this->response['success'] = 1;
			
			
			# Enviamos el email
			
			
			
			
			
			
			
			
		
		
		} else {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "El usuario es inv&aacute;lido.";
		}

	}
	
	
	
	/*
	
		CONFIRMACION
	
	*/
	Public function postRecoveryChange() {
	
	
		# Validación
		
		$input = Input::all();
		
		$rules = array(
			'email' => 'required|email',
			'token' => 'required',
			'password' => 'required|min:6',
			'password_confirm' => 'required|min:6',
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = "Los datos de confirmaci&oacute;n son inv&aacute;lidos o han expirado";

			exit();
		}
		
		
		# Validacion contraseñas
		
		if ( $input['password'] != "" && $input['password'] != $input['password_confirm'] ) {
		
			$this->response['error'] = 1;
			$this->response['error_message'][] =  'Los passwords no coinciden.';
		}
		
		
		
		# Buscamos el usuario
		
		$usuario = Usuario::where("username", "=", $input['email'])->where("remember_token", "=", $input['token'])->where("recovery", "=", 1)->where("active", "=", 1)->get();
		
		if ( count( $usuario ) == 1 ) {
		
			$usuario[0]->password = Hash::make( $input['password'] );
			$usuario[0]->recovery = 0;
			$usuario[0]->save();
			
			$this->response['success'] = 1;
			$this->response['data'] = $usuario[0]->toArray();
			
		} else {
		
			$this->response['error'] = 1;
			$this->response['error_message'][] =  'Los datos de usuario son inv&aacute;lidos o han expirado.';
		}
	
	}
	
	
	
	
	/*
	
		DESTRUIR OBJETO - Al final de cualquier servicio imprimo un JSON
	
	*/
	Public function __destruct()  {
	
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		
	}
	
	
} // END CLASS


?>