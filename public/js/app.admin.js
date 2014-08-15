$(document).ready(function(){


	/**************************************************************
	
						BLOQUE INTERFAZ
		
	***************************************************************/

	//
	//	TAGS por CATEGORIA :: 'GDI Fichas'
	//
	$("#comboCategoria").change(function() {
	
		var id = $(this).val();
		var url = $(this).data('svc') + "/" + id;
		
		$.ajax({
		
			url: url,
			method : "post",
			dataType: "json"
			
		}).success(function(resp) {

			$("#selected_tags").html("");
			$("#mm_tags_list").html("");

			var html = "";
			
			for (i=0;i<resp.data.length;i++) {
				html += "<li><input type='checkbox' name='tags[]' value='"+ resp.data[i].id +"' id='chk_opcion_tags_"+ resp.data[i].id +"'> <span id='nombre_tags_"+ resp.data[i].id +"'>"+ resp.data[i].tag +"</span></li>";
			}
			
			setTimeout(function() {
				$("#mm_tags_list").html(html);
				
				$("[id^='chk_opcion_tags_']").click(function() {
				
//					if ( $(this).attr('checked') ) {

					var id = this.id.match(/\d+/);
				
					var LI = $(document.createElement("LI"));
					LI.html("<span id='tags_"+ id +"'>"+ $("SPAN#nombre_tags_"+ id).text() +"</span><a class='delete_option' data-element='tags' data-elementid='"+ id +"' href='javascript:void%200;' title='Eliminar este tag'>X</a></li>");
					LI.attr("id", "opcion_tags_" + id);
					
					var ref = "tags";

					$("UL#selected_tags").append(LI);
					
					setTimeout(function() {
						$("A.delete_option").click(function() {
							var id = $(this).data('elementid');
							var element = $(this.parentNode.parentNode).data('element');


							$("#chk_opcion_" + element + "_"+ id).attr('checked', false);
							(this.parentNode).remove();
						});
					}, 300);
				});
				
			}, 500);
			
			return false;
		});
	
	});
	
	
	
	
	//
	//	AGREGAR VIDEOS
	//
	$("#addVideo").click(function() {
	
		if ($("#video").val().length < 1) {
			alert("Por favor, ingrese la URL del video.");
			return false;
		}
	
		$.ajax({
		
			url: $(this).data('route'),
			dataType: 'json',
			data: { 
				video: $("#video").val(),
				elemento: $(this).data('elemento'),
				tipo: $(this).data('tipo')
			},
			method : 'post'
			
		}).success(function(resp) {

			if (resp.error == 1) {
				alert(resp.error_message);
				return false; 
			}
			
			
			var LI = $(document.createElement("LI"));
			LI.attr('id', 'video_' + resp.id);
			LI.html("<a  href='javascript:void%200;' data-element='video_"+ resp.id +"' data-delete='/adm/"+ resp.elemento +"/"+ resp.tipo +"/"+ resp.id +"/borrar' class='btn btn-mini deleteModal sec accion'><i class='icon-trash'></i> Eliminar video</a>");

			var img = new Image();
			
			if ( resp.sitio == 'vimeo') {

				$.ajax({
				
					url: "http://vimeo.com/api/v2/video/"+ resp.code +".json",
					dataType: 'json',
					method : 'get'
					
				}).success(function(respb) {
				
					img.src = respb[0].thumbnail_medium;

				});

				
			} else {
			
				img.src = "http://img.youtube.com/vi/"+ resp.code +"/0.jpg";
			}

			img.width = 220;
			img.onload = function() {
				LI.append($(img));
				if ( $("LI#empty_videos") ) $("LI#empty_videos").remove();
				
				$("#videosGal").append(LI);								
				$(LI).on('mouseover', function() { $(this).find("A").show();}).on('mouseout', function() { $(this).find("A").hide(); });
				
				$("#video").val('');
				
				reorderUpdate( $("#videosGal")[0] );								
			};
		});
	});
	
	

	// Obtiene el PATH de la applicacion (hasta /adm)
	
	var appPath = "";
	var appPathArr = window.location.pathname;
	appPathArr = appPathArr.split("/");

	for (var i=0; i<appPathArr.length; i++) {
		
		if (appPathArr[i] != "adm") {
			break;
		}
		
		appPath += appPathArr[i];
		
	}
	appPathArr = null;
	
	
	
	$("SELECT#sede_optlist").change(function() {
	
		var id = $(this).val();
		var url = $(this).data('svc') + id;

		$.ajax({
		
			url: url,
			method : "post",
			dataType: "json"
			
		}).success(function(resp) {

			$("#salas").html("");

			var html = "";
			
			for (i=0;i<resp.data.length;i++) {
				html += "<option value='"+ resp.data[i].sala +"'>"+ resp.data[i].sala +"</option>";
			}
			
			setTimeout(function() {
				$("#salas").html(html);
			}, 500);
			
			return false;
		});

	
	});



	$('input[type=radio],input[type=file]').uniform();

	$('a.modalTriggerImg').click(function( evt ){
		
		evt.preventDefault();

		$('#imgModal').attr('src', $(this).data('img'));

		$('#myModal').modal('show');
		
	});

	$('a.modalTriggerVid').click(function( evt ){
		
		evt.preventDefault();

		$('.modal-body div').append("<iframe width=\"530\" height=\"310\" src=\"http://www.youtube.com/embed/" + $(this).data('id') + "?autoplay=1&showinfo=0&rel=0&fs=1\" frameborder=\"0\" allowfullscreen></iframe>");

		$('#myModal').modal('show');
		
	});

	$('#emptyVideo').click(function(){
		$('.modal-body div').empty();		
	});


	
	$(document).on( 'click', 'a.deleteModal', function( evt ) {
		
		evt.preventDefault();
		var item = $( "#" + $(this).data('element') );
		var url = $(this).data('delete');
		

		$('.deleteYes').click(function() {

			$.ajax({
			
				url: url,
				method : "post",
				dataType: "json"
				
			}).success(function(resp) {
				
				if ( resp.error == 1) {
					alert(resp.error_message);
					return false;
				}
				
				item.remove();
			});
			
		});

		$('#deleteModal').modal('show');
	});	
	
	
	
	/* EDITAR MODAL para GALERIAS */
	
	$(document).on( 'click', 'a.editarModal', function( evt ) {
		
		evt.preventDefault();
		var item = $( "#" + $(this).data('element') );
		var url_post = $(this).data('editar');
		var url_get = $(this).data('get');
		var id = null;
		
		/* Traemos la data original del elemento */
		
		$.ajax({
		
			url: url_get,
			method : "post",
			dataType: "json"
			
		}).success(function(resp) {
			
			if ( resp.error == 1) {
				alert(resp.error_message);
				return false;
			}
			
			
			/* Bajamos la informacion al form modal */
			
			id = resp.data.id;

			$("#data_url").val(resp.data.url);
		});
		

		$('.editarYes').click(function() {

			$.ajax({
			
				url: url_post,
				method : "post",
				data: { 
					id: id,
					url: $("#data_url").val(),
				},
				dataType: "json"
				
			}).success(function(resp) {
				
				if ( resp.error == 1) {
					$("#error_url").html(resp.error_message);
					return false;
				}
				
				
				/* Limpiar y cerrar */
				
				setTimeout(function() {
					$("#error_url").html("");
					url: $("#data_url").val(""),
					$('#editarModal').modal('hide');
				}, 100);
				
			});
			
		});

		$('#editarModal').modal('show');
	});	
	
	
	
	$('a.openModal').click(function( evt ){
	
		evt.preventDefault();
		
		var id = this.id;
		
		id = id.substr(0, id.length - 4);

		$('#' + id).modal('show');
	});	
	
	
	
	
	
	
	
	
	// FECHAS
	$('INPUT.datepicker').each(function( evt ) {

		$(this).datetimepicker({
		  format:'Y-m-d H:i:00',
		  lang:'en'
		}); 
	
	});
	
	
	var index = 1;
	
	$("A#add_fecha").click(function(evt) {
		
		evt.preventDefault();
		
		var die = false;
		
		$("DIV#fechas").find("INPUT").each(function() {
		
			if (this.value == "") {
				die = true;
				return false;
			}
		});
		
		if (die) return false;
		
		var INPUT = $( document.createElement('INPUT') );
		INPUT.attr('name', 'fechas[]');
		INPUT.attr('id', 'fechaX_' + index);
		INPUT.attr('type', 'text');
		INPUT.attr('style', 'margin-top:5px;width:120px;float:left;');
		
		var A = $( document.createElement('A') );
		A.attr('class', 'btn');
		A.attr('style', 'float:left; margin-left:10px; margin-top:5px;');
		A.attr('href', 'javascript:void%200;');
		A.attr('title', 'Eliminar esta fecha');
		A.html("<i class='icon icon-trash'></i> eliminar");
		
		var SPAN = $( document.createElement('SPAN') );
		SPAN.attr('style', 'display:block;clear:both;');
			
		$("DIV#fechas").append(INPUT);
		$("DIV#fechas").append(A);		
		$("DIV#fechas").append(SPAN);

		var val = index;

		$('#fechaX_'+ val).datetimepicker({
		  format:'Y-m-d H:i:00',
		  lang:'en'
		});
		

		
		A.click(function() {
			$('INPUT#fechaX_' + val).remove(); $(this).remove();
		});
		
		index++;
	});
	
	
	
	// TINY MCE
	tinymce.init({
		selector: ".tinymce",
		width : '81%',
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});	
	

	
	// SORTABLE UPDATES
	$("UL.sortable-list").sortable({ opacity: 0.6, cursor: 'move', update: reorderUpdate });
	
	
	/*
	
		AGREGAR / QUITAR Elementos relacionados
		TAGS, SITIOS, CONTENIDOS, etc.
		
	*/
	$('A.dealSelectAll').click(function() {
	
		var element = $(this).data('element');
	
		$(this.parentNode).find("INPUT[id^='chk_']").each(function() {
			$(this).attr('checked', 'checked');
			
			var id = this.id.match(/\d+/);

			if ( $("LI#" + element + "_" + id).length == 0 ) {
				var LI = $(document.createElement("LI"));
				LI.html("<span id='" + element + "_"+ id +"'>"+ $("SPAN#nombre_" + element + "_"+ id).text() +"</span><a class='delete_option' data-element='"+ element +"' data-elementid='"+ id +"' href='javascript:void%200;' title='Eliminar este " + element + "'>X</a></li>");
				LI.attr("id", "opcion_" + element + "_" + id);
				
				var ref = element[0].toUpperCase() + element.substr(1, element.length) + "s";

				$("UL#selected_" + element).append(LI);
				
				setTimeout(function() {
					$("A.delete_option").click(function() {
						var id = $(this).data('elementid');
						var element = $(this.parentNode.parentNode).data('element');


						$("#chk_opcion_" + element + "_"+ id).attr('checked', false);
						(this.parentNode).remove();
					});
				}, 300);
			}
		});
	});

	$('A.dealRemoveAll').click(function() {
	
		var element = $(this).data('element');
	
		$(this.parentNode).find("INPUT[id^='chk_']").each(function() {	
		
			$(this).attr('checked', false);
			var id = this.id.match(/\d+/);
			$("LI#opcion_" + element + "_" + id).remove();
		});
	});	
	
	$("A.delete_option").click(function() {
		var id = $(this).data('elementid');
		var element = $(this.parentNode.parentNode).data('element');


		$("#chk_opcion_" + element + "_"+ id).attr('checked', false);
		(this.parentNode).remove();
	});	
	
	$("INPUT[id^='chk_']").click(function() {
		var id = this.id.match(/\d+/);

		var element = $(this.parentNode.parentNode).data('element');
		
		if ( $(this).attr('checked') ) {

			var LI = $(document.createElement("LI"));
			LI.html("<span id='" + element + "_"+ id +"'>"+ $("SPAN#nombre_" + element + "_"+ id).text() +"</span><a class='delete_option' data-element='"+ element +"' data-elementid='"+ id +"' href='javascript:void%200;' title='Eliminar este " + element + "'>X</a></li>");
			LI.attr("id", "opcion_" + element + "_" + id);
			
			var ref = element[0].toUpperCase() + element.substr(1, element.length) + "s";

			$("UL#selected_" + element).append(LI);

			setTimeout(function() {
				$("A.delete_option").click(function() {
					var id = $(this).data('elementid');
					var element = $(this.parentNode.parentNode).data('element');

					$("#chk_opcion_" + element + "_"+ id).attr('checked', false);
					(this.parentNode).remove();
				});
			}, 300);
			
		} else {
		
			$("LI#" + "opcion_" + element + "_" + id).remove();
		}
	});	
	


	// MOSTRAR UPLOAD AREA
	$("[id^='dragandrophandler_']").click(function() { $(this).fadeOut(); });
	
	$("[id^='uploadAreaSwitch_']").click(function() {
	
		var element = this.id.substr(17, this.id.length);
	
		if ( $("#dragandrophandler_"+ element).css("display") == "none") {
			$("#dragandrophandler_"+ element).fadeIn();
			
		} else {
		
			$("#dragandrophandler_"+ element).fadeOut();
		}
	
	});
	
	// GALERIAS MOSTRAR BOTONES
	$("UL#imageGal LI, UL#videoGal LI, UL#sonidosGal LI, UL#videosGal LI, UL#clippingsGal LI, UL#fotosGal LI").on('mouseover', function() { $(this).find("A.accion").show();}).on('mouseout', function() { $(this).find("A.accion").hide(); });
	

	// DATATABLES JS Plugin
	var arFields = [];
	$('#datatable').each(function() { 
	
		var fields = ($(this).find('TH').length / 2 ) - 1; 
		
		for (var i=0; i<fields; i++) {
				arFields.push(i);
		}	
	
	}).dataTable({
	
		"sDom": "<'row'<'span6'T><'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"bAutoWidth": true,
		"iDisplayLength": 25,
		"oTableTools": {
			"sSwfPath": "/swf/copy_cvs_xls_pdf.swf",
			"aButtons": [
				{
					"sExtends": "csv",
					"sButtonText": "CSV",
					"mColumns": arFields
				},
				{
					"sExtends": "xls",
					"sButtonText": "XLS",
					"mColumns": arFields
				},
				{
					"sExtends": "pdf",
					"sButtonText": "PDF",
					"mColumns": arFields
				}
			]
		}
	});

	$.extend( $.fn.dataTableExt.oStdClasses, {
		"sWrapper": "dataTables_wrapper form-inline"
	});



	
	/**************************************************************
	
						BLOQUE INTERFAZ
		
	***************************************************************/	
	
	// === Sidebar navigation === //
	
	$('.submenu > a').click(function(e)
	{
		e.preventDefault();
		var submenu = $(this).siblings('ul');
		var li = $(this).parents('li');
		var submenus = $('#sidebar li.submenu ul');
		var submenus_parents = $('#sidebar li.submenu');
		if(li.hasClass('open'))
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenu.slideUp();
			} else {
				submenu.fadeOut(250);
			}
			li.removeClass('open');
		} else 
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenus.slideUp();			
				submenu.slideDown();
			} else {
				submenus.fadeOut(250);			
				submenu.fadeIn(250);
			}
			submenus_parents.removeClass('open');		
			li.addClass('open');	
		}
	});
	
	var ul = $('#sidebar > ul');
	
	$('#sidebar > a').click(function(e)
	{
		e.preventDefault();
		var sidebar = $('#sidebar');
		if(sidebar.hasClass('open'))
		{
			sidebar.removeClass('open');
			ul.slideUp(250);
		} else 
		{
			sidebar.addClass('open');
			ul.slideDown(250);
		}
	});

	// === Fixes the position of buttons group in content header and top user navigation === //
	function fix_position()
	{
		var uwidth = $('#user-nav > ul').width();
		$('#user-nav > ul').css({'margin-left':'-' + uwidth / 2 + 'px'});
	}
	
	// === Resize window related === //
	$(window).resize(function()
	{
		if($(window).width() > 479)
		{
			ul.css({'display':'block'});	
			$('#content-header .btn-group').css({width:'auto'});		
		}
		if($(window).width() < 479)
		{
			ul.css({'display':'none'});
			fix_position();
		}
		if($(window).width() > 768)
		{
			$('#user-nav > ul').css({width:'auto',margin:'0'});
		}
	});
	
	if($(window).width() < 468)
	{
		ul.css({'display':'none'});
		fix_position();
	}
	if($(window).width() > 479)
	{
		ul.css({'display':'block'});
	}
	
	// === Tooltips === //
	$('.tip').tooltip();	
	$('.tip-left').tooltip({ placement: 'left' });	
	$('.tip-right').tooltip({ placement: 'right' });	
	$('.tip-top').tooltip({ placement: 'top' });	
	$('.tip-bottom').tooltip({ placement: 'bottom' });	
	
	// === Search input typeahead === //
	$('#search input[type=text]').typeahead({
		source: ['Dashboard','Form elements','Common Elements','Validation','Wizard','Buttons','Icons','Interface elements','Support','Calendar','Gallery','Reports','Charts','Graphs','Widgets'],
		items: 4
	});
		
});



