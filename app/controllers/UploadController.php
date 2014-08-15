<?php

/*
 *
 * 		UPLOAD DE ARCHIVOS
 *
 */
class UploadController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'success' => 0, 'data' => array());

	Private $usuario = NULL;

	
	/*
	
		CONTROL DE SESION
		
	*/
	Public function __construct() {
	
		if ( Auth::check() ) {
		
			$this->usuario = Usuario::find( Auth::id() );
		}
	}
	
	
	
	/*
	
		CARGA DE ARCHIVOS
		
	*/
	Public function postUpload() {
	
		# Control de usuario
		
		if ( $this->usuario === NULL ) {
			$this->response['error'] = 1;
			$this->response['error_message'] = "Debe iniciar sesion para realizar esta operacion";
			
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}		
		

        # Validamos el archivo y directorio
		
		$file = Input::file('file');

        $rules = array(
            'file' => 'required',
			'directorio' => 'required|numeric'
        );

        $validation = Validator::make( array('file' => $file, 'directorio' => Input::get('directorio')), $rules);	

        if ( $validation->fails() ) {

			$mensajes = "";
			foreach ( $validation->messages()->all() as $msg ) {
				$mensajes .= $msg . "<br/>";
			}
			
			$this->response['error'] = 1;
			$this->response['error_message'] = $mensajes;
			
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
        }
		

		# Cargo el archivo y lo muevo al directorio correspondiente

		try {

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . "/storage/user" . $this->usuario->id . "/";
			$extension = $file->getClientOriginalExtension();			
			$filename = sha1( Auth::user()->id.uniqid() ) . "." . $extension;	
			$originalName = $file->getClientOriginalName();
			
			$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			
			$filesize = filesize( $destinationPath . $filename );
			$filesizeHuman = $filesize / 1024;
			
			
			# Si es mayor a 1024Kb, mostramos "megas"
			
			if ( $filesizeHuman >= 1024 ) {
			
				$filesizeHuman = number_format( $filesizeHuman / 1024, 2, ",", ".") . "Mb";
				
			} else {
			
				$filesizeHuman = number_format( $filesizeHuman, 2, ",", ".") . "Kb";
			}
			
			
			# Verificamos el TIPO de archivo
			
			switch ( strtolower($extension) ) {
			
				/* COMPRESION */
				case 'rar':
					$tipo = "Rar";
					break;
					
				case 'zip':
					$tipo = "Zip";
					break;
					
				case 'gz':
					$tipo = "Zip";
					break;

				case 'gzip':
					$tipo = "Zip";
					break;					
			
			
				/* AUDIO */
				case 'wav':
					$tipo = "Audio";
					break;
					
				case 'mp3':
					$tipo = "Audio";
					break;
					
				case 'flac':
					$tipo = "Audio";
					break;

				case 'mid':
					$tipo = "Audio";
					break;	
					
					
				/* IMAGEN */
				case 'gif':
					$tipo = "Img";
					break;
					
				case 'png':
					$tipo = "Img";
					break;
					
				case 'jpg':
					$tipo = "Img";
					break;

				case 'bmp':
					$tipo = "Img";
					break;
					
					
					
				/* OFFICE / DOC */
				
				case 'pdf':
					$tipo = "Pdf";
					break;				
					
				case 'doc':
					$tipo = "Word";
					break;	

				case 'xls':
					$tipo = "Word";
					break;
					
				case 'docx':
					$tipo = "Word";
					break;
					
				case 'txt':
					$tipo = "Word";
					break;
					
				
				/* POR DEFECTO */
				
				default:
					$tipo = "Otro";
					break;
			}
			
			

		} catch (Exception $e) {
		
			$this->response['error'] = 1;
			$this->response['error_message'] = $e->getMessage();
			
			echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
			exit();
		}


	

        # Guardo la nueva informacion 
		
        if( $uploadSuccess ) {

			/* Creamos el nuevo objeto */

			try {
			
				$archivo = New Archivo();
				$archivo->usuario_id = $this->usuario->id;
				$archivo->directorio_id = Input::get('directorio');
				$archivo->fuente = $filename;
				$archivo->hash = md5( time() . $this->usuario->id . $filename );
				$archivo->nombre = $originalName;
				$archivo->peso = $filesizeHuman;
				$archivo->bytes = $filesize;
				$archivo->tipo = $tipo;
				$archivo->save();
				
			} catch (Exception $e) {
			
				$this->response['error'] = 1;
				$this->response['error_message'] = $e->getMessage();
				
				echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
				exit();
			}			

			
			# Creamos la respuesta en formato JSon
			
			$this->response['sucess'] = 1;
			$this->response['data'] = array(
				'id' => $archivo->id,
				'nombre' => $archivo->nombre,
				'peso' => $archivo->peso
			);
			
        }

		
		if ( Input::get('iframe') == 1) {
		
			echo "<script> parent.postUpload('". $archivo->id ."', '".addslashes($originalName)."','".$filesizeHuman."'); </script>";
			
			exit();
		}
		
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	
	
} // END CLASS

?>