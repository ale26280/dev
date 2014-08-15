<?php

class Tag extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';
	
	
	# PORTFOLIOS
	
	Public function fichas() {
	
		return $this->belongsToMany('Ficha', 'Tag');
	}
	
	
	# CATEGORIAS
	
	Public function categorias() {
	
		return $this->belongsToMany('FichaCategoria', 'categoria_tag');
	}
	

	# PORTFOLIO: MEDIA
	
	Public function fichasMedia() {
	
		return $this->belongsToMany('Ficha', 'ficha_tags')->where('categoria_id', '=', 3);
	}
	
	# PORTFOLIO: MEDIA
	
	Public function fichasExperience() {
	
		return $this->belongsToMany('Ficha', 'ficha_tags')->where('categoria_id', '=', 2);
	}
	
	# PORTFOLIO: ENTERTAIMENT
	
	Public function fichasEntertaiment() {
	
		return $this->belongsToMany('Ficha', 'ficha_tags')->where('categoria_id', '=', 1);
	}
	
	# PORTFOLIO: ACTIVACIONES
	
	Public function fichasActivaciones() {
	
		return $this->belongsToMany('Ficha', 'ficha_tags')->where('categoria_id', '=', 4);
	}
	
}

?>