<?php

/*
 *
 * 		Controller para administracion de "SLIDERS"
 *
 */
class AdmSliderController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $slider = NULL;
	
	

	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		# CONTROL DE PERMISOS - Si el usuario no es de perfil ADMINISTRADOR forzamos la salida
		
		if ( Auth::check() && Auth::user()->perfil_id == 1 ) {
		
			$this->usuario = Slider::find( Auth::id() );

		} else {
		
			exit("No tiene permiso para acceder a este sitio");
		}
	}
	
	
	
	/*
	
		SLIDERS :: LISTAR
		
	*/
	Public function getListar() {
		
		$sliders = Slider::all();
		
		Return View::make('admin/slider/listar')
			->with('registros', $sliders->toArray() );
	}
	
	
	
	/*
	
		EDITAR SLIDER :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$slider = Slider::find( $id );
		
		$fotos = $slider->fotos->toArray();		
		
		$slider = $slider->toArray();
		

		# Vista
		
		Return View::make('admin/slider/editar')
			->with('registro', $slider)
			->with('fotos', $fotos);
	}
	
	

	/*
	
		SLIDER AGREGAR :: VISTA
		
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

	

		$fotos = Temporal::where('modulo', '=', 'slider')->where('tipo', '=', 'foto')->orderBy('orden')->get()->toArray();
	
		# Vista
		
		Return View::make('admin/slider/agregar')
			->with('fotos', $fotos);
	}	
	
	
	
	/*
	
		SLIDER EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {

	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria' => 'required'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('slider_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		
		# Procesamiento de uploads: imagen principal
		
		if ( ($file = Input::file( 'imagen' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/sliders/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['imagen'] = $filename;
			}
		}
		
		
		# Actualizacion del registro
		
		$slider = Slider::find( $id );
		$slider->categoria = $input['categoria'];
		
		$slider->save();
		
		Return Redirect::to( route('slider_listar') );
	}
	
	
	
	/*
	
		SLIDER AGREGAR :: POST
		
	*/
	Public function postAgregar() {

		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria' => 'required'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('slider_agregar') )
				->withErrors( $validation->messages() )
				->withInput();
		}

		
		
		# Actualizacion del registro
		
		$slider = new Slider();
		$slider->categoria = $input['categoria'];
		
		$slider->save();
		
		
		# Agregamos fotos
		
		$fotos = Temporal::where('modulo', '=', 'slider')->where('tipo', '=', 'foto')->get();
		
		foreach ( $fotos as $f ) {
		
			rename($_SERVER['DOCUMENT_ROOT'] . '/storage/temporales/' . $f->fuente, $_SERVER['DOCUMENT_ROOT'] . '/storage/sliders/' . $f->fuente);
		
			$pf = new SliderFoto();
			$pf->slider_id = $slider->id;
			$pf->fuente = $f->fuente;
			$pf->url = $f->data_a;			
			$pf->orden = $f->orden;
			$pf->save();
			
			$f->delete();
		}
		
		
		# Vista
		
		Return Redirect::to( route('slider_listar') );
	}
	
	
	
	/*
	
		SLIDER BORRAR :: POST (ajax)
		
	*/
	Public function getBorrar( $id ) {
	
		try {
		
			# Busco el usuario
			
			$slider = Slider::find( $id );

			# Borro el usuario
			
			$slider->delete();
		
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}

		
		# Respuesta
		
		$this->response['success'] = 1;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	
	/*
	
		EDITAR FOTO EN GALERIA :: Traer la info de la foto
		
	*/
	Public function postFotoGet( $id, $temporal = false ) {
	
		$data = array();
		
		try {
		
			if ( $temporal != false ) {
			
				$foto = Temporal::find( $id );
				$data = array(
					'id' => $foto->id, 
					'url' => $foto->data_a
				);
			
			} else {
			
				$foto = SliderFoto::find( $id );
				$data = array(
					'id' => $foto->id, 
					'url' => $foto->url
				);
			}
			
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage;
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}
		

		# Respuesta
		
		$this->response['success'] = 1;
		$this->response['data'] = $data;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
	
	/*
	
		EDITAR FOTO EN GALERIA :: POST
		
	*/
	Public function postFotoEditar( $temporal = false ) {
	
		$input = Input::all();
		
		$rules= array(
			'url' => ''
		);
		
        $validation = Validator::make($input, $rules);
		
        if ( $validation->fails() )
        {
		
			// Creamos la respuesta en formato JSon
			$this->response['error'] = 1;

			if ( $validation->messages()->first('url') ) {
				$this->response['error_message'] = $validation->messages()->first('url');
			}
			
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}
		
	
		/** Actualizo el registro **/
		
		try {

			if ( $temporal == false ) {
			
				$foto = SliderFoto::find( $input['id'] );
				$foto->url = $input['url'];
				$foto->save();

			} else {
			
				$foto = Temporal::find( $input['id'] );
				$foto->data_a = $input['url'];
				$foto->save();
			}
			
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}
		

		# Respuesta
		
		$this->response['success'] = 1;
		$this->response['data'] = $foto->toArray();
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
			
			if ( $input['tipo'] == 'video' && !is_numeric( $sliderId ) ) {
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
				$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/sliders/';
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				
				# Agregamos la foto
				$foto = new SliderFoto();
				$foto->slider_id = $elementoId;
				$foto->fuente =   $filename;
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

		BORRAR Uploads
	
	*/	
	Public function getBorrarUpload( $tipo, $registroId ) {
	
		try {
		
			switch ($tipo) {
			
				case 'foto':
					$registro = SliderFoto::find($registroId);
					break;
					
				case 'video':
					$registro = SliderVideo::find($registroId);
					break;
					
				case 'temporal':
					$registro = Temporal::find($registroId);
					break;
			}
	
		
			/* Busco el archivo relacionado */

			$file = $tipo == 'temporal' ? "/storage/temporales/" . $registro->fuente : "/storage/sliders/" . $registro->fuente;

			
			/* Elimino el registro */
			
			$registro->delete();
			
			
			/* Elimino el archivo */
			if ( is_file(($_SERVER['DOCUMENT_ROOT'] . $file)) ) {
				unlink($_SERVER['DOCUMENT_ROOT'] . $file);
				
			}	else {
			
				$this->response['error'] = '1';
				$this->response['error_message'] = $_SERVER['DOCUMENT_ROOT'] . $file;
				echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
				exit();
			}

			

			
		} catch ( Exception $e ) {
		
			$this->response['error'] = '1';
			$this->response['error_message'] = $e->getMessage();
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}
			
		/* Respuesta JSON */
		
		$this->response = json_encode($this->response);
		echo $this->response;		
	}
	
	

	/*
	
		REORDENAR elementos de galeria
	
	*/
	Public function postFotosReorder( $tipo = "SliderFoto" )
    {

		try {

				$obj = "SliderFoto";
				
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