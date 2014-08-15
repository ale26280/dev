/*******************************************************

	- LOGIN / INSCRIPCION -

*******************************************************/

var currentDir = 0;

$("DOCUMENT").ready(function() {


	/*
	
		Aplico eventos
	
	*/
	
	// INSCRIPCION
	$("A#btnInscripcionAceptar").click( inscripcion );

	
	// RECOVERY
	$("A#btnRecoveryEnviar").click( recovery );
	
	
	// CONFIRMAR RESET
	$("A#btnResetConfirmar").click( resetPassword );

});



// INSCRIPCION

function inscripcion() {

	var url = $("#btnInscripcion").data('inscripcion');

	// Envío la peticion al server
	
	$.ajax({
	
		url: url,
		method : 'post',
		data : $("#formInscripcion").serialize(),
		dataType: 'json'
		
	}).success(function(resp) {

		if ( resp.error == 1) {
			
			$("SPAN#inscripcion_error").html( resp.error_message );
			$("SPAN#inscripcion_error").fadeIn();
			
			setTimeout( function() { $("SPAN#inscripcion_error").fadeOut(); }, 4000 );
				
			return false;
		}
		
		$("#formInscripcion")[0].reset();
		
		$(".inscripcion-modal").modal("hide");
		
		setTimeout(function(){
			$("#modalInscripcionOk").modal("show");
		}, 500);
		
	});
	
	return false;
}


// RECUPERACION CONTRASEÑA

function recovery() {

	var url = $("#btnRecovery").data('recovery');

	// Envío la peticion al server
	
	$.ajax({
	
		url: url,
		method : 'POST',
		data : $("#formRecovery").serialize(),
		dataType: 'json'
		
	}).success(function(resp) {

		if ( resp.error == 1) {
			
			$("SPAN#recovery_error").html( resp.error_message );
			$("SPAN#recovery_error").fadeIn();
			
			setTimeout( function() { $("SPAN#recovery_error").fadeOut(); }, 4000 );
				
			return false;
		}
		
		$(".recovery-modal").modal("hide");
		
		setTimeout(function() {
			$("#modalRecoveryOk").modal("show");
		}, 500);

	}).error(function(x, status, error) {
	
		alert("Error AJAX - " + x.status + ": status: "+ status +" - " + error);		
		return false;
	});
	
	return false;
}


// RESETEAR CONTRASEÑA

function resetPassword() {

	var url = $("#btnResetConfirmar").data('reset');

	// Envío la peticion al server
	
	$.ajax({
	
		url: url,
		method : 'POST',
		data : $("#formReset").serialize(),
		dataType: 'json'
		
	}).success(function(resp) {

		if ( resp.error == 1) {

			$("DIV#reset_error").html( resp.error_message );
			$("DIV#reset_error").fadeIn();

			return false;
		}


		$("#modalRecoveryOk").modal("show");
		
		
		

	}).error(function(x, status, error) {
	
		if ( x.status == 500 ) {
		
			alert("ERROR 500: " + error);
			return false;
		
		} else {
	
			alert("Error AJAX - " + x.status + ": status: "+ status +" - " + error);		
			return false;
		}
		
	});

	return false;
}
