@extends('admin.main')
@section('content')
<div class="row-fluid">

	<div class="span12">
	
		<div class="widget-box">
		
			<div class="widget-title">
			
				<span class="icon"><i class="icon-align-justify"></i></span>
				
				<h5>Editar Categoria</h5>
				
			</div>

			<div class="widget-content nopadding">

				{{ Form::open( array('url' => route('fichacategoria_editar_post', $registro['id']), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 
					
					<div class="control-group {{ $errors->has( 'titulo' ) ? 'error' : '' }}">
					
						{{ Form::label('categoria', 'Categoria', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'categoria', Input::old( 'categoria' ) != "" ? Input::old( 'categoria' ):$registro['categoria'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'categoria' ) }}</span>
							
						</div>
						
					</div>
					

				
				
					<!-- TAGS -->
					<div class="control-group {{ $errors->has('tags') ? 'error' : '' }}">
					
						{{ Form::label( 'tags', 'Agregar Tags', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							<a href="javascript:void%200;" class="btn btn-mini openModal" id="tagsModalOpen"><i class="icon-share"></i> Seleccionar...</a><br/>
							
							<br/>
							
							<ul id="selected_tags" class="optionListEditable" data-element="tags">
							
							@foreach ($tags as $tag)
							
								@if ($tag['selected'] === true)
								<li id="opcion_tags_{{$tag['id']}}"><span>{{$tag['tag']}}</span> 

									<a data-elementid="{{$tag['id']}}" class="delete_option" href="javascript:void%200;" title="Eliminar este Tag">X</a>
								
								</li>
								@endif
								
							@endforeach	
								
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

								<br/>
								
								<ul id="mm_tags_list" class="listing" data-element="tags">
								@foreach ($tags as $tag)

									<li>{{Form::checkbox('tags[]', $tag['id'], $tag['selected'], array('id' => 'chk_opcion_tags_' . $tag['id'], 'checked' => null))}} <span id="nombre_tags_{{ $tag['id'] }}">{{ $tag['tag'] }}</span></li>

								@endforeach
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
					
					<a href="{{ route('fichacategoria_listar') }}" class="btn"><i class="icon-remove-sign"></i> Cancelar</a>
					
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
		¿Esta seguro que desea borrar este archivo?
	</div>
	
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn">No</a>
		<a href="#" data-dismiss="modal" class="btn btn-danger deleteYes">¡Si, estoy seguro!</a>
	</div>
</div>
<!-- BORRAR Modal -->

@stop