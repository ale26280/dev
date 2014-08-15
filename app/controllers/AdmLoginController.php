<?php

/*
 *
 * 		LOGIN CONTROLLER
 *
 */
class AdmLoginController extends BaseController {


	/*
	
		ARMADO DE VISTA DEL LOGIN
	
	*/
	Public function getLogin() {
	
		return View::make('admin/login');
		
	}
	
	
	
	/*
	
		PROCESAMIENTO DEL LOGIN
	
	*/
	Public function postLogin() {
	
    	$input = array(
    		'username' => Input::get('username'),
    		'password' => Input::get('password')
    	);

    	$rules = array(
		    'username' => 'required',
		    'password'  => 'required'

		);
		

    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() )
		{	
    		return Redirect::to('adm/login')->withErrors( $validation->messages() );
		}
  
  
    	if ( Auth::attempt( $input ) )
    	{
		
			if ( Auth::user()->perfil_id == 1 ) {

				return Redirect::to('adm/dashboard');
				
			}
    	}
		
    	$wrong_user = "El usuario o la contraseña son incorrectos.";
    	return Redirect::to('adm/login')->withErrors( array('wrong_user' => $wrong_user) );
	}
}

?>