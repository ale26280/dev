<?php

/*
 *
 * 		Controller para administracion de "FICHAS"
 *
 */
class AdmFichaCategoriaController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $fichaCategoria = NULL;
	
	

	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		# CONTROL DE PERMISOS - Si el usuario no es de perfil ADMINISTRADOR forzamos la salida
		
		if ( Auth::check() && Auth::user()->perfil_id == 1 ) {
		
			$this->usuario = FichaCategoria::find( Auth::id() );

		} else {
		
			exit("No tiene permiso para acceder a este sitio");
		}
	}
	
	
	
	/*
	
		FICHAS :: LISTAR
		
	*/
	Public function getListar() {
		
		$fichaCategorias = FichaCategoria::all();
		
		
		Return View::make('admin/ficha_categoria/listar')
			->with('registros', $fichaCategorias->toArray() );
	}
	
	
	
	/*
	
		EDITAR FICHA :: VISTA
		
	*/
	Public function getEditar( $id ) {
	
		$fichaCategoria = FichaCategoria::find( $id );
	

		# Tags seleccionados
		$tags_selected = $fichaCategoria->tags;

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
		
		Return View::make('admin/ficha_categoria/editar')
			->with('registro', $fichaCategoria)
			->with('tags', $arTags);
	}
	
	

	/*
	
		FICHA AGREGAR :: VISTA
		
	*/
	Public function getAgregar() {	

		# Tags
		$tags = Tag::all();
		$arTags = array();
		foreach ($tags as $tag) {
			$arTags[ $tag->id ] = $tag->tag;
		}		
		unset($tags);
		
		
		# Categorias
		$categorias = FichaCategoria::all();
		
		$arCat = array();
		foreach ($categorias as $cat) {
			$arCat[ $cat->id ] = $cat->categoria;
		}
		
		unset($categorias);
		
		
		# Vista
		
		Return View::make('admin/ficha_categoria/agregar')
			->with('categorias', $arCat)
			->with('tags', $arTags);
	}	
	
	
	
	/*
	
		FICHA EDITAR :: POST
		
	*/
	Public function postEditar( $id ) {

	
		# Validacion
		
		$input = Input::all();
		
		$rules = array(
			'categoria' => 'required',
			'tags' => 'required'
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {	

			Return Redirect::to( route('fichaCategoria_editar', $id) )
				->withErrors( $validation->messages() )
				->withInput();
		}
		
		

		
		# Actualizacion del registro
		
		$fichaCategoria = FichaCategoria::find( $id );
		$fichaCategoria->categoria = $input['categoria'];
		$fichaCategoria->save();
		
		
		# Agregamos TAGS
		
		$fichaCategoria->tags()->sync( $input['tags'] );
		

		# VISTA
		
		Return Redirect::to( route('fichacategoria_listar') );
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
			'titulo' => 'required',
			'copete' => '',
			'banner' => '',
			'fichaCategoria' => '',
			'info' => '',
			'web' => '',
			'blog' => '',
			'orden_home' => '',
			'orden_interior' => '',
			'tags' => 'required'			
		);
		
    	$validation = Validator::make($input, $rules);

		if ( $validation->fails() ) {

			Return Redirect::to( route('fichaCategoria_agregar') )
				->withErrors( $validation->messages() )
				->withInput();
		}

		
		# Procesamiento de uploads: imagen principal
		
		if ( ($file = Input::file( 'imagen_principal' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichaCategorias/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['imagen_principal'] = $filename;
			}
		}
		
		# Procesamiento de uploads: banner
		
		if ( ($file = Input::file( 'banner' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichaCategorias/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['banner'] = $filename;
			}
		}
		
		
		# Procesamiento de uploads: presentacion (pdf)
		
		if ( ($file = Input::file( 'presentacion' )) ) {
			$extension = $file->getClientOriginalExtension();

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/fichaCategorias/presentaciones/';
			$filename = md5( Auth::user()->id . microtime() ) . "." . $extension;		
			$uploadSuccess = $file->move($destinationPath, $filename);
			
			if ( $uploadSuccess ) {
				$input['presentacion'] = $filename;
			}
		}

		
		# Creamos del registro
		
		$fichaCategoria = new FichaCategoria();
		$fichaCategoria->categoria_id = $input['categoria_id'];
		$fichaCategoria->imagen_principal = $input['imagen_principal'];
		$fichaCategoria->presentacion = $input['presentacion'];		
		$fichaCategoria->titulo = $input['titulo'];
		$fichaCategoria->copete = $input['copete'];
		$fichaCategoria->banner = $input['banner'];
		$fichaCategoria->fichaCategoria = $input['fichaCategoria'];
		$fichaCategoria->info = $input['info'];
		$fichaCategoria->web = $input['web'];
		$fichaCategoria->blog = $input['blog'];
		$fichaCategoria->orden_home = $input['orden_home'];
		$fichaCategoria->orden_interior = $input['orden_interior'];		
		
		$fichaCategoria->save();
		
		
		# Agregamos TAGS
		
		$fichaCategoria->tags()->sync( $input['tags'] );
		
		
		# Vista
		
		Return Redirect::to( route('fichaCategoria_listar') );
	}
	
	
	
	/*
	
		FICHA BORRAR :: POST (ajax)
		
	*/
	Public function getBorrar( $id ) {
	
		try {
		
			# Busco el usuario
			
			$fichaCategoria = FichaCategoria::find( $id );

			# Borro el usuario
			
			$fichaCategoria->delete();
		
		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
		}

		
		# Respuesta
		
		$this->response['success'] = 1;
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	/*
	
		DEVUELVE TAGS por CATEGORIA
	
	*/
	Public function getTags( $idCategoria, $idParent = null ) {
	
		$tags = FichaCategoria::find( $idCategoria )->tags;
		
		
		
		$tags = $tags->toArray();
		
		# Respuesta
		
		$this->response['success'] = 1;
		$this->response['data'] = $tags;
		
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
} // END CLASS


?>