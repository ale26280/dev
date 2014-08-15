@extends('admin.main')
@section('content')
<div class="widget-box">

	<div class="widget-title">

		<h5>Categorias</h5>

		<div class="buttons">
			<!--<a title="Icon Title" class="btn btn-mini" href="{{ route('fichacategoria_agregar') }}"><i class="icon-plus"></i> Agregar nuevo</a>
		
			<a title="Icon Title" class="btn btn-mini" href="javascript:window.open('{{ route('fichacategoria_export') }}');"><i class="icon-download-alt"></i> Exportar</a>-->
		</div>
		
	</div>

	<div class="widget-content" style="margin-top:6px;">

		<table id="datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
		
			<thead>
				<tr>
				
					<th>Categoria</th>				

					<th style="width: 10%;">Acciones</th>
				</tr>
			</thead>			
		
			<tbody>

				@foreach ( $registros as $registro )
				
				<tr id="row_{{$registro['id']}}" class="odd_gradeA">
					
					<td>{{ $registro['categoria'] }}</td>			

					<td>
						<div class="btn-group">

							<a href="{{ route('fichacategoria_editar', $registro['id']) }}" class="btn btn-mini" title="Editar/Actualizar Slider"><i class="icon-pencil"></i> </a>

							<!--<a href="javascript:void%200;" class="btn btn-mini deleteModal" title="Eliminar Slider" data-element="row_{{$registro['id']}}" data-delete="{{ route('fichacategoria_borrar', $registro['id']) }}"><i class="icon-trash"></i> </a>-->

						</div>
					</td>
				</tr>

				@endforeach
			</tbody>
			
		</table>
		
	</div>

</div>

<!-- BORRAR Modal -->
<div id="deleteModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Borrar registro</h3>
	</div>
	
	<div class="modal-body">
		¿Esta seguro que desea borrar este registro?
	</div>
	
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn">No</a>
		<a href="#" data-dismiss="modal" class="btn btn-danger deleteYes">¡Si, estoy seguro!</a>
	</div>
</div>
<!-- BORRAR Modal -->
@stop

