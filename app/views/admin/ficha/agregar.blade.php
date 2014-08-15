@extends('admin.main')
@section('content')

<div class="row-fluid">

	<div class="span12">
	
		<div class="widget-box">
		
			<div class="widget-title">
			
				<span class="icon"><i class="icon-align-justify"></i></span>
				
				<h5>Agregar Administrador</h5>
				
			</div>

			<div class="widget-content nopadding">
			
				{{ Form::open( array('url' => route('ficha_agregar_post'), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 
					
					<div class="control-group {{ $errors->has('destacada') ? 'error' : '' }}">
					
						{{ Form::label('destacada', 'Destacado', array('class' => 'control-label') ) }}
						
						<div class="controls">
						
							{{ Form::checkbox('destacada', '1', Input::old( 'destacada' ) == 1 ? true:false) }} <br>
							
							<span class="errNew">{{ $errors->first('destacada') }}</span>
							
						</div>
	
					</div>
					
					<div class="control-group {{ $errors->has('fecha') ? 'error' : '' }}">
					
						{{ Form::label('fecha', 'Fecha', array('class' => 'control-label') ) }}
						
						<div class="controls">
						
							{{ Form::text('fecha', Input::old('fecha'), array('id' => 'fecha_input') ) }} <br>
							
							<span class="errNew">{{ $errors->first('fecha') }}</span>
							
						</div>
						
						<script type="text/javascript"> $("#fecha_input").datepicker({ dateFormat: "yy-mm-dd" }); </script>
						
					</div>					
					
					<div class="control-group {{ $errors->has( 'caetgoria_id' ) ? 'error' : '' }}">
					
						{{ Form::label('categoria_id', 'Categoria', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::select( 'categoria_id', $categorias, Input::old('categoria_id'), array('id' => 'comboCategoria', 'data-svc' => route('fichacategoria_tags')) ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'caetgoria_id' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'imagen_principal' ) ? 'error' : '' }}">
					
						{{ Form::label('imagen_principal', 'Imagen principal', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::file( 'imagen_principal' ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'imagen_principal' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'presentacion' ) ? 'error' : '' }}">

					{{ Form::label('presentacion', 'Presentacion', array('class' => 'control-label')) }}
						
						<div class="controls">

							{{ Form::file( 'presentacion' ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'presentacion' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'titulo' ) ? 'error' : '' }}">
					
						{{ Form::label('titulo', 'Titulo', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'titulo', Input::old( 'titulo' ) != "" ? Input::old( 'titulo' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'titulo' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'cliente' ) ? 'error' : '' }}">
					
						{{ Form::label('cliente', 'Cliente', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'cliente', Input::old( 'cliente' ) != "" ? Input::old( 'cliente' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'cliente' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'copete' ) ? 'error' : '' }}">
					
						{{ Form::label('copete', 'Copete', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'copete', Input::old( 'copete' ) != "" ? Input::old( 'copete' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'copete' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'banner' ) ? 'error' : '' }}">
					
						{{ Form::label('banner', 'Banner', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::file( 'banner' ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'banner' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has('ficha') ? 'error' : '' }}">
					
						{{ Form::label('ficha', 'Ficha', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea('ficha', Input::old( 'ficha' ) != "" ? Input::old( 'ficha' ):"", array('class' => 'tinymce')) }} <br>
							
							<span class="errNew">{{ $errors->first( 'ficha' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has('info') ? 'error' : '' }}">
					
						{{ Form::label('info', 'Info', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea('info', Input::old( 'info' ) != "" ? Input::old( 'info' ):"", array('class' => 'tinymce')) }} <br>
							
							<span class="errNew">{{ $errors->first( 'info' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'web' ) ? 'error' : '' }}">
					
						{{ Form::label('web', 'Web', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'web', Input::old( 'web' ) != "" ? Input::old( 'web' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'web' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'blog' ) ? 'error' : '' }}">
					
						{{ Form::label('blog', 'Blog', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'blog', Input::old( 'blog' ) != "" ? Input::old( 'blog' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'blog' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'orden_home' ) ? 'error' : '' }}">
					
						{{ Form::label('orden_home', 'Orden (Home)', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'orden_home', Input::old( 'orden_home' ) != "" ? Input::old( 'orden_home' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'orden_home' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'orden_interior' ) ? 'error' : '' }}">
					
						{{ Form::label('orden_interior', 'Orden (Interior)', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'orden_interior', Input::old( 'orden_interior' ) != "" ? Input::old( 'orden_interior' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'orden_interior' ) }}</span>
							
						</div>
						
					</div>
					
					
					<!-- TAGS -->
					<div class="control-group {{ $errors->has('tags') ? 'error' : '' }}">
					
						{{ Form::label( 'tags', 'Agregar Tags', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							<a href="javascript:void%200;" class="btn btn-mini openModal" id="tagsModalOpen"><i class="icon-share"></i> Seleccionar...</a><br/>
							
							<br/>
							
							<ul id="selected_tags" class="optionListEditable" data-element="tags">

								@if ( Input::old('tags') )

									@foreach ($tags as $id => $tag)
									
										@foreach ( Input::old('tags') as $oldTag)
										
											@if ($id == $oldTag)
												<li id="opcion_tags_{{$id}}"><span>{{$tag}}</span> 

													<a data-elementid="{{$id}}" class="delete_option" href="javascript:void%200;" title="Eliminar este Tag">X</a>
												
												</li>
												
												<?php continue; ?>
											@endif
											
										@endforeach
										
									@endforeach	
									
								@endif
								
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
									{{Form::text('video')}} {{ Form::button('Agregar', array('id' => 'addVideo', 'data-route' => route('ficha_video_agregar'), 'data-elemento' => 'ficha', 'data-tipo' => 'temporal' )) }}
									<span class="errNew">{{ $errors->first('descripcion') }}</span>
								</div>
							</div>
							
							<br/>
							
							<ul id="videosGal"  class="imageGal sortable-list" data-update="{{ route('ficha_video_reordenar') }}">
							@if ( count( $videos ) > 0 )
							
							@foreach ( $videos as $video )
								<li id="video_{{ $video['id'] }}">

								<a href="javascript:void%200;" data-element="video_{{ $video['id'] }}" data-delete="{{ route('galeria_borrar', array('ficha', 'temporal', $video['id']) ) }}" class="btn btn-mini deleteModal sec accion"><i class="icon-trash"></i> Eliminar video</a>
								
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
								
								<ul id="fotosGal"  class="imageGal sortable-list" data-update="{{ route('ficha_foto_reordenar', 'temporal') }}">

								@if ( count($fotos) > 0)
									
									@foreach ($fotos as $foto)
										<li id="foto_{{ $foto['id'] }}">
										
										<a href="javascript:void%200;" data-element="foto_{{$foto['id']}}" data-delete="{{ route('galeria_borrar', array('ficha', 'temporal', $foto['id']) ) }}" class="btn btn-mini deleteModal sec accion"><i class="icon-trash"></i> Eliminar foto</a>
										
										<img src="/storage/temporales/{{ $foto['fuente'] }}" width="220" height="auto" /></li>
									@endforeach
								
								@else
								
									<li style="width:99%; text-align:center;" id="empty_fotos">A&uacute;n no se han cargado imagenes para esta galeria</li>
									
								@endif

								</ul>
								
								<div id="dragandrophandler_fotos" class="dragandrophandler" data-tipo="fotos" data-route="{{ route('ficha_foto_upload') }}">Arrastre y suelte imagenes aqu&iacute; para cargar</div>
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
								li.html("<a href='javascript:void%200;' data-element='foto_"+ data.id +"' title='Eliminar' data-delete='/adm/ficha/temporal/"+ data.id +"/borrar' class='btn btn-mini deleteModal sec accion'><i class='icon-trash'></i> Eliminar foto</a>");

								li.attr("id", "foto_"+ data.id);

								var img = new Image();
								img.src = '/storage/temporales/' + data.fuente;
								img.width = 220;
								img.onload = function() {
									li.append($(img));
									if ( $("LI#empty_" + tipo) ) $("LI#empty_" + tipo).remove();
									$("#fotosGal").append(li);
									reorderUpdate($("#fotosGal")[0]);
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
							fd.append('elemento', 'ficha');
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
					
					
				</div>
			
				

				<div class="form-actions">
				
					<button type="submit" class="btn btn-primary"><i class="icon-hdd icon-white"></i> Guardar</button>
					
					<a href="{{ route('ficha_listar') }}" class="btn"><i class="icon-remove-sign"></i> Cancelar</a>
					
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