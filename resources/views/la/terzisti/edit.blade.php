@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/terzisti') }}">Terzista</a> :
@endsection
@section("contentheader_description", $terzisti->$view_col)
@section("section", "Terzista")
@section("section_url", url(config('laraadmin.adminRoute') . '/terzisti'))
@section("sub_section", "Modifica")

@section("htmlheader_title", "Modifica Terzista : ".$terzisti->$view_col)

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
                {!! Form::model($terzisti, ['route' => [config('laraadmin.adminRoute') . '.terzisti.update', $terzisti->id ], 'method'=>'PUT', 'id' => 'terzisti-edit-form']) !!}
                    @la_input($module,'name')
                    @la_input($module,'mobile')
                    @la_input($module,'mobile2')
                    @la_input($module,'email')              
                    @la_input($module,'city')
                    @la_input($module,'address')                  
					<div class="form-group">
						<label for="role">Associato a :</label>
						<select id="soci" class="form-control" data-placeholder="Seleziona Associato" rel="select2" name="users_id[]" multiple>
							<?php
							$associati = App\Models\Associati::where('terzisti_id', $terzisti->id)->get();
							$users = App\User::where('terzisti_id','!=',$terzisti->id)->where('context_id','!=','0')->get(); 
							?>
							@foreach($users as $use)
								@if(!$associati->isEmpty())
								@if($use->id != 1)
									<option @foreach($associati as $associato) @if($associato->users_id == $use->id) selected @endif @endforeach value="{{ $use->id }}">{{ $use->name }}</option>
								@endif
								@else
								@if($use->id != 1)
									<option value="{{ $use->id }}">{{ $use->name }}</option>
								@endif
								@endif
 							@endforeach						
						</select>
					</div>                    
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Aggiorna', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/terzisti') }}" class="btn btn-default pull-right">Torna indietro</a>
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
    $("#terzisti-edit-form").validate({});
    $('select#soci').on('select2:unselect',function(e){
		$('select#soci option').each(function(){
			if($(this).val() == e.params.data.id){
				$(this).removeAttr('selected')
				$('select#soci').trigger('change')
			}
			})
        })
});
</script>
@endpush