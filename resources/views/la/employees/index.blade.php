@extends("la.layouts.app")

@section("contentheader_title", "Soci")
@section("contentheader_description", "Lista Soci")
@section("section", "Soci")
@section("sub_section", "Lista")
@section("htmlheader_title", "Lista Soci")

@section("headerElems")
@la_access("Employees", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Aggiungi Socio</button>
@endla_access
@endsection

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Azioni</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Employees", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Aggiungi Socio</h4>
			</div>
			{!! Form::open(['action' => 'LA\EmployeesController@store', 'id' => 'employee-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
				
				@la_input($module,'name')
				@la_input($module,'mobile')
				@la_input($module,'mobile2')
				@la_input($module,'email')
				@la_input($module, 'password')
				@la_input($module,'city')
				@la_input($module,'address')			
					<div class="form-group">
					<input class="form-control" name="dept" type="hidden" value="2">
					</div>
					<div class="form-group">
					<label for="code">Codice Socio :</label>
					<input class="form-control" placeholder="Inserisci Codice Socio" min="1" name="codice" type="number" value="" required>
					</div>
					<div class="form-group">
						<label for="role">Ruolo* :</label>
						<select class="form-control" required="1" data-placeholder="Seleziona Ruolo" rel="select2" name="role">
							<?php $roles = App\Role::where('display_name','!=','Terzisti')->where('display_name','!=','Admin')->get();; ?>
							@foreach($roles as $role)
								@if($role->id != 1)
									<option value="{{ $role->id }}">{{ $role->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
				{!! Form::submit( 'Invia', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/employee_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Cerca"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#employee-add-form").validate({
		code: {
				number: true
			}
	});
});
</script>
@endpush