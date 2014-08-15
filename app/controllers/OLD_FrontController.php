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
	
		HOME 
	
	*/
	Public function getHome() {	
	
		#
		#	CONFIGURACION
		#
		
		$config = Configuracion::find(1)->toArray();
		
		
		#
		#	FOTO HOME
		#
		$fotoHome = Slider::where('categoria', '=', 'home')->first()->get();
		$fotoHome = $fotoHome[0]->fotos->toArray();
		$fotoHome = $fotoHome[0]['fuente'];		

		
		
		
	
	
		#
		#	PORTFOLIO
		#

		// Busco TAGS para cada CATEGORIA
		
		$filtro = array();
		$filtro['tmp_entertaiment'] = FichaCategoria::find( 1 )->tags->toArray();
		$filtro['tmp_experience'] = FichaCategoria::find( 2 )->tags->toArray();
		$filtro['tmp_media'] = FichaCategoria::find( 3 )->tags->toArray();
		
		
		
		foreach ($filtro['tmp_entertaiment'] as $tag) {
		
			if ( Tag::find( $tag['id'] )->fichasEntertaiment()->count() > 0 ) {
				$filtro['entertaiment'][] = $tag;
			}
		}
		
		unset($filtro['tmp_entertaiment']);
		

		foreach ($filtro['tmp_experience'] as $tag) {
		
			if ( Tag::find( $tag['id'] )->fichasExperience()->count() > 0 ) {
				$filtro['experience'][] = $tag;
			}
		}
		
		unset($filtro['tmp_experience']);
		
		
		foreach ($filtro['tmp_media'] as $tag) {
		
			if ( Tag::find( $tag['id'] )->fichasMedia()->count() > 0 ) {
				$filtro['media'][] = $tag;
			}
		}
		
		unset($filtro['tmp_media']);
		
		
		// Busco fichas
	
		$entertaiment = Ficha::where('categoria_id', '=', 1)->where('destacada', '=', 1)->orderBy('orden_home', 'ASC')->take(8)->get();
		$experience = Ficha::where('categoria_id', '=', 2)->where('destacada', '=', 1)->orderBy('orden_home', 'ASC')->take(8)->get();
		$media = Ficha::where('categoria_id', '=', 3)->where('destacada', '=', 1)->orderBy('orden_home', 'ASC')->take(8)->get();
		
		
		#
		#	SLIDERS
		#
		
		$sliderClientes = Slider::where('categoria', '=', 'clientes')->get();
	

	
		# VISTA
		
		return View::make("home")
			->with('fotoHome', $fotoHome)
			->with('config', $config)
			->with('sliderClientes', $sliderClientes)
			->with('filtro', $filtro)
			->with('media', $media)
			->with('entertaiment', $entertaiment)
			->with('experience', $experience);
	}
	
	
	
	/*
	
		PORTFOLIO
	
	*/
	Public function getPortfolio( $id ) {
	
		#
		#	PORTFOLIO
		#
		
		$portfolio = Ficha::find( $id );
	
		return View::make("portfolio")
			->with('portfolio', $portfolio);
	}
	
	
	/*
	
		TRAER FICHAS
	
	*/
	Public function getFichas( $cat, $index ) {
	
		switch ($cat) {
		
			case 'exp':
				$items = Ficha::where('categoria_id', '=', 2)->where('destacada', '=', 0)->orderBy('orden_home', 'DESC')->orderBy('orden_home', 'ASC')->take(8)->skip($index)->get();
				$next = Ficha::where('categoria_id', '=', 2)->where('destacada', '=', 0)->count() - ($index + 8);
			break;
			
			case 'ent':
				$items = Ficha::where('categoria_id', '=', 1)->where('destacada', '=', 0)->orderBy('orden_home', 'DESC')->orderBy('orden_home', 'ASC')->take(8)->skip($index)->get();
				$next = Ficha::where('categoria_id', '=', 1)->where('destacada', '=', 0)->count() - ($index + 8);
			break;

			case 'med':
				$items = Ficha::where('categoria_id', '=', 3)->where('destacada', '=', 0)->orderBy('orden_home', 'DESC')->orderBy('orden_home', 'ASC')->take(8)->skip($index)->get();
				$next = Ficha::where('categoria_id', '=', 3)->where('destacada', '=', 0)->count() - ($index + 8);
			break;

		}

		
		# TAGS
		
		foreach ($items as $item) {
		
			$item->tags = $item->tags->toArray();
		
		}
		
		$this->response['success'] = 1;
		$this->response['next'] = $next;
		$this->response['data'] = $items->toArray();
		
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	
	/*
	
		CONTACTO
	
	*/
	Public function postContacto() {

	try {
	
		$config = Configuracion::find(1)->toArray();
	
		
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'name' => 'required',
			'mail' => 'required',
			'subject' => 'mimes:pdf',
			'message' => 'required',
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('/') )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		//sumbission data
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$date = date('d/m/Y');
		$time = date('H:i:s');
		
		//form data
		$name = Input::get('name');	
		$email = Input::get('mail');
		$subject = Input::get('subject');
		$message = Input::get('message');
		
		$mailfrom = "no-reply@generaciondeideas.com.ar";	
		$mailsubject = "Contacto web";
		$mailmensaje = "<p>Nuevo mensaje de contacto via web:</p>
			<p><strong>Name: </strong>".$name." </p>
			<p><strong>Email Address: </strong> ".$email." </p>
			<p><strong>Subject: </strong> ".$subject." </p>
			<p><strong>Message: </strong> ".$message." </p>
			<br/>
			<p>This message was sent from the IP Address: ".$ipaddress." on ".$date." at ".$time."</p>";

		// Headers
		$headers = "From: " . $from . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		mail($config['contacto'], $mailsubject, $mailmensaje, $headers);
		
		
		if ( $config['contacto_copia'] != "" ) {
		
			mail($config['contacto_copia'], $mailsubject, $mailmensaje, $headers);
		}

		echo "Mail Sent";
		
		
		} catch ( Exception $e ) {
		
			echo $e->getMessage();
		}
	}
	
	
	
	
	/*
	
		NEWSLETTER
	
	*/
	Public function postNewsletter() {
	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'name' => 'required',
			'mail' => 'required',
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('/') )
				->withErrors( $validation->messages() )
				->withInput();
		}

		
		# Guardamos la data
		
		$news = new Newsletter();
		$news->nombre = Input::get('name');
		$news->correo = Input::get('mail');
		
		$news->save();
		
		echo "Mail Sent";
	}
	
	
	
	
	
	
	
}	// END CLASS


?>