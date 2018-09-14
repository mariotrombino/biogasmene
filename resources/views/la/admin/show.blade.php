@extends('la.layouts.app')

@section('htmlheader_title')
	Vista Utente
@endsection

@section('main-content')
<div id="page-content" class="profile2">
	<div class="bg-success clearfix">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<img class="profile-image" src="{{ Gravatar::fallback(asset('/img/avatar5.png'))->get(Auth::user()->email, ['size'=>400]) }}" alt="">
				</div>
				<div class="col-md-9">
					<h4 class="name">{{ $employee->$view_col }}</h4>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dats1"><i class="fa fa-envelope-o"></i> {{ $employee->email }}</div>
			<div class="dats1"><i class="fa fa-clock-o"></i> Inserito il {{ date("d M, Y", strtotime($employee->created_at)) }}</div>
		</div>		
		<div class="col-md-1 actions">
			@la_access("Users", "edit")
			@if(Auth::user()->id == $employee->id|| Entrust::hasRole("SUPER_ADMIN"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/users/'.$employee->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif
			@endla_access
			
			@la_access("Users", "delete")
			@if(Auth::user()->id == $employee->id|| Entrust::hasRole("SUPER_ADMIN"))
			 <a class="btn btn-default btn-delete btn-xs" data-toggle="modal" data-target="#AddModal"><i class="fa fa-times"></i></a>
			@endif
			@endla_access
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/users') }}" data-toggle="tooltip" data-placement="right" title="Torna Indietro"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> Informazioni Generali</a></li>
		@if(Entrust::hasRole("SUPER_ADMIN") || Auth::user()->id == $employee->id)
		<li class=""><a role="tab" data-toggle="tab" href="#tab-account-settings" data-target="#tab-account-settings"><i class="fa fa-key"></i> Imposta Password</a></li>
		@endif
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>Informazioni Generali</h4>
					</div>
					<div class="panel-body">
						@la_display($module, 'name')
						@la_display($module, 'email')
					</div>
				</div>
			</div>
		</div>
		
		@if(Auth::user()->id == $employee->id|| Entrust::hasRole("SUPER_ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/admin_change_password/'.$employee->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Imposta Password</h4>
						</div>
						<div class="panel-body">
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							@if(Session::has('success_message'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
							@endif
							<div class="form-group">
								<label for="password" class=" col-md-2">Password</label>
								<div class=" col-md-10">
									<input type="password" name="password" value="" id="password" class="form-control" placeholder="Password" autocomplete="off" required="required" data-rule-minlength="6" data-msg-minlength="Please enter at least 6 characters.">
								</div>
							</div>
							<div class="form-group">
								<label for="password_confirmation" class=" col-md-2">Ripeti password</label>
								<div class=" col-md-10">
									<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" placeholder="Ripeti password" autocomplete="off" required="required" data-rule-equalto="#password" data-msg-equalto="Please enter the same value again.">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Cambia Password</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		@endif			
	</div>
	</div>
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Elimina Admin</h4>
            </div>
            {{ Form::open(['route' => [config('laraadmin.adminRoute') . '.admin.destroy', $employee->id], 'method' => 'delete', 'style'=>'display:inline']) }}
            <div class="modal-body">
                <div class="box-body">
                <p> Sei sicuro di eliminare {{ $employee->name }} ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                {!! Form::submit( 'Elimina', ['class'=>'btn btn-success']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
	@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	@endif
});
</script>
@endpush