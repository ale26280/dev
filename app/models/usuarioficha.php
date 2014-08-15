<?php

class UsuarioFicha extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuario_ficha';
	
	
	
	Public function usuario() {
	
		Return $this->belongsTo("Usuario");
	}
	

}

?>
