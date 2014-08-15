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

				{{ Form::open( array('url' => route('configuracion_editar_post', $registro['id']), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 
					
					<div class="control-group {{ $errors->has( 'titulo' ) ? 'error' : '' }}">
					
						{{ Form::label('titulo', 'Titulo', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'titulo', Input::old( 'titulo' ) != "" ? Input::old( 'titulo' ):$registro['titulo'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'titulo' ) }}</span>
							
						</div>
						
					</div>					
					
					<div class="control-group {{ $errors->has( 'descripcion' ) ? 'error' : '' }}">
					
						{{ Form::label('descripcion', 'Descripcion', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea( 'descripcion', Input::old( 'descripcion' ) != "" ? Input::old( 'descripcion' ):$registro['descripcion'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'descripcion' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'video' ) ? 'error' : '' }}">
					
						{{ Form::label('video', 'Video', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'video', Input::old( 'video' ) != "" ? Input::old( 'video' ):$registro['video'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'video' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'metadatos' ) ? 'error' : '' }}">
					
						{{ Form::label('metadatos', 'Metadatos', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea( 'metadatos', Input::old( 'metadatos' ) != "" ? Input::old( 'metadatos' ):$registro['metadatos'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'metadatos' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'analytics' ) ? 'error' : '' }}">
					
						{{ Form::label('analytics', 'Analytics', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::textarea( 'analytics', Input::old( 'analytics' ) != "" ? Input::old( 'analytics' ):$registro['analytics'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'analytics' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'telefono' ) ? 'error' : '' }}">
					
						{{ Form::label('telefono', 'Telefono', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'telefono', Input::old( 'telefono' ) != "" ? Input::old( 'telefono' ):$registro['telefono'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'telefono' ) }}</span>
							
						</div>
						
					</div>	
					
					<div class="control-group {{ $errors->has( 'contacto' ) ? 'error' : '' }}">
					
						{{ Form::label('contacto', 'Contacto', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'contacto', Input::old( 'contacto' ) != "" ? Input::old( 'contacto' ):$registro['contacto'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'contacto' ) }}</span>
							
						</div>
						
					</div>					
					
					<div class="control-group {{ $errors->has( 'contacto_copia' ) ? 'error' : '' }}">
					
						{{ Form::label('contacto_copia', 'Contacto copia', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'contacto_copia', Input::old( 'contacto_copia' ) != "" ? Input::old( 'contacto_copia' ):$registro['contacto_copia'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'contacto_copia' ) }}</span>
							
						</div>
						
					</div>	
					
					<div class="control-group {{ $errors->has( 'latitud' ) ? 'error' : '' }}">
					
						{{ Form::label('latitud', 'Latitud', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'latitud', Input::old( 'latitud' ) != "" ? Input::old( 'latitud' ):$registro['latitud'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'latitud' ) }}</span>
							
						</div>
						
					</div>	
					
					<div class="control-group {{ $errors->has( 'longitud' ) ? 'error' : '' }}">
					
						{{ Form::label('longitud', 'Longitud', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'longitud', Input::old( 'longitud' ) != "" ? Input::old( 'longitud' ):$registro['longitud'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'longitud' ) }}</span>
							
						</div>
						
					</div>						
					
					<div class="control-group {{ $errors->has( 'url_facebook' ) ? 'error' : '' }}">
					
						{{ Form::label('url_facebook', 'Facebook', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'url_facebook', Input::old( 'url_facebook' ) != "" ? Input::old( 'url_facebook' ):$registro['url_facebook'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'url_facebook' ) }}</span>
							
						</div>
						
					</div>	
					
					<div class="control-group {{ $errors->has( 'url_twitter' ) ? 'error' : '' }}">
					
						{{ Form::label('url_twitter', 'Twitter', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'url_twitter', Input::old( 'url_twitter' ) != "" ? Input::old( 'url_twitter' ):$registro['url_twitter'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'url_twitter' ) }}</span>
							
						</div>
						
					</div>	
					
					<div class="control-group {{ $errors->has( 'url_googleplus' ) ? 'error' : '' }}">
					
						{{ Form::label('url_googleplus', 'Google+', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'url_googleplus', Input::old( 'url_googleplus' ) != "" ? Input::old( 'url_googleplus' ):$registro['url_googleplus'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'url_googleplus' ) }}</span>
							
						</div>
						
					</div>	

					<div class="control-group {{ $errors->has( 'url_linkedin' ) ? 'error' : '' }}">
					
						{{ Form::label('url_linkedin', 'Linkedin', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'url_linkedin', Input::old( 'url_linkedin' ) != "" ? Input::old( 'url_linkedin' ):$registro['url_linkedin'] ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'url_linkedin' ) }}</span>
							
						</div>
						
					</div>	

				</div>


				<div class="form-actions">
				
					<button type="submit" class="btn btn-primary"><i class="icon-hdd icon-white"></i> Guardar</button>
					
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