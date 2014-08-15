@extends('admin.main')
@section('content')
<div class="row-fluid">

	<div class="span12">
	
		<div class="widget-box">
		
			<div class="widget-title">
			
				<span class="icon"><i class="icon-align-justify"></i></span>
				
				<h5>Editar Administrador</h5>
				
			</div>

			<div class="widget-content nopadding">

				{{ Form::open( array('url' => route('slider_editar_post', $registro['id']), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 
					
					<div class="control-group {{ $errors->has( 'categoria' ) ? 'error' : '' }}">
					
						{{ Form::label('categoria', 'Categoria / Titulo', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text('categoria', Input::old('categoria') != "" ? Input::old('categoria'):$registro['categoria'] ) }}
							
							<span class="errNew">{{ $errors->first( 'categoria' ) }}</span>
							
						</div>
						
					</div>
					

				<!-- GALERIA DE FOTOS -->
				<div class="control-group {{ $errors->has('fotos') ? 'error' : '' }}">
				
					{{ Form::label('fotos', 'Galeria de Fotos', array('class' => 'control-label')) }}
					
					<div class="controls">
					
						<a href="javascript:void%200;" class="btn btn-mini openModal" id="fotosModalOpen"><i class="icon-share"></i> Cargar...</a><br/>
						
						<br/>
						
						<span class="errNew">{{ $errors->first('fotos') }}</span><br/>								
					</div>
					
					
					<!-- FOTOS MODAL -->
					<div id="fotosModal" class="modal hide fade" style="width:800px;">
					
						<div class="modal-header">
						
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							
							<h3>Galer&iacute;a de fotos</h3>
						</div>
						
						<div class="modal-body" style="height:400px; scroll:auto;">
						
							<a id="uploadAreaSwitch_fotos" title="Agregar fotos" class="btn" href="javascript:void%200;"><i class="icon-picture"></i> <span>Agregar fotos...</span></a><br/>
							
							<br/>
							
							<ul id="fotosGal"  class="imageGal sortable-list" data-update="{{ route('slider_foto_reordenar') }}">

							@if ( count($fotos) > 0)
								
								@foreach ($fotos as $foto)
									<li id="foto_{{ $foto['id'] }}">
									
									<a href="javascript:void%200;" data-element="foto_{{$foto['id']}}" data-delete="{{ route('galeria_borrar', array('slider', 'foto', $foto['id']) ) }}" class="btn btn-mini deleteModal accion sec"><i class="icon-trash"></i> Eliminar foto</a>

									<a href="javascript:void%200;" data-element="foto_{{$foto['id']}}" data-get="{{ route('slider_foto_get', $foto['id']) }}" data-editar="{{ route('slider_foto_editar') }}" class="btn btn-mini editarModal accion"><i class="icon-pencil"></i> Editar foto</a>
									
									<img src="/storage/sliders/{{ $foto['fuente'] }}" width="220" height="auto" /></li>
								@endforeach
							
							@else
							
								<li style="width:99%; text-align:center;" id="empty_fotos">A&uacute;n no se han cargado imagenes para esta galeria</li>
								
							@endif

							</ul>
							
							<div id="dragandrophandler_fotos" class="dragandrophandler" data-tipo="fotos" data-route="{{ route('slider_foto_upload', $registro['id']) }}">Arrastre y suelte imagenes aqu&iacute; para cargar</div>
							<br/>
							<br/>
							<div id="status1"></div>
	
						</div>
						
						<div class="modal-footer">

							<a href="javascript:void%200;" data-dismiss="modal" class="btn btn-danger" id="selectYes">Guardar</a>
							
						</div>

					</div>
					
				</div>

					
					
					
				<script type="text/javascript">
				
				$("UL#fotosGal LI, UL#clippingsGal LI, UL#videosGal LI, UL#sonidosGal LI").on('mouseover', function() { $(this).find("A").show();}).on('mouseout', function() { $(this).find("A").hide(); });
				
				var maxFileSize = "20000"; // Maximo por archivo en Kb
				
				function sendFileToServer(formData,status,tipo,route) {

					var uploadURL = route; //Upload URL
					var extraData = {}; //Extra Data.
					var jqXHR=$.ajax({
						xhr: function() {
							var xhrobj = $.ajaxSettings.xhr();
							if (xhrobj.upload) {
									xhrobj.upload.addEventListener('progress', function(event) {
										var percent = 0;
										var position = event.loaded || event.position;
										var total = event.total;
										if (event.lengthComputable) {
											percent = Math.ceil(position / total * 100);
										}
										//Set progress
										status.setProgress(percent);
									}, false);
								}
							return xhrobj;
						},
						url: uploadURL,
						type: "POST",
						contentType:false,
						processData: false,
						cache: false,
						data: formData,
						dataType: "json",
						success: function(data){

							status.setProgress(100);
							
							if ( data.error == 1) {
							
								alert(data.error_message);
								return false;
							
							}
							


							// Agregamos la nueva imagen al DOM
							var li = $(document.createElement("LI"));
					
							status.setProgress(100);
							
							// Agregamos la nueva imagen al DOM
							var li = $(document.createElement("LI"));
							li.html("<a href='javascript:void%200;' data-element='foto_"+ data.id +"' title='Eliminar' data-delete='/adm/slider/foto/"+ data.id +"/borrar' class='btn btn-mini deleteModal sec accion'><i class='icon-trash'></i> Eliminar foto</a> <a href='javascript:void%200;' data-element='foto_"+ data.id +"' title='Editar' data-get='/adm/slider/foto/get/"+ data.id +"' data-editar='/adm/slider/foto/editar' class='btn btn-mini editarModal accion'><i class='icon-pencil'></i> Editar foto</a>");
							li.attr("id", "foto_"+ data.id);

							var img = new Image();
							img.src = '/storage/sliders/' + data.fuente;
							img.width = 220;
							img.onload = function() {
								li.append($(img));
								if ( $("LI#empty_" + tipo) ) $("LI#empty_" + tipo).remove();
								$("#fotosGal").append(li);
								//reorderUpdate($("#" + tipo +"Gal")[0]);
							};
							
							setTimeout(function() {
								$(li).on('mouseover', function() { $(this).find("A").show();}).on('mouseout', function() { $(this).find("A").hide(); });

								status.destroy();
								
							}, 500);
						}
					});

				 
					status.setAbort(jqXHR);
				}
				 
				var rowCount=0;
				
				function createStatusbar(obj) {

					 rowCount++;
					 var row="odd";
					 if(rowCount %2 ==0) row ="even";
					 this.statusbar = $("<div class='statusbar "+row+"'></div>");
					 this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
					 this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
					 this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
					 this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
					 $("#" + obj.id).after(this.statusbar);
					 
					this.destroy = function() {
						this.statusbar.fadeOut("slow", function() { if (this.statusbar) this.statusbar.remove(); });
					};
						 
				 
					this.setFileNameSize = function(name,size)
					{
						var sizeStr="";
						var sizeKB = size/1024;
						if(parseInt(sizeKB) > 1024)
						{
							var sizeMB = sizeKB/1024;
							sizeStr = sizeMB.toFixed(2)+" MB";
						}
						else
						{
							sizeStr = sizeKB.toFixed(2)+" KB";
						}
				 
						this.filename.html(name);
						this.size.html(sizeStr);
					}
					this.setProgress = function(progress)
					{      
						var progressBarWidth =progress*this.progressBar.width()/ 100; 
						this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
						if(parseInt(progress) >= 100)
						{
							this.abort.hide();
						}
					}
					this.setAbort = function(jqxhr)
					{
						var sb = this.statusbar;
						this.abort.click(function()
						{
							jqxhr.abort();
							sb.hide();
						});
					}
				}
				function handleFileUpload(files,obj)
				{

				   for (var i = 0; i < files.length; i++)
				   {
						if ( (files[i].size / 1024) > maxFileSize) {
							alert("El archivo " + files[i].name + " es demasiado grande (" + maxFileSize +" máximo)");
							continue;
						}
						
						var fd = new FormData();
						fd.append('file', files[i]);
						fd.append('elemento', 'slider');
						fd.append('tipo', 'foto');				
				 
						var status = new createStatusbar(obj); //Using this we can set progress.
						
						
						status.setFileNameSize(files[i].name,files[i].size);
						sendFileToServer(fd,status,$(obj).data('tipo'),$(obj).data('route'));

				 		delete window.fd;
				 		delete window.status;

				   }
				}
				
				$(document).ready(function() {
				
					$("[id^='dragandrophandler_']").on('dragenter', function (e) {
						e.stopPropagation();
						e.preventDefault();
						$(this).css('border', '2px solid #0B85A1');
					});
					
				
					$("[id^='dragandrophandler_']").on('dragover', function (e) {
						 e.stopPropagation();
						 e.preventDefault();
					});
					
					$("[id^='dragandrophandler_']").on('drop', function (e) {
					 
						 $(this).css('border', '2px dotted #0B85A1');
						 e.preventDefault();
						 var files = e.originalEvent.dataTransfer.files;

						 //We need to send dropped files to Server
						 handleFileUpload(files,this);
					});
					
					$(document).on('dragenter', function (e) {
						e.stopPropagation();
						e.preventDefault();
					});
					
					$(document).on('dragover', function (e) {
					  e.stopPropagation();
					  e.preventDefault();
					  //obj.css('border', '2px dotted #0B85A1');
					});
					
					$(document).on('drop', function (e) {
						e.stopPropagation();
						e.preventDefault();
					});
					 
				});
				</script>


					
					
				</div>


				<div class="form-actions">
				
					<button type="submit" class="btn btn-primary"><i class="icon-hdd icon-white"></i> Guardar</button>
					
					<a href="{{ route('slider_listar') }}" class="btn"><i class="icon-remove-sign"></i> Cancelar</a>
					
				</div>
				
			{{ Form::close() }}


			</div>
			
		</div>  
		
	</div>
	
</div>


<!-- EDITAR Modal -->
<div id="editarModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Editar elemento</h3>
	</div>
	
	<div class="modal-body">

		{{ Form::open( array('url' => 'javascript:void%200;', 'method' => 'POST', 'class' => 'form-horizontal') ) }} 
			
			<div class="control-group {{ $errors->has( 'categoria' ) ? 'error' : '' }}">
			
				{{ Form::label('url', 'URL', array('class' => 'control-label')) }}
				
				<div class="controls">
				
					{{ Form::text('url', "", array('id' => 'data_url') ) }}
					
					<br/>
					
					<span class="errNew" id="error_url">{{ $errors->first( 'categoria' ) }}</span>
					
				</div>
				
			</div>
	
		{{ Form::close() }}
	
	</div>
	
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn">No</a>
		<a href="#" class="btn btn-primary editarYes"><i class="icon-hdd icon-white"></i> Guardar</a>
	</div>
</div>
<!-- EDITAR Modal -->



<!-- BORRAR Modal -->
<div id="deleteModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Borrar registro</h3>
	</div>
	
	<div class="modal-body">
		&iquest;Esta seguro que desea borrar este archivo?
	</div>
	
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn">No</a>
		<a href="#" data-dismiss="modal" class="btn btn-danger deleteYes">&iexcl;Si, estoy seguro!</a>
	</div>
</div>
<!-- BORRAR Modal -->

@stop