/**************************************************************

				BLOQUE FUNCIONES / HELPERS
	
***************************************************************/

function reorderUpdate(Obj) {

	if (Obj.tagName != "UL") {
		Obj = this;
	}
	
	var orden = "";
	var route = $(Obj).data('update');
	
	$(Obj).find("LI").each(function() {
		var id = this.id.match(/\d+/);
		orden += id +",";
	});
		
	var order = 'order=' + orden;
	

	$.post(route, order, function(resp){
		// Do something...

	}); 
	
}


function agregarAnterior() {
 
	// Obtengo un numero "siguiente" al ultimo ID
	var ultimo = null;
	var items = document.getElementById("items_anterior").getElementsByTagName("LI");
	var id = "1";
	
	
	if (items.length > 0) {
		var lastId = items[items.length - 1].id;
		id = lastId.substr(16, lastId.length);	
		id++;

		
		if ( $(items[items.length - 1]).find("INPUT")[0].value == '' || $(items[items.length - 1]).find("INPUT")[1].value == '' ) {
			return false;
		}
	}

	var html = "";
	
	html +=	"	<span>URL</span>";
	html +=	"	<input class='large' type='text' name='anterior[]' value='' /> ";
	html +=	"	<span>A&ntilde;o</span> ";
	html +=	"	<input type='text' name='anterior_ano[]' value='' size='4' maxlength='4' />";
	html +=	"	<a onclick='javascript:$(\"#anterior_opcion_"+ id +"\").remove();' href='javascript:void%200;' title='Eliminar esta opcion'>X</a>";

	var LI = $( document.createElement("LI") );
	LI.attr("id", "anterior_opcion_" + id);
	LI.html(html);
	
	$("#items_anterior").append( LI );
	
}


function search(obj) { 

	var elemento = $(obj).attr('id');
	
	var filtro = $(obj).val().toLowerCase();
	
	$("UL#mm_"+ elemento +"_list LI").each(function() {
	
		$( this ).hide();

		if ( $( this).find("INPUT:first")[0].checked == true ) {

			$( this ).show();

		} else if (filtro != "") {

			var text = $(this).find("SPAN:first").text().toLowerCase();

			if (text.substr(0, filtro.length) == filtro ) {
			
				$( this ).show();
			}
		}
	});
	
}
