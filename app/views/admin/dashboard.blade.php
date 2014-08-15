@extends('admin.main')
@section('content')

<div class="row-fluid">

	<div class="span12">
		
		<div class="widget-box">

			<div class="widget-title"><span class="icon"><i class="icon-signal"></i></span><h5>Información general</h5></div>
			
			<div class="widget-content">
			
				{{ Form::open( array('url' => 'adm/dashboard', 'method' => 'POST') ) }}
				
				<div class="row-fluid">

					<div class="span6">
					
						<ul class="site-stats">

							<li> 
								<label><i class="icon-user"></i> Total <b>Usuarios</b>: </label>
								<label><i class="icon-file"></i> Total <b>Fichas</b>: </label>
								<label><i class="icon-envelope"></i> Total <b>Newsletter</b>: </label>
							</li>
						</ul>

					</div>
					
				</div>

			</div>

			Para consultas de soporte escribir a: <a href="mailto:soporte@kood.com.ar" title="KOOD Support">soporte@kood.com.ar</a>.
		</div>          
	</div>
</div>
@stop