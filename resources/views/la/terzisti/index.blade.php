<?php
use App\Models\Prodotti;
?>
@extends("la.layouts.app")

@section("contentheader_title", "Terzisti")
@section("contentheader_description", "Lista Terzisti")
@section("section", "Terzisti")
@section("sub_section", "Lista")
@section("htmlheader_title", "Lista Terzisti")

@section("headerElems")
@la_access("Terzisti", "create")
@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
    <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Aggiungi Terzista</button>
@endif
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

@la_access("Terzisti", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Aggiungi Terzista</h4>
            </div>
            {!! Form::open(['action' => 'LA\TerzistiController@store', 'id' => 'terzisti-add-form']) !!}
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
					<input class="form-control" name="dept" type="hidden" value="3">
					</div>
<!--                    <div class="form-group"> -->
<!-- 						<label for="tara">Tara :</label> -->
<!-- 						<input type="number" class="form-control" name="tara" id="tara" min="1" required> -->
<!-- 					</div> -->
					<div class="form-group">
						<label for="role">Associato a :</label>
						<select class="form-control" data-placeholder="Associato a" rel="select2" name="users_id[]" multiple>
							<?php $users = App\User::where('context_id',"!=",'0')->get(); ?>
							@foreach($users as $user)
								@if($user->id != 1)
									<option value="{{ $user->id }}">{{ $user->name }}</option>
								@endif
							@endforeach
						</select>
					</div>                    
					<div class="form-group">
						<label for="role">Ruolo* :</label>
						<select class="form-control" required="1" data-placeholder="Seleziona Ruolo" rel="select2" name="role">
							<?php $roles = App\Role::where('display_name','=','Terzisti')->get(); ?>
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/terzisti_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Cerca"
        },
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });   
    $("#terzisti-add-form").validate({
        });
});
</script>
@endpush