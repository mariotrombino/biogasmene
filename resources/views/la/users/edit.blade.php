@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/users') }}">Admin</a> :
@endsection
@section("contentheader_description", $employee->$view_col)
@section("section", "Soci")
@section("section_url", url(config('laraadmin.adminRoute') . '/users'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Modifica Admin : ".$employee->$view_col)

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

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($employee, ['route' => [config('laraadmin.adminRoute') . '.users.update', $employee->id ], 'method'=>'PUT', 'id' => 'employee-edit-form']) !!}
					@la_input($module, 'name')
					@la_input($module, 'email')
					<div class="form-group">
					<input class="form-control" name="dept" type="hidden" value="4">
					</div>
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Aggiorna', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/users') }}" class="btn btn-default pull-right">Torna Indietro</a>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#employee-edit-form").validate({
		
	});
});
</script>
@endpush