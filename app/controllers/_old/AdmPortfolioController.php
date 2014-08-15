<?php

/*
 *
 * 		Controller para administracion de "PORTFOLIO"
 *
 */
class AdmPortfolioController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $portfolio = NULL;
	
	

	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		# CONTROL DE PERMISOS - Si el usuario no es de perfil ADMINISTRADOR forzamos la salida
		
		if ( Auth::check() && Auth::user()->perfil_id == 1 ) {
		
			$this->usuario = Portfolio::find( Auth::id() );

		} else {
		
			exit("No tiene permiso para acceder a este sitio");
		}
	}
	
	
	
	/*
	
		SLIDERS :: LISTAR
		
	*/
	Public function getListar() {
		
		$portfolios = Portfolio::all();
		$arPortfolios = array();
		
		foreach ($portfolios as $p) {
			$tmp = $p->toArray();
			$tmp['categoria'] = $p->categoria->categoria;
			
			$arPortfolios[] = $tmp;
			
		}

		Return View::make('admin/portfolio/listar')
			->with('registros', $arPortfolios );
	}
	
	
	
	/*
	
		EDITAR SLIDER :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$portfolio = Portfolio::find( $id );
		
		# Videos
		$videos = $portfolio->videos->toArray();
		
		# Fotos
		$fotos = $portfolio->fotos->toArray();
		
		# Tags seleccionados
		$tags_selected = $portfolio->tags;

		
		# Portfolio
		$portfolio = $portfolio->toArray();
		
		# Categorias
		$categorias = PortfolioCategoria::all();
		
		$arCat = array();
		foreach ($categorias as $cat) {
			$arCat[ $cat->id ] = $cat->categoria;
		}
		
		unset($categorias);
		
		# Organizacion TAGS > Tags Seleccionados
		$tags = Tag::all();
		$arTags = array();
		foreach ($tags as $tag) {
		
			$tmp = array(
				'id' => $tag->id,
				'tag' => $tag->tag,
				'selected' => false
			);
			
			foreach ($tags_selected as $selected) {	
				if ($tag->id == $selected->id) {
					$tmp['selected'] = true;
					break;
				}
			}
			
			$arTags[] = $tmp;
		}
		unset($tags);
		

		# Vista
		
		Return View::make('admin/portfolio/editar')
			->with('categorias', $arCat )
			->with('registro', $portfolio )
			->with('fotos', $fotos )
			->with('videos', $videos )
			->with('tags', $arTags );
	}
	
	

	/*
	
		SLIDER AGREGAR :: VISTA
		
	*/
	Public function getAgregar() {
	
		# VIDEOS
		$videos = Temporal::where('modulo', '=', 'portfolio')->where('tipo', '=', 'video')->get()->toArray();
		
		# FOTOS
		$fotos = Temporal::where('modulo', '=', 'portfolio')->where('tipo', '=', 'foto')->get()->toArray();
		
		# CATEGORIAS
		$categorias = PortfolioCategoria::all();
		
		$arCat = array();
		foreach ($categorias as $cat) {
			$arCat[ $cat->id ] = $cat->categoria;
		}		
		unset($categorias);
		
		
		# TAGS
		$tags = Tag::all();
		$arTags = array();
		foreach ($tags as $tag) {
			$arTags[ $tag->id ] = $tag->tag;
		}		
		unset($tags);
		
		
		
		# Vista
		
		Return View::make('admin/portfolio/agregar')
			->with('categorias', $arCat)
			->with('fotos', $fotos)
			->with('videos', $videos)
			->with('tags', $arTags);		
	}	
	
	
	
	/*
	
		SLIDER EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {

	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria_id' => 'required|numeric',
			'titulo' => 'required',
			'subtitulo' => 'required',
			'descripcion' => '',
			'orden' => 'numeric'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('portfolio_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}		
		
		
		# Actualizacion del registro
		
		$portfolio = Portfolio::find( $id );
		$portfolio->categoria_id = $input['categoria_id'];
		$portfolio->titulo = $input['titulo'];
		$portfolio->subtitulo = $input['subtitulo'];
		$portfolio->descripcion = $input['descripcion'];
		$portfolio->orden = $input['orden'];
		
		$portfolio->save();
		
		
		# Agregamos TAGS
		
		$portfolio->tags()->sync( $input['tags'] );
		
		
		# VISTA
		
		Return Redirect::to( route('portfolio_listar') );
	}
	
	
	
	/*
	
		SLIDER AGREGAR :: POST
		
	*/
	Public function postAgregar() {

		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria_id' => 'required|numeric',
			'titulo' => 'required',
			'subtitulo' => 'required',
			'descripcion' => '',
			'orden' => 'numeric'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('portfolio_agregar') )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		# Actualizacion del registro
		
		$portfolio = new Portfolio();
		$portfolio->categoria_id = $input['categoria_id'];
		$portfolio->titulo = $input['titulo'];
		$portfolio->subtitulo = $input['subtitulo'];
		$portfolio->descripcion = $input['descripcion'];
		$portfolio->orden = $input['orden'];
		
		$portfolio->save();
		
		
		# Agregamos fotos
		
		$fotos = Temporal::where('modulo', '=', 'portfolio')->where('tipo', '=', 'foto')->get();
		
		foreach ( $fotos as $f ) {
		
			$pf = new PortfolioFoto();
			$pf->portfolio_id = $portfolio->id;
			$pf->fuente = $f->fuente;
			$pf->orden = $f->orden;
			$pf->save();
			
			$f->delete();
		}
		
		
		
		# Agregamos videos
		
		$videos = Temporal::where('modulo', '=', 'portfolio')->where('tipo', '=', 'video')->get();
		
		foreach ( $videos as $v ) {
		
			$pv = new PortfolioVideo();
			$pv->portfolio_id = $portfolio->id;
			$pv->fuente = $v->fuente;
			$pv->orden = $v->orden;
			$pv->save();
			
			$v->delete();
		}
		
		

		# Agregamos TAGS
		
		$portfolio->tags()->sync( $input['tags'] );
		
		
		
		
		# Vista
		
		Return Redirect::to( route('portfolio_listar') );
	}
	
	
	
	/*
	
		SLIDER BORRAR :: POST (ajax)
		
	*/
	Public function getBorrar( $id ) {
	
		try {
		
			# Busco el usuario
			
			$portfolio = Portfolio::find( $id );

			# Borro el usuario
			
			$portfolio->delete();
		
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}

		
		# Respuesta
		
		$this->response['success'] = 1;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	
	
	
	/*
	
		AGREGAR IMAGENES A LA GALERIA TEMPORAL
	
	*/
	Public function postFotoUpload( $elementoId = NULL ) {
	
		
        /** VALIDAR LA NUEVA INFORMACION **/	
        $input = Input::all();
		
		$file = Input::file('file'); // your file upload input field in the form should be named 'file'

		$input['file'] = $file;
		
        $rules = array(
            'file' => 'required|mimes:jpg,jpeg,gif,png',
			'elemento' => 'required',
			'tipo' => 'required'
        );

        $validation = Validator::make($input, $rules);
		
        if ( $validation->fails() )
        {
		
			// Creamos la respuesta en formato JSon
			$this->response['error'] = 1;

			if ( $validation->messages()->first('file') ) {
				$this->response['error_message'] = $validation->messages()->first('file');
			}
			
			if ( $validation->messages()->first('elemento') ) {
				$this->response['error_message'] = "Error interno: falta definir 'elemento' en el form de envio";
			}
			
			if ( $validation->messages()->first('tipo') ) {
				$this->response['error_message'] = "Error interno: falta definir 'tipo=temporal|video' en el form de envio";
			}
			
			if ( $input['tipo'] == 'video' && !is_numeric( $portfolioId ) ) {
				$this->response['error_message'] = "Error interno: falta definir 'elemento_id' en el form de envio";
			}
			
			$this->response = json_encode($this->response);
			echo $this->response;
			exit();
        }


		
		/** MOVER ARCHIVO Y GUARDAR LA NUEVA INFORMACION **/
		
		try {
			
			$extension = $file->getClientOriginalExtension();			
			$filename = sha1( Auth::user()->id.uniqid() ) . "." . $extension;	
			
			if ( $input['tipo'] == 'temporal' ) {
			
				# Procesamos el archivo 
				$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/temporales/';
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);

				# Almacenamos el temporal
				$foto = new Temporal();
				$foto->fuente = '/storage/temporales/' . $filename;
				$foto->tipo = "foto";
				$foto->time = time();
				$foto->modulo = $input['elemento'];
				$foto->save();
				
			} elseif ( $input['tipo'] == 'foto' ) {
			
				# Procesamos el archivo 
				$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fotos/';
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				
				# Agregamos la foto
				$foto = new PortfolioFoto();
				$foto->portfolio_id = $elementoId;
				$foto->fuente =  '/storage/fotos/' . $filename;
				$foto->save();				
			
			}
			
			
			# Salida JSON
			
			$this->response['success'] = 1;
			$this->response['id'] = $foto->id;
			$this->response['elemento'] = $input['elemento'];
			$this->response['tipo'] = $input['tipo'];
			$this->response['fuente'] = $foto->fuente;
				


		} catch (Exception $e) {
		
			$this->response['error'] = '1';
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		}
		
		
		/** RESPUESTA **/

		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	
	
	
	
	/*
	
		AGREGAR VIDEOS DE VIMEO / YOUTUBE A LA GALERIA TEMPORAL
	
	*/
	Public function postVideoAgregar( $portfolioId = NULL ) {
	
		
        /** VALIDAR LA NUEVA INFORMACION **/	
        $input = Input::all();
				
        $rules = array(
            'video' => 'required|url',
			'elemento' => 'required',
			'tipo' => 'required'
        );

        $validation = Validator::make($input, $rules);

        if ( $validation->fails() )
        {
			// Creamos la respuesta en formato JSon
			$response['error'] = '1';

			if ( $validation->messages()->first('video') ) {
				$response['error_message'] = "El URL ingresado es incorrecto";
			}
			
			if ( $validation->messages()->first('elemento') ) {
				$response['error_message'] = "Error interno: falta definir 'elemento' en el form de envio";
			}
			
			if ( $validation->messages()->first('tipo') ) {
				$response['error_message'] = "Error interno: falta definir 'tipo=temporal|video' en el form de envio";
			}
			
			if ( $input['tipo'] == 'video' && !is_numeric( $portfolioId ) ) {
				$response['error_message'] = "Error interno: falta definir 'elemento_id' en el form de envio";
			}
			
			$response = json_encode($response);
			echo $response;
			exit();
        }
		
		
		
		
		try {
		

			/** PROCESAR LA URL DE VIDEO Y DISTINGUIR VIMEO / YOUTUBE **/
			
			$videoCode = "";
			$videoSitio = "";
			$arUrl = explode("/", $input['video']);
			
			if ( strlen($arUrl[ count($arUrl) - 1 ]) == 8 && is_numeric( $arUrl[ count($arUrl) - 1 ] ) ) {
				
				$videoCode = $arUrl[ count($arUrl) - 1 ];
				$videoSrc = "vimeo";
			
			} else {
			
				$arUrl = explode("=", $arUrl[ count($arUrl) - 1 ]);
				
				if ( strlen($arUrl[ count($arUrl) - 1 ]) == 11 ) {
				
					$videoCode = $arUrl[ count($arUrl) - 1 ];
					$videoSrc = "youtube";
					
				} else {
				
					$this->response['error'] = '1';
					$this->response['error_message'] = $arUrl[ count($arUrl) - 1 ];
					echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
					exit();
				}
			
			}
			
			unset($arUrl);
			

			/** GUARDAR LA NUEVA INFORMACION **/
			
			if ( $input['tipo'] == 'temporal' ) {

				# Almacenamos el temporal
				$video = new Temporal();
				$video->fuente = $videoCode;
				$video->tipo = "video";
				$video->data_a = $videoSrc;
				$video->time = time();
				$video->modulo = $input['elemento'];
				$video->save();
				
			} elseif ( $input['tipo'] == 'video' ) {
			
				# Agregamos el video
				$video = new PortfolioVideo();
				$video->portfolio_id = $portfolioId;
				$video->fuente = $videoCode;
				$video->sitio = $videoSrc;
				$video->save();				
			
			}
			
			
			# Salida JSON
			
			$this->response['code'] = $videoCode;
			$this->response['sitio'] = $videoSrc;
			$this->response['success'] = 1;
			$this->response['id'] = $video->id;
			$this->response['elemento'] = $input['elemento'];
			$this->response['tipo'] = $input['tipo'];
			

		



		} catch (Exception $e) {
		
			$this->response['error'] = '1';
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}
		
		
		/** RESPUESTA **/

		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	
	/*

		BORRAR Uploads
	
	*/	
	Public function getBorrarUpload( $elemento, $tipo, $registroId ) {
	
		try {
		
			$modelo = $elemento . $tipo;
		
			switch ($tipo) {
			
				case 'foto':
					$registro = $modelo::find($registroId);
					break;
					
				case 'video':
					$registro = PortfolioVideo::find($registroId);
					break;
					
				case 'temporal':
					$registro = Temporal::find($registroId);
					break;
			}
	
		
			/* Busco el archivo relacionado */

			$file = $tipo == 'temporal' ? $registro->fuente : $registro->fuente;

			
			/* Elimino el registro */
			
			$registro->delete();
			
			
			/* Elimino el archivo */
			if ( is_file(($_SERVER['DOCUMENT_ROOT'] . $file)) ) {
				unlink($_SERVER['DOCUMENT_ROOT'] . $file);
			}	

			

			
		} catch ( Exception $e ) {
		
			$this->response['error'] = '1';
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		}
			
		/* Respuesta JSON */
		
		$this->response = json_encode($this->response);
		echo $this->response;		
	}
	
} // END CLASS


?>