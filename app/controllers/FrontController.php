<?php

/*
 *
 * 		Controller general para FRONT END
 *
 */
class FrontController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $slider = NULL;
	
	
	
	/*
	
		CONFIRMACION
	
	*/
	Public function getRecoveryConfirmacion( $email, $token ) {
	
	
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
	
	
	
	
		# Expiramos recoveries que tengan mas de 24hs
		
		$ayer = date("Y-m-d H:i:s", strtotime("-1 day"));
		
		$expirados = Usuario::where("perfil_id", "=", 2)->where("active", "=", 1)->where("recovery", "=", 1)->where("recovery_date", "<=", $ayer)->get();
		
		if ( count($expirados) > 0 ) {
		
			foreach ( $expirados as $usuario ) {
			
				$usuario->recovery = 0;
				$usuario->recovery_date = null;
				$usuario->save();			
			}
		
		}
		
		unset($expirados, $usuario);

		
		# Buscamos el usuario
		
		$usuario = Usuario::where("username", "=", $input['email'])->where("remember_token", "=", $input['token'])->where("active", "=", 1)->get();
		

		# Si el usuario existe
		
		if ( count($usuario) == 1 ) {
			
			Return View::make('recovery-change')
				->with('email', $input['email'] )
				->with('token', $input['token'] );
		
		} else {
		
			exit("El registro es inexistente o ha expirado");
		}
		
	}

	
	
	
}

	
?>