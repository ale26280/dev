<?php

/*
 *
 * 		DOWNLOAD CONTROLLER
 *
 */
class DownloadController extends BaseController {


	/*
	
		Dispatcher de descargas
	
	*/
	Public function getDownload( $hash ) {
	
		$archivo = Archivo::where('hash', '=', $hash )->get();
		
		if ( count($archivo) == 1 ) {

			$file = $_SERVER['DOCUMENT_ROOT'] . "/storage/user" . $archivo[0]->usuario_id . "/" . $archivo[0]->fuente;
			
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($archivo[0]->nombre).'"');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			
		} else {
		
			return Response::view('404', array(), 404);
		}
	
	}
	
	

} // END CLASS

?>