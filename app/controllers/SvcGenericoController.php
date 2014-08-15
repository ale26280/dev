<?php

/*
 *
 * 		SERVICIOS CONTROLLER
 *
 */
Class SvcGenericoController extends BaseController {

	Private $response = array('error' => 0, 'error_message' => '', 'total' => 0, 'totalRegistros' => 0, 'data' => array());
	
	Private $usuario = NULL;
	
	


	/*
	
		SERVICIO GENERICO
	
	*/
	Public function getServicio( $elemento, $id = null ) {
	
		$registros = $elemento::all();
		$total = count($registros);
		
		$arRegistros = $registros->toArray();
		
		for ($i=0;$i<$total;$i++) {
		
			if ( method_exists( $registros[$i], 'fotos' ) && count( $registros[$i]->fotos ) > 0 ) {
			
				$arRegistros[$i]['fotos'] = $registros[$i]->fotos->toArray();
			
			}
			
			
			if ( method_exists( $registros[$i], 'videos' ) && count( $registros[$i]->videos ) > 0 ) {
			
				$arRegistros[$i]['videos'] = $registros[$i]->videos->toArray();
			
			}
		
		}
		
		$this->response['data'] = $registros->toArray();
	}



	
	
	
	/*
	
		DESTRUIR OBJETO - Al final de cualquier servicio imprimo un JSON
	
	*/
	Public function __destruct()  {
	
		echo json_encode($this->response, JSON_HEX_QUOT | JSON_HEX_TAG);
		
	}
	
	
} // END CLASS


?>