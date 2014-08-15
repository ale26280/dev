<?php

class Perfil extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'perfiles';
	
	
	
	# RETURN usuarios
	
	Public function usuarios() {
	
		return $this->hasMany('usuario');
	}
	
	
	
	
	
}


?>