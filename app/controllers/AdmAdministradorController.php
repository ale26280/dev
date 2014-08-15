<?php

/*
 *
 * 		Controller de Administradores CMS
 *
 */
class AdmAdministradorController extends BaseController {

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
	
		ADMINISTRADORES :: LISTAR
		
	*/
	Public function getListar() {
		
		$usuarios = Usuario::where('perfil_id', '=', 1)->get();
		
		Return View::make('admin/administrador/listar')
			->with('registros', $usuarios->toArray() );
	}
	
	
	
	/*
	
		EDITAR USUARIO :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$usuario = Usuario::find( $id );
	
		$usuario = $usuario->toArray();
		
		
		# Busco perfiles habilitados
		
		$perfiles = Perfil::where('admin', '=', 1)->get();
		$arPerfiles = array();
		
		foreach ( $perfiles as $perfil ) {
			
			$arPerfiles[$perfil->id] = $perfil->titulo;
		}
		

		# Vista
		
		Return View::make('admin/administrador/editar')
			->with('registro', $usuario )
			->with('perfiles', $arPerfiles );
			
	}
	
	

	/*
	
		USUARIO AGREGAR :: VISTA
		
	*/
	Public function getAgregar() {
	
		# Busco perfiles habilitados
		
		$perfiles = Perfil::where('admin', '=', 1)->get();
		$arPerfiles = array();
		
		foreach ( $perfiles as $perfil ) {
			$arPerfiles[$perfil->id] = $perfil->titulo;
		}
		

		# Vista
		
		Return View::make('admin/administrador/agregar')
			->with('perfiles', $arPerfiles );		
	}	
	
	
	
	/*
	
		USUARIO EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {
	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'perfil_id' => 'required|numeric',
			'nombre' => 'required',
			'apellido' => 'required',
			'email' => 'required|email',
			'password' => 'min:6',
			'password_confirm' => 'min:6'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('administrador_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		# Validacion contraseñas
		
		if ( $input['password'] != "" && $input['password'] != $input['password_confirm'] ) {
		
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('password_confirm', 'Los passwords no coinciden');

			Return Redirect::to( route('administrador_editar', $id) )
				->withErrors( $messages )
				->withInput();		
		}
		
		
		# Actualizacion del registro
		
		$usuario = Usuario::find( $id );
		$usuario->perfil_id = $input['perfil_id'];
		$usuario->nombre = $input['nombre'];
		$usuario->apellido = $input['apellido'];
		$usuario->email = $input['email'];
		$usuario->username = $input['email'];
		
		# Actualizar password solo si es solicitado
		
		if ( strlen($input['password']) >= 6 ) {
		
			$usuario->password = Hash::make( $input['password'] );
		}
		
		$usuario->save();

		Return Redirect::to( route('administrador_listar') );
	}
	
	
	
	/*
	
		USUARIO AGREGAR :: POST
		
	*/
	Public function postAgregar() {
	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'perfil_id' => 'required|numeric',
			'nombre' => 'required',
			'apellido' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:6',
			'password_confirm' => 'required|min:6'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('administrador_agregar') )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		# Validacion contraseñas
		
		if ( $input['password'] != $input['password_confirm'] ) {
		
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('password_confirm', 'Los passwords no coinciden');

			Return Redirect::to( route('administrador_agregar') )
				->withErrors( $messages )
				->withInput();		
		}
		
		
		# Actualizacion del registro
		
		$usuario = new Usuario();
		$usuario->perfil_id = $input['perfil_id'];
		$usuario->username = $input['username'];
		$usuario->nombre = $input['nombre'];
		$usuario->apellido = $input['apellido'];
		$usuario->email = $input['email'];
		$usuario->username = $input['email'];		
		$usuario->password = Hash::make( $input['password'] );
		
		$usuario->save();
		
		# Vista
		
		Return Redirect::to( route('administrador_listar') );
	}
	
	
	
	/*
	
		USUARIO BORRAR :: POST (ajax)
		
	*/
	Public function getBorrar( $id ) {
	
		try {
		
			# Busco el usuario
			
			$usuario = Usuario::find( $id );

			# Borro el usuario
			
			$usuario->delete();
		
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}

		
		# Respuesta
		
		$this->response['success'] = 1;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
} // END CLASS


?>