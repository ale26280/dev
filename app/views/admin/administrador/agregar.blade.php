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
			
				{{ Form::open( array('url' => route('administrador_agregar_post'), 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal') ) }} 
					
					<div class="control-group {{ $errors->has( 'perfil' ) ? 'error' : '' }}">
					
						{{ Form::label('perfil', 'Perfil', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::select('perfil_id', $perfiles, Input::old( 'perfil_id' ) != "" ? Input::old( 'perfil_id' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'perfil' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'nombre' ) ? 'error' : '' }}">
					
						{{ Form::label('nombre', 'Nombre', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'nombre', Input::old( 'nombre' ) != "" ? Input::old( 'nombre' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'nombre' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'apellido' ) ? 'error' : '' }}">
					
						{{ Form::label('apellido', 'Apellido', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'apellido', Input::old( 'apellido' ) != "" ? Input::old( 'apellido' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'apellido' ) }}</span>
							
						</div>
						
					</div>
					
					<div class="control-group {{ $errors->has( 'email' ) ? 'error' : '' }}">
					
						{{ Form::label('email', 'Email', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'email', Input::old( 'email' ) != "" ? Input::old( 'email' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'email' ) }}</span>
							
						</div>
						
					</div>
					
					
					<div class="control-group {{ $errors->has( 'username' ) ? 'error' : '' }}">
					
						{{ Form::label('username', 'Username', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::text( 'username', Input::old( 'username' ) != "" ? Input::old( 'username' ):"" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'username' ) }}</span>
							
						</div>
						
					</div>
					
					
					<div class="control-group {{ $errors->has( 'password' ) ? 'error' : '' }}">
					
						{{ Form::label('password', 'Password', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::password( 'password', "" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'password' ) }}</span>
							
						</div>
						
					</div>
					
					
					<div class="control-group {{ $errors->has( 'password_confirm' ) ? 'error' : '' }}">
					
						{{ Form::label('password_confirm', 'Confirmacion Password', array('class' => 'control-label')) }}
						
						<div class="controls">
						
							{{ Form::password( 'password_confirm', "" ) }} <br/>
							
							<span class="errNew">{{ $errors->first( 'password_confirm' ) }}</span>
							
						</div>
						
					</div>
					
				</div>
			
				

				<div class="form-actions">
				
					<button type="submit" class="btn btn-primary"><i class="icon-hdd icon-white"></i> Guardar</button>
					
					<a href="{{ route('administrador_listar') }}" class="btn"><i class="icon-remove-sign"></i> Cancelar</a>
					
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