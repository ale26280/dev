@extends('admin.main')
@section('content')

<div class="row-fluid">

	<div class="span12">
	
		<div class="widget-box">
		
			<div class="widget-title">
			
				<span class="icon"><i class="icon-align-justify"></i></span>
				
				<h5>Agregar a Portfolio</h5>
				
			</div>

			<div class="widget-content nopadding">
			
				{{ Form::open( array('url' => route('portfolio_agregar_post'), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 

					<div class="control-group {{ $errors->has( 'caetgoria_id' ) ? 'error' : '' }}">
					
						{{ Form::label('categoria_id', 'Categoria', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::select( 'categoria_id', $categorias, Input::old('categoria_id') ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'caetgoria_id' ) }}</span>
							
						</div>
						
					</div>
				
					<div class="control-group {{ $errors->has( 'titulo' ) ? 'error' : '' }}">
					
						{{ Form::label('titulo', 'Titulo', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'titulo', Input::old( 'titulo' ) != "" ? Input::old( 'titulo' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'titulo' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'subtitulo' ) ? 'error' : '' }}">
					
						{{ Form::label('subtitulo', 'Sub Titulo', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'subtitulo', Input::old( 'subtitulo' ) != "" ? Input::old( 'subtitulo' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'subtitulo' ) }}</span>
							
						</div>
						
					</div>
					
					
					<div class="control-group {{ $errors->has( 'descripcion' ) ? 'error' : '' }}">
					
						{{ Form::label('descripcion', 'Descripcion', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea( 'descripcion', Input::old( 'descripcion' ) != "" ? Input::old( 'descripcion' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'descripcion' ) }}</span>
							
						</div>
						
					</div>
					
					
					<div class="control-group {{ $errors->has( 'orden' ) ? 'error' : '' }}">
					
						{{ Form::label('orden', 'Orden', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'orden', Input::old( 'orden' ) != "" ? Input::old( 'orden' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'orden' ) }}</span>
							
						</div>
						
					</div>
					
					
					
					<!-- TAGS -->
					<div class="control-group {{ $errors->has('tags') ? 'error' : '' }}">
					
						{{ Form::label( 'tags', 'Agregar Tags', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							<a href="javascript:void%200;" class="btn btn-mini openModal" id="tagsModalOpen"><i class="icon-share"></i> Seleccionar...</a><br/>
							
							<br/>
							
							<ul id="selected_tags" class="optionListEditable" data-element="tags">

								
							</ul>

							<span class="errNew">{{ $errors->first('tags') }}</span><br/>
							
						</div>
						
						<!-- RELACIONES MULTIPLES -->
						<div id="tagsModal" class="modal hide fade">
						
							<div class="modal-header">
							
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h3>Agregar Tags</h3>
								
							</div>
							
							<div class="modal-body" style="height:400px; scroll:auto;">
							
								<a class="btn dealSelectAll" href="javascript:void%200;" data-element="tags" title="Select All">Seleccionar todos</a> <a class="btn dealRemoveAll" data-element="tags" href="javascript:void%200;" title="Deselect All">Eliminar todos</a><br/>
								
								<?php $dsp = ''; ?>
								
								<br/>
								
								<ul id="mm_tags_list" class="listing" data-element="tags">
								@foreach ($tags as $key => $value)

									<li style='{{ $dsp }}'>{{ Form::checkbox('tags[]', $key, false, array('id' => 'chk_opcion_tags_' . $key, 'checked' => null))}} <span id="nombre_tags_{{ $key }}">{{ $value }}</span></li>
									
								@endforeach
								</ul>

							</div>
							
							<div class="modal-footer">

								<a href="javascript:void%200;" data-dismiss="modal" class="btn btn-danger" id="selectYes">Guardar</a>
								
							</div>
							
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
								
								<ul id="fotosGal"  class="imageGal sortable-list" data-update="{{ route('portfolio_foto_reordenar') }}">

								@if ( count($fotos) > 0)
									
									@foreach ($fotos as $foto)
										<li id="foto_{{ $foto['id'] }}">
										
										<a href="javascript:void%200;" data-element="foto_{{$foto['id']}}" data-delete="{{ route('galeria_borrar', array('portfolio', 'temporal', $foto['id']) ) }}" class="btn btn-mini deleteModal sec accion"><i class="icon-trash"></i> Eliminar foto</a>
										
										<img src="{{ $foto['fuente'] }}" width="220" height="auto" /></li>
									@endforeach
								
								@else
								
									<li style="width:99%; text-align:center;" id="empty_fotos">A&uacute;n no se han cargado imagenes para esta galeria</li>
									
								@endif

								</ul>
								
								<div id="dragandrophandler_fotos" class="dragandrophandler" data-tipo="fotos" data-route="{{ route('portfolio_foto_upload') }}">Arrastre y suelte imagenes aqu&iacute; para cargar</div>
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
								li.html("<a href='javascript:void%200;' data-element='foto_"+ data.id +"' title='Eliminar' data-delete='/adm/portfolio/temporal/"+ data.id +"/borrar' class='btn btn-mini deleteModal sec accion'><i class='icon-trash'></i> Eliminar foto</a>");

								li.attr("id", "foto_"+ data.id);

								var img = new Image();

								img.src = data.fuente;
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
							fd.append('elemento', 'portfolio');
							fd.append('tipo', 'temporal');
					 
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
					
					
					
					
					<!-- GALERIA DE VIDEOS -->
					<div class="control-group {{ $errors->has('videos') ? 'error' : '' }}">
					
						{{ Form::label('videos', 'Galeria de Videos', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							<a href="javascript:void%200;" class="btn btn-mini openModal" id="videosModalOpen"><i class="icon-share"></i> Cargar...</a><br/>
							
							<br/>
							
							<span class="errNew">{{ $errors->first('videos') }}</span><br/>								
						</div>
						
						
						<!-- VIDEOS MODAL -->
						<div id="videosModal" class="modal hide fade" style="width:800px;">
						
							<div class="modal-header">
							
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								
								<h3>Galer&iacute;a de videos</h3>
							</div>
							
							<div class="modal-body" style="height:400px; scroll:auto;">
							
								<div style='margin:15px' class="control-group {{ $errors->has('url') ? 'error' : '' }}">
									{{ Form::label('video', 'Video URL', array('class' => 'control-label')) }}
									<div class="controls">
										{{Form::text('video')}} {{ Form::button('Agregar', array('id' => 'addVideo', 'data-route' => route('portfolio_video_agregar'), 'data-elemento' => 'portfolio', 'data-tipo' => 'temporal' )) }}
										<span class="errNew">{{ $errors->first('descripcion') }}</span>
									</div>
								</div>
								
								<br/>
								
								<ul id="videosGal"  class="imageGal sortable-list" data-update="{{ route('portfolio_video_reordenar') }}">
								@if ( count( $videos ) > 0 )
								
								@foreach ( $videos as $video )
									<li id="video_{{ $video['id'] }}">

									<a href="javascript:void%200;" data-element="video_{{ $video['id'] }}" data-delete="{{ route('galeria_borrar', array('portfolio', 'temporal', $video['id']) ) }}" class="btn btn-mini deleteModal sec accion"><i class="icon-trash"></i> Eliminar video</a>
									
									<img id="vidthumb_{{ $video['id'] }}" src="" width="220" height="auto" /></li>

									@if ($video['data_a'] == 'youtube')
									
										<script>
									
											$("#vidthumb_{{ $video['id'] }}").attr("src", "http://img.youtube.com/vi/{{ $video['fuente'] }}/0.jpg");
									
										</script>
									
									@else 
									
										<script>
										
											$.ajax({
											
												url: "http://vimeo.com/api/v2/video/{{$video['fuente']}}.json",
												dataType: 'json',
												method : 'get'
												
											}).success(function(respb) {
											
												
												$("#vidthumb_{{ $video['id'] }}").attr("src", respb[0].thumbnail_medium);

											});
											
										</script>

									@endif
									
								@endforeach
								
								@else
								
									<li style="width:99%; text-align:center;" id="empty_videos">A&uacute;n no se han cargado videos</li>

								@endif
								</ul>
		
							</div>
							
							<div class="modal-footer">
								
								<a href="javascript:void%200;" data-dismiss="modal" class="btn btn-danger" id="selectYes">Guardar</a>
								
							</div>

						</div>
						
					</div>
				
					
					
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