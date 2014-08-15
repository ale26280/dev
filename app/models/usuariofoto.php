<?php

class UsuarioFoto extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuario_foto';
	
	
	
	Public function usuario() {
	
		Return $this->belongsTo("Usuario");
	}
	

}

?>
