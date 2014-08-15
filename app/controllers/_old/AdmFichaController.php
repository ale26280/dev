<?php

/*
 *
 * 		Controller para administracion de "FICHAS"
 *
 */
class AdmFichaController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $ficha = NULL;
	
	

	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		# CONTROL DE PERMISOS - Si el usuario no es de perfil ADMINISTRADOR forzamos la salida
		
		if ( Auth::check() && Auth::user()->perfil_id == 1 ) {
		
			$this->usuario = Ficha::find( Auth::id() );

		} else {
		
			exit("No tiene permiso para acceder a este sitio");
		}
	}
	
	
	
	/*
	
		FICHAS :: LISTAR
		
	*/
	Public function getListar() {
		
		$fichas = Ficha::orderBy('destacada')->orderBy('orden_home')->get();
		
		$arFichas = array();
		
		foreach ($fichas as $f) {
			$tmp = $f->toArray();
			$tmp['categoria'] = $f->categoria->categoria;
			
			$arFichas[] = $tmp;
			
		}
		
		Return View::make('admin/ficha/listar')
			->with('registros', $arFichas );
	}
	
	
	
	/*
	
		EDITAR FICHA :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$ficha = Ficha::find( $id );

		# Fotos
		$fotos = $ficha->fotos->toArray();
		
		# Videos
		$videos = $ficha->videos->toArray();		

		# Tags seleccionados
		$tags_selected = $ficha->tags;

		# Organizacion TAGS > Tags Seleccionados
		$tags = $ficha->categoria->tags;
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
		
		
		# Categorias
		$categorias = FichaCategoria::all();
		
		$arCat = array();
		foreach ($categorias as $cat) {
			$arCat[ $cat->id ] = $cat->categoria;
		}
		
		unset($categorias);
		
	
		# Array
		$ficha = $ficha->toArray();
		
		

		# Vista
		
		Return View::make('admin/ficha/editar')
			->with('categorias', $arCat)
			->with('fotos', $fotos)
			->with('videos', $videos )
			->with('registro', $ficha)
			->with('tags', $arTags);
	}
	
	

	/*
	
		FICHA AGREGAR :: VISTA
		
	*/
	Public function getAgregar() {	
	
		/* Limpieza de temporales */
		
		$basura = Temporal::where('time', '<', time() - 300)->get();
		
		foreach ($basura as $b) {

			$file = $_SERVER['DOCUMENT_ROOT'] . "/storage/temporales/" . $b->fuente;
			
			if ( file_exists( $file ) )
			unlink( $file );
			
			$b->delete();
		}
		

		# Videos
		$videos = Temporal::where('modulo', '=', 'ficha')->where('tipo', '=', 'video')->orderBy('orden')->get()->toArray();

		# Fotos
		$fotos = Temporal::where('modulo', '=', 'ficha')->where('tipo', '=', 'foto')->orderBy('orden')->get()->toArray();

		# Tags
		$tags = FichaCategoria::orderBy('categoria')->first()->tags;
		$arTags = array();
		foreach ($tags as $tag) {
			$arTags[ $tag->id ] = $tag->tag;
		}		
		unset($tags);
		
		
		# Categorias
		$categorias = FichaCategoria::orderBy('categoria')->get();
		
		$arCat = array();
		foreach ($categorias as $cat) {
			$arCat[ $cat->id ] = $cat->categoria;
		}
		
		unset($categorias);
		
		
		# Vista
		
		Return View::make('admin/ficha/agregar')
			->with('categorias', $arCat)
			->with('videos', $videos)			
			->with('fotos', $fotos)
			->with('tags', $arTags);
	}	
	
	
	
	/*
	
		FICHA EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {

	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria_id' => 'required',
			'imagen_principal' => '',
			'titulo' => 'required',
			'fecha' => 'required',
			'cliente' => '',
			'copete' => '',
			'banner' => '',
			'presentacion' => 'mimes:pdf',
			'ficha' => '',
			'info' => '',
			'web' => '',
			'blog' => '',
			'orden_home' => '',
			'orden_interior' => '',
			'tags' => 'required',
			'destacada' => ''
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('ficha_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		# Procesamiento de uploads: imagen principal
		
		if ( ($file = Input::file( 'imagen_principal' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['imagen_principal'] = $filename;
			}
		}
		
		# Procesamiento de uploads: banner
		
		if ( ($file = Input::file( 'banner' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/presentaciones/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['banner'] = $filename;
			}
		}
		
		# Procesamiento de uploads: presentacion (pdf)
		
		if ( ($file = Input::file( 'presentacion' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['presentacion'] = $filename;
			}
		}
		
		
		# Actualizacion del registro
		
		$ficha = Ficha::find( $id );
		$ficha->categoria_id = $input['categoria_id'];
		$ficha->imagen_principal = $input['imagen_principal'] != "" ? $input['imagen_principal']:$ficha->imagen_principal;
		$ficha->titulo = $input['titulo'];
		$ficha->fecha = $input['fecha'];
		$ficha->cliente = $input['cliente'];		
		$ficha->copete = $input['copete'];
		$ficha->banner = $input['banner'] != "" ? $input['banner']:$ficha->banner;
		$ficha->ficha = $input['ficha'];
		$ficha->info = $input['info'];
		$ficha->web = $input['web'];
		$ficha->blog = $input['blog'];
		$ficha->destacada = $input['destacada'];				
		$ficha->orden_home = $input['orden_home'];
		$ficha->orden_interior = $input['orden_interior'];		
		
		$ficha->save();
		
		
		# Agregamos TAGS
		
		$ficha->tags()->sync( $input['tags'] );
		

		# VISTA
		
		Return Redirect::to( route('ficha_listar') );
	}
	
	
	
	/*
	
		FICHA AGREGAR :: POST
		
	*/
	Public function postAgregar() {

		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria_id' => 'required',
			'imagen_principal' => 'required',
			'presentacion' => 'mimes:pdf',
			'fecha' => 'required',
			'cliente' => '',
			'titulo' => 'required',
			'copete' => '',
			'banner' => '',
			'ficha' => '',
			'info' => '',
			'web' => '',
			'blog' => '',
			'orden_home' => '',
			'orden_interior' => '',
			'tags' => 'required',
			'destacada' => ''
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('ficha_agregar') )
				->withErrors( $validation->messages() )
				->withInput();
		}

		
		# Procesamiento de uploads: imagen principal
		
		if ( ($file = Input::file( 'imagen_principal' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['imagen_principal'] = $filename;
			}
		}
		
		# Procesamiento de uploads: banner
		
		if ( ($file = Input::file( 'banner' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['banner'] = $filename;
			}
		}
		
		
		# Procesamiento de uploads: presentacion (pdf)
		
		if ( ($file = Input::file( 'presentacion' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/presentaciones/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['presentacion'] = $filename;
			}
		}

		
		# Creamos del registro
		
		$ficha = new Ficha();
		$ficha->categoria_id = $input['categoria_id'];
		$ficha->imagen_principal = $input['imagen_principal'];
		$ficha->presentacion = $input['presentacion'];		
		$ficha->titulo = $input['titulo'];
		$ficha->fecha = $input['fecha'];
		$ficha->cliente = $input['cliente'];		
		$ficha->copete = $input['copete'];
		$ficha->banner = $input['banner'];
		$ficha->ficha = $input['ficha'];
		$ficha->info = $input['info'];
		$ficha->web = $input['web'];
		$ficha->blog = $input['blog'];
		$ficha->destacada = $input['destacada'];		
		$ficha->orden_home = $input['orden_home'];
		$ficha->orden_interior = $input['orden_interior'];		
		
		$ficha->save();
		
		
		# Agregamos TAGS
		
		$ficha->tags()->sync( $input['tags'] );
		
		
		# Agregamos videos
		
		$videos = Temporal::where('modulo', '=', 'ficha')->where('tipo', '=', 'video')->get();
		
		foreach ( $videos as $v ) {
		
			$pv = new FichaVideo();
			$pv->ficha_id = $ficha->id;
			$pv->fuente = $v->fuente;
			$pv->orden = $v->orden;
			$pv->sitio = $v->data_a;
			$pv->save();
			
			$v->delete();
		}
		
		
		# Agregamos fotos
		
		$fotos = Temporal::where('modulo', '=', 'ficha')->where('tipo', '=', 'foto')->get();
		
		foreach ( $fotos as $f ) {
		
			rename($_SERVER['DOCUMENT_ROOT'] . '/storage/temporales/' . $f->fuente, $_SERVER['DOCUMENT_ROOT'] . '/storage/fichas/' . $f->fuente);
		
			$pf = new FichaFoto();
			$pf->ficha_id = $ficha->id;
			$pf->fuente = $f->fuente;
			$pf->orden = $f->orden;
			$pf->save();
			
			$f->delete();
		}
		
		
		
		# Vista
		
		Return Redirect::to( route('ficha_listar') );
	}
	
	
	
	/*
	
		FICHA BORRAR :: POST (ajax)
		
	*/
	Public function getBorrar( $id ) {
	
		try {
		
			# Busco el usuario
			
			$ficha = Ficha::find( $id );

			# Borro el usuario
			
			$ficha->delete();
		
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}

		
		# Respuesta
		
		$this->response['success'] = 1;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	/*
	
		AGREGAR VIDEOS DE YOUTUBE A LA GALERIA TEMPORAL
	
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
			
			if ( $input['tipo'] == 'video' && !is_numeric( $fichaId ) ) {
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
				$foto->fuente = $filename;
				$foto->tipo = "foto";
				$foto->time = time();
				$foto->modulo = $input['elemento'];
				$foto->save();
				
			} elseif ( $input['tipo'] == 'foto' ) {
			
				# Procesamos el archivo 
				$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fotos/fichas/';
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				
				# Agregamos la foto
				$foto = new FichaFoto();
				$foto->ficha_id = $elementoId;
				$foto->fuente =  $filename;
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
	Public function postVideoAgregar( $fichaId = NULL ) {
	
		
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
			
			if ( $input['tipo'] == 'video' && !is_numeric( $fichaId ) ) {
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
				$video = new FichaVideo();
				$video->ficha_id = $fichaId;
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
		
			switch ($tipo) {
			
				case 'foto':
					$registro = FichaFoto::find($registroId);
					break;
					
				case 'video':
					$registro = FichaVideo::find($registroId);
					break;
					
				case 'temporal':
					$registro = Temporal::find($registroId);
					break;
			}
	
		
			/* Busco el archivo relacionado */

			$file = $tipo == 'temporal' ? '/storage/temporales/' . $registro->fuente :  '/storage/fotos/fichas/' .$registro->fuente;

			
			/* Elimino el registro */
			
			$registro->delete();
			
			
			/* Elimino el archivo */
			if ( is_file(($_SERVER['DOCUMENT_ROOT'] . $file)) ) {			
				unlink($_SERVER['DOCUMENT_ROOT'] . $file);
			}	else { echo "No existe"; exit(); }

			

			
		} catch ( Exception $e ) {
		
			$this->response['error'] = '1';
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		}
			
		/* Respuesta JSON */
		
		$this->response = json_encode($this->response);
		echo $this->response;		
	}
	
	
	
	/*
	
		REORDENAR elementos de galeria
	
	*/
	Public function postFotosReorder( $tipo = "FichaFoto" )
    {

		try {

				$obj = "FichaFoto";
				
				if ( $tipo == 'temporal') {
				
					$obj = "Temporal";
				}
				
				$orden = explode(",",Input::get('order'));
				$total = count($orden) -1;

				for ($i=0;$i<$total;$i++) {
					$foto = $obj::find( $orden[$i] );
					$foto->orden = $i;
					$foto->save();
				}

		} catch ( Exception $e ) {

			echo $e->getMessage();
		}

    } 
	
	
	
	
	
	/*
	
		REORDENAR elementos de galeria
	
	*/
	Public function postVideosReorder( $tipo = "FichaVideo" )
    {

		try {

				$obj = "FichaVideo";
				
				if ( $tipo == 'temporal') {
				
					$obj = "Temporal";
				}
				
				$orden = explode(",",Input::get('order'));
				$total = count($orden) -1;

				for ($i=0;$i<$total;$i++) {
					$foto = $obj::find( $orden[$i] );
					$foto->orden = $i;
					$foto->save();
				}

		} catch ( Exception $e ) {

			echo $e->getMessage();
		}

    } 	
	
	
} // END CLASS


?>