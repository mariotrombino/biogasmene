@extends('la.layouts.app')

@section('htmlheader_title')
    Vista Terzista
@endsection

@section('main-content')
<div id="page-content" class="profile2">
    <div class="bg-primary clearfix">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-3">
                    <!--<img class="profile-image" src="{{ asset('la-assets/img/avatar5.png') }}" alt="">-->
                    <div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
                </div>
                <div class="col-md-9">
                    <h4 class="name">{{ $terzisti->$view_col }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dats1"><i class="fa fa-envelope-o"></i> {{ $terzisti->email }}</div>
            <div class="dats1">@if(!empty($terzisti->city)|| !empty($terzisti->address))<i class="fa fa-map-marker"></i> {{ $terzisti->city }}, {{ $terzisti->address }}@endif</div>
            <div class="dats1"><i class="fa fa-phone"></i> {{ $terzisti->mobile }}</div>
            <div class="dats1"><i class="fa fa-clock-o"></i> Inserito il {{ date("d M, Y", strtotime($terzisti->created_at)) }}</div>
        </div>
        <div class="col-md-1 actions">
            @la_access("Terzisti", "edit")
                <a href="{{ url(config('laraadmin.adminRoute') . '/terzisti/'.$terzisti->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
            @endla_access
            
            @la_access("Terzisti", "delete")
                    <a class="btn btn-default btn-delete btn-xs" data-toggle="modal" data-target="#AddModal"><i class="fa fa-times"></i></a>
            @endla_access
        </div>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/terzisti') }}" data-toggle="tooltip" data-placement="right" title="Torna Indietro"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> Informazioni Generali</a></li>
				<li class=""><a role="tab" data-toggle="tab" href="#prodotti" data-target="#prodotti"><i class="fa fa-table"></i> Prodotti Associati</a></li>
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<li class=""><a role="tab" data-toggle="tab" href="#tab-quota-carico" data-target="#tab-quota-carico"><i class="fa fa-table"></i> Imposta Prodotti in Ingresso</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#tab-quota-scarico" data-target="#tab-quota-scarico"><i class="fa fa-table"></i> Imposta Prodotti in Uscita</a></li>
		@endif        
        @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN") || $terzisti->id == Auth::user()->terzisti_id)
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
						@la_display($module, 'mobile')
						@la_display($module, 'mobile2')
						@la_display($module, 'email')
						@la_display($module, 'dept')
						@la_display($module, 'city')
						@la_display($module, 'address')
						@if(!empty($terzisti->associati))
						<div class="form-group">
						<label for="users_id" class="col-md-4 col-sm-6 col-xs-6">Associato a :</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						@foreach($terzisti->associati as $associati)
						@php $username = App\User::where('id','=',$associati->users_id)->first() @endphp
						<a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}/{{ $username->context_id }}" class="label label-primary">{{ $username->name }}</a>
						@endforeach
						</div>
						</div>
						@endif						
                    </div>
                </div>
            </div>
        </div>
        		<div role="tabpanel" class="tab-pane fade" id="prodotti">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>Prodotti Associati</h4>
					</div>
					<div class="panel-body">
	<table class="responsive table" style="width: 100%">
	<thead>
	<tr>
	<td>Codice Prodotto</td>
	<td>Nome Prodotto</td>
	<td>Tipo</td>
	<td>Socio</td>
	<td>Azioni</td>
	</tr>
	</thead>
	<tbody>
	
	</tbody>
	</table>
					</div>
				</div>
			</div>
		</div>
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN") || $terzisti->id == Auth::user()->terzisti_id )
		<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/cambia_password/'.$terzisti->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
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
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-quota-carico">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/cambia_carico/'.$terzisti->id) }}" id="carico-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Imposta Prodotti in Ingresso</h4>
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
							@if(Session::has('success_message_carico'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message_carico') }}</p>
							@endif
						@if(!empty($terzisti->associati))
						<div class="form-group">
						<label for="users_id" class="col-md-4 col-sm-6 col-xs-6">Socio :</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						<select name="soci">
						<option></option>
						@foreach($terzisti->associati as $associati)
						@php $username = App\User::where('id','=',$associati->users_id)->first() @endphp
						<option value="{{ $username->id }}">{{ $username->name }}</option>
						@endforeach
						</select>
						</div>
						</div>
						@endif
						<div class="form-group">
						<label for="prodotti_associati" class="col-md-4 col-sm-6 col-xs-6">Prodotti Associati</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						<select name="prodotti_associati">
						</select>
						</div>
						</div>
								</div>
							</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Salva</button>
						</div>
				</form>
			</div>
		</div>
		@endif
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-quota-scarico">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/cambia_scarico/'.$terzisti->id) }}" id="scarico-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Imposta Prodotti in Uscita</h4>
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
							@if(Session::has('success_message_scarico'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message_scarico') }}</p>
							@endif
						@if(!empty($terzisti->associati))
						<div class="form-group">
						<label for="users_id" class="col-md-4 col-sm-6 col-xs-6">Socio :</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						<select name="soci_uscita">
						<option></option>
						@foreach($terzisti->associati as $associati)
						@php $username = App\User::where('id','=',$associati->users_id)->first() @endphp
						<option value="{{ $username->id }}">{{ $username->name }}</option>
						@endforeach
						</select>
						</div>
						</div>
						@endif
						<div class="form-group">
						<label for="prodotti_associati" class="col-md-4 col-sm-6 col-xs-6">Prodotti Associati</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						<select name="prodotti_associati_uscita">
						</select>
						</div>
						</div>
						</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Salva</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		@endif
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-tara">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/cambia_tara/'.$terzisti->id) }}" id="tara-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Aggiorna Tara</h4>
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
							@if(Session::has('success_message_tara'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message_tara') }}</p>
							@endif
							<div class="form-group">
								<label for="password" class=" col-md-2">Tara</label>
								<div class=" col-md-10">
									<input type="number" name="tara" value="" id="tara" min="1" class="form-control" placeholder="Tara..." autocomplete="off" required="required" data-rule-minlength="1" data-msg-minlength="Per favore inserisci almeno un numero.">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Cambia Tara</button>
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
                <h4 class="modal-title" id="myModalLabel">Elimina Terzista</h4>
            </div>
            {{ Form::open(['route' => [config('laraadmin.adminRoute') . '.terzisti.destroy', $terzisti->id], 'method' => 'delete', 'style'=>'display:inline']) }}
            <div class="modal-body">
                <div class="box-body">
                <p> Sei sicuro di eliminare {{ $terzisti->name }} ?</p>
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
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    $('select[name="prodotti_associati"]').select2({
        language: "it",
        placeholder: "Prodotto",
        allowClear: true,
        sorter: function(data) {
            /* Sort data using lowercase comparison */
            return data.sort(function (a, b) {
                a = a.text.toLowerCase();
                b = b.text.toLowerCase();
                if (a > b) {
                    return 1;
                } else if (a < b) {
                    return -1;
                }
                return 0;
            });
        }
    });
    $('select[name="prodotti_associati_uscita"]').select2({
        language: "it",
        placeholder: "Prodotto",
        allowClear: true,
        sorter: function(data) {
            /* Sort data using lowercase comparison */
            return data.sort(function (a, b) {
                a = a.text.toLowerCase();
                b = b.text.toLowerCase();
                if (a > b) {
                    return 1;
                } else if (a < b) {
                    return -1;
                }
                return 0;
            });
        }
    });    
    $('select[name="soci"]').select2({
        language: "it",
        placeholder: "Seleziona Socio",
        allowClear: true,
        sorter: function(data) {
            /* Sort data using lowercase comparison */
            return data.sort(function (a, b) {
                a = a.text.toLowerCase();
                b = b.text.toLowerCase();
                if (a > b) {
                    return 1;
                } else if (a < b) {
                    return -1;
                }
                return 0;
            });
        }
    });
    $('select[name="soci_uscita"]').select2({
        language: "it",
        placeholder: "Seleziona Socio",
        allowClear: true,
        sorter: function(data) {
            /* Sort data using lowercase comparison */
            return data.sort(function (a, b) {
                a = a.text.toLowerCase();
                b = b.text.toLowerCase();
                if (a > b) {
                    return 1;
                } else if (a < b) {
                    return -1;
                }
                return 0;
            });
        }
    });    
	$('select[name="soci"]').on("select2:select", function(e) {
		var $id = e.params.data.id;
		$('select[name="prodotti_associati"]').empty();
		$('select[name="prodotti_associati"]').append('<option></option>');
			$.getJSON("/admin/ingresso/prodotti/"+$id,function(json){
					for (var i = 0; i < json.length; i++) {
						$('select[name="prodotti_associati"]').append('<option value="'+json[i].id+'">'+json[i].name+'</option>')
					}
				})
		});
	$('select[name="soci_uscita"]').on("select2:select", function(e) {
		var $id = e.params.data.id;
		$('select[name="prodotti_associati_uscita"]').empty();
		$('select[name="prodotti_associati_uscita"]').append('<option></option>');
			$.getJSON("/admin/uscita/prodotti/"+$id,function(json){
					for (var i = 0; i < json.length; i++) {
						$('select[name="prodotti_associati_uscita"]').append('<option value="'+json[i].id+'">'+json[i].name+'</option>')
					}
				})
		});	
	@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	$('#carico-reset-form').validate({
		
	});
	$('#scarico-reset-form').validate({
		
	});
	@endif
	$('table.table').DataTable({
		processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/proddipajax') }}/{{ $user->id }}",
//         columns: [
//             { "data": "codice" },
//             { "data": "name" },
//             { "data": "tipo" },
//         ],
        columnDefs: [
            {
                render: function ( data, type, row ) 
                {
                    return row[0];
                },
                    targets: 0
                },
             { 
              	render: function ( data, type, row ) 
              	{
                	return row[1];
                },
                    targets: 1
              },
              {
				render: function ( data, type, row )
				{
					return row[2];
				},
					targets: 2
               },
               {
      				render: function ( data, type, row )
      				{
      					return row[5];
      				},
      					targets: 3
                     },                                                         
              {
    			render: function ( data, type, row )
    			{
        			@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
    				return '<a id="delete" class="btn btn-xs btn-delete btn-danger" data-toggle="modal" data-target="#deleteProduct" data-name="'+row[1]+'" data-id="'+row[3]+'" data-product="'+row[5]+'"><i class="fa fa-trash"></i></a>'
					+'<div class="modal fade" id="deleteProduct" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Elimina Prodotto</h4></div>'
					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".prodottiassociatiterzisti.destroy", ""], "method" => "delete", "style" => "display:inline"])!!}'
					+'<input type="hidden" name="users_id" value="{{$terzisti->id}}">'
    				+'<div class="modal-body"><div class="box-body"><p id="name"></p>'
    				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
    				+'{!!Form::submit( "Elimina", ["class"=>"btn btn-danger"])!!}</div>'
    				+'{!!Form::close()!!}</div></div></div>';
    				@else
        				return "";
    				@endif
    			},
    				targets: 4
              },              
            ],
            initComplete: function(settings, json){
                $('a#delete').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('product'),
							$id = $(this).data('id'),
							$container_name = $('p#name');
						$container_name.html('Sei sicuro di voler eliminare il prodotto '+$name+' ?');
						$('form').attr('action','/admin/prodottiassociatiterzisti/'+$id)
                    })
                $('a#edit').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('product'),
							$id = $(this).data('id'),
							$carico = $(this).data('carico'),
							$scarico = $(this).data('scarico'),
							$container_name = $('p#name');
						$container_name.html($name);
						$('input[name="carico"]').val($carico)
						$('input[name="scarico"]').val($scarico)
						$('form').attr('action','/admin/prodottiassociatiterzisti/'+$idproduct)
                    })                    
                },
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Cerca"
		},
	})	
});
</script>
@endpush