@extends('la.layouts.app')

@section('htmlheader_title')
	Vista Soci
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
			<div class="dats1">@if(!empty($employee->city)|| !empty($employee->address))<i class="fa fa-map-marker"></i> {{ $employee->city }}, {{ $employee->address }}@endif</div>
			<div class="dats1"><i class="fa fa-phone"></i> {{ $employee->mobile }}</div>
			<div class="dats1"><i class="fa fa-clock-o"></i> Inserito il {{ date("d M, Y", strtotime($employee->created_at)) }}</div>
		</div>	
		<div class="col-md-1 actions">
			@la_access("Employees", "edit")
				<a href="{{ url(config('laraadmin.adminRoute') . '/employees/'.$employee->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endla_access
			
			@la_access("Employees", "delete")
			 <a class="btn btn-default btn-delete btn-xs" data-toggle="modal" data-target="#AddModal"><i class="fa fa-times"></i></a>
			@endla_access
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}" data-toggle="tooltip" data-placement="right" title="Torna Indietro"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> Informazioni Generali</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#ingresso" data-target="#ingresso"><i class="fa fa-arrow-up"></i> Ingresso / <i class="fa fa-arrow-down"></i> Uscita</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#prodotti" data-target="#prodotti"><i class="fa fa-table"></i> Prodotti Associati</a></li>
		@if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<li class=""><a role="tab" data-toggle="tab" href="#tab-quota-carico" data-target="#tab-quota-carico"><i class="fa fa-table"></i> Imposta Prodotti in Ingresso</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#tab-quota-scarico" data-target="#tab-quota-scarico"><i class="fa fa-table"></i> Imposta Prodotti in Uscita</a></li>			
		@endif
		@if($employee->id == Auth::user()->context_id || Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
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
						<div class="form-group">
						<label for="code" class="col-md-4 col-sm-6 col-xs-6">Codice :</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">{{ $codice->codice }}
						</div></div>
						@la_display($module, 'city')
						@la_display($module, 'address')
						@if(!empty($prodotti))
						<div class="form-group">
						<label for="users_id" class="col-md-4 col-sm-6 col-xs-6">Prodotti :</label>
						<div class="col-md-8 col-sm-6 col-xs-6 fvalue">
						@foreach($prodotti as $prodottis)						
						<a href="{{ url(config('laraadmin.adminRoute') . '/prodotti') }}/{{ $prodottis->id }}" class="label label-primary">{{ $prodottis->name }}</a>
						@endforeach
						</div>
						</div>
						@endif	
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="ingresso">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>Ingressi</h4>
					</div>
					<div class="panel-body">
	<table class="responsive table ingresso" style="width: 100% !important">
	<thead>
	<tr>
	<td>Utente</td>
	<td>Codice Prodotto</td>
	<td>Nome Prodotto</td>
	<td>Lordo</td>
<td>Tara</td>
	<td>Netto</td>
	<td>Azioni</td>
	<td>Effettuato</td>
	</tr>
	</thead>
	<tbody>
	</tbody>
	    <tfoot>
            <tr>
                <th colspan="7" style="text-align:right">Totale:</th>
                <th></th>
            </tr>
        </tfoot>	
	</table>
	<hr>
						<div class="panel-default panel-heading">
						<h4>Uscita</h4>
					</div>
					<div class="panel-body">
	<table class="responsive table uscita" style="width: 100% !important">
	<thead>
	<tr>
	<td>Utente</td>
	<td>Codice Prodotto</td>
	<td>Nome Prodotto</td>
	<td>Lordo</td>
<td>Tara</td>
	<td>Netto</td>
	<td>Effettuato</td>
	<td>Azioni</td>
	</tr>
	</thead>
	<tbody></tbody>
	    <tfoot>
            <tr>
                <th colspan="7" style="text-align:right">Totale:</th>
                <th></th>
            </tr>
        </tfoot>
	</table>
	</div>
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
	<table class="responsive table prodotti" style="width: 100% !important">
	<thead>
	<tr>
	<td>Codice Prodotto</td>
	<td>Nome Prodotto</td>
	<td>Tipo</td>
	<td>Quota Disponibile</td>
	<td>Evaso</td>
	<td>Da Evadere</td>
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
		@if($employee->id == Auth::user()->context_id || Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/change_password/'.$employee->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
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
				<form action="{{ url(config('laraadmin.adminRoute') . '/soci_cambia_carico/'.$employee->id) }}" id="carico-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
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
							<div class="form-group">
								<label for="prodotti_associati" class="col-md-2">Prodotti in Ingresso</label>
								<div class="col-md-10">
								<?php $prodotti = App\Models\Prodotti::where('tipo','=','ingresso')->get(); ?>
								<select name="prodotti_associati" rel="select2">
								@foreach($prodotti as $prodotto)
								<option value="{{ $prodotto->id }}">{{ $prodotto->name }} ({{ $prodotto->codice }})</option>
								@endforeach
								</select>
								</div>
								</div>
								<div class="form-group">
								<label for="carico" class="col-md-2">Quota Disponibile</label>
								<div class="col-md-10">
									<input type="number" name="carico" value="" id="carico" class="form-control" placeholder="Carico..." autocomplete="off" required="required">
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
		<div role="tabpanel" class="tab-pane fade" id="tab-quota-scarico">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/soci_cambia_scarico/'.$employee->id) }}" id="scarico-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
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
							<div class="form-group">
								<label for="prodotti_associati" class="col-md-2">Prodotti in Uscita</label>
								<div class="col-md-10">
								<?php $prodotti = App\Models\Prodotti::where('tipo','=','uscita')->get(); ?>
								<select name="prodotti_associati" rel="select2">
								@foreach($prodotti as $prodotto)
								<option value="{{ $prodotto->id }}">{{ $prodotto->name }} ({{ $prodotto->codice }})</option>
								@endforeach
								</select>
								</div>
								</div>
								<div class="form-group">
								<label for="carico" class="col-md-2">Quota Disponibile</label>
								<div class="col-md-10">
									<input type="number" name="carico" value="" id="ucarico" class="form-control" placeholder="Carico..." autocomplete="off" required="required">
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
	</div>
	</div>
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Elimina Socio</h4>
            </div>
            {{ Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $employee->id], 'method' => 'delete', 'style'=>'display:inline']) }}
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

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/r-2.2.1/sl-1.2.5/datatables.min.js"></script>
<script>
$(function () {
	@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	$('#carico-reset-form').validate({
		
	});
	$('#scarico-reset-form').validate({
		
	});
	@endif
	$('table.prodotti').DataTable({
		processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/prodajax') }}/{{ $user->id }}",
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
    					return row[3];
    				},
    					targets: 3
                  },
                  {
        				render: function ( data, type, row )
        				{
        					return row[6]-row[7];
        				},
        					targets: 4
                      },
                      {
            				render: function ( data, type, row )
            				{
                				var evaso = row[6]-row[7];
            					return row[3]-evaso;
            				},
            					targets: 5
                          },                                                                  
              {
    			render: function ( data, type, row )
    			{
        			var ids = row[5];
        			@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
    				return '<a id="edit" class="btn btn-xs btn-edit btn-info" data-toggle="modal" data-target="#editProduct" data-carico="'+row[3]+'" data-name="'+row[1]+'" data-id="'+row[6]+'" data-product="'+row[4]+'" style="margin-right:3px"><i class="fa fa-edit"></i></a>'
    				+'<a id="delete" class="btn btn-xs btn-delete btn-danger" data-toggle="modal" data-target="#deleteProduct" data-name="'+row[1]+'" data-id="'+row[6]+'" data-product="'+row[4]+'"><i class="fa fa-trash"></i></a>'
					+'<div class="modal fade" id="editProduct" role="dialog" aria-labelledby="myModal"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModal">Aggiorna Prodotto</h4></div>'
					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".prodottiassociati.update", ""], "method" => "PUT"])!!}'
					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
    				+'<div class="modal-body"><div class="box-body">'
    				+'<p id="name"></p>'
    				+'<div class="form-group" style="border-bottom: 0px">'
    				+'<label for="carico" class="col-md-2">Quota Disponibile </label>'
					+'<div class="col-md-10">'
    				+'<input name="carico" type="number" value="" class="form-control" required>'
    				+'</div></div>'   				
    				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
    				+'{!!Form::submit( "Aggiorna", ["class"=>"btn btn-danger"])!!}</div>'
    				+'{!!Form::close()!!}</div></div></div>'
					+'<div class="modal fade" id="deleteProduct" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Elimina Prodotto</h4></div>'
					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".prodottiassociati.destroy", ""], "method" => "delete", "style" => "display:inline"])!!}'
					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
    				+'<div class="modal-body"><div class="box-body"><p id="name"></p>'
    				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
    				+'{!!Form::submit( "Elimina", ["class"=>"btn btn-danger"])!!}</div>'
    				+'{!!Form::close()!!}</div></div></div>';
    				@else
        				return "";
    				@endif
    			},
    				targets: 6 
              },              
            ],
            initComplete: function(settings, json){
                $('a#delete').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('product'),
							$id = $(this).data('id'),
							$container_name = $('p#name');
						$container_name.html('Sei sicuro di voler eliminare il prodotto '+$name+' ?');
						$('form').attr('action','/admin/prodottiassociati/'+$idproduct)
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
						$('form').attr('action','/admin/prodottiassociati/'+$idproduct)
                    })                    
                },
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Cerca"
		},
	})
	$('table.ingresso').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            lordo = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            tara = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );   
            total = lordo-tara;
            // Total over this page
            lordoTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            taraTotal = api
            .column( 3, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            pageTotal = lordoTotal-taraTotal;
            // Update footer
            $( api.column( 3 ).footer() ).html(
                'Netto '+pageTotal +' ( '+ total +' Netto totale)'
            );
        },		
    	dom: '<"row"<"col-lg-3 col-sm-12 text-center"B><"col-lg-3 col-sm-12 text-center"f><"col-lg-3 col-sm-12 text-center"l><"col-lg-3 col-sm-12 text-center"<"totale">>>rtp',
        language: {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Nessun risultato",
            "infoEmpty": "Nessun record",
            search: "",
            searchPlaceholder: "Cerca..."
        },
    	lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tutti"]],
        buttons: [
            {
				extend: 'pdfHtml5',
				title: 'Ingresso',
				filename: "{{ $user->name }}-<?php echo \Carbon\Carbon::now(); ?>",
				exportOptions: {
					columns: [0,1,2,3,4,5,7]
					}
                },
                {
    				extend: 'excel',
    				title: 'Ingresso',
    				filename: "{{ $user->name }}-<?php echo \Carbon\Carbon::now(); ?>",
    				exportOptions: {
    					columns: [0,1,2,3,4,5,7]
    					}
                    },               
        ],
		processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/prodotti/ingresso') }}/{{ $user->id }}",
        columnDefs: [
            {
                render: function ( data, type, row ) 
                {
                    return row[5];
                },
                    targets: 0
                },
             { 
              	render: function ( data, type, row ) 
              	{
                	return row[0];
                },
                    targets: 1
              },
              {
				render: function ( data, type, row )
				{
					return row[1];
				},
					targets: 2
              },
              {
    				render: function ( data, type, row )
    				{
    					return row[2];
    				},
    					targets: 3
                  },
                  {
        				render: function ( data, type, row )
        				{
        					return row[3];
        				},
        					targets: 4
                      },                  
                  {
        				render: function ( data, type, row )
        				{
        					return row[2]-row[3];
        				},
        					targets: 5
                      },
                      {
            				render: function ( data, type, row )
            				{   
                			@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
            					return '<a id="editIn" class="btn btn-xs btn-edit btn-info" data-toggle="modal" data-target="#editIngresso" data-lordo="'+row[2]+'" data-tara="'+row[3]+'" data-name="'+row[1]+'" data-id="'+row[4]+'" style="margin-right:3px"><i class="fa fa-edit"></i></a>'
                			+'<a id="deleteIn" class="btn btn-xs btn-delete btn-danger" data-toggle="modal" data-target="#deleteIngresso" data-name="'+row[1]+'" data-id="'+row[4]+'"><i class="fa fa-trash"></i></a>'
        					+'<div class="modal fade" id="editIngresso" role="dialog" aria-labelledby="myModal"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModal">Aggiorna Ingresso</h4></div>'
        					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".ingresso.update", ""], "method" => "PUT"])!!}'
        					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
            				+'<div class="modal-body"><div class="box-body">'
            				+'<p id="name"></p>'
            				+'<div class="form-group" style="border-bottom: 0px">'
            				+'<label for="carico" class="col-md-2">Pesata </label>'
        					+'<div class="col-md-10">'
            				+'<input name="carico" type="number" value="'+row[2]+'" class="form-control" required>'
            				+'</div></div>'
            				+'<div class="form-group" style="border-bottom: 0px">'
            				+'<label for="carico" class="col-md-2">Tara </label>'
        					+'<div class="col-md-10">'
            				+'<input name="tara" type="number" value="'+row[3]+'" class="form-control">'
            				+'</div></div>'           				 				
            				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
            				+'{!!Form::submit( "Aggiorna", ["class"=>"btn btn-danger"])!!}</div>'
            				+'{!!Form::close()!!}</div></div></div>'
        					+'<div class="modal fade" id="deleteIngresso" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Elimina Ingresso</h4></div>'
        					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".ingresso.destroy", ""], "method" => "delete", "style" => "display:inline"])!!}'
        					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
            				+'<div class="modal-body"><div class="box-body"><p id="name"></p>'
            				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
            				+'{!!Form::submit( "Elimina", ["class"=>"btn btn-danger"])!!}</div>'
            				+'{!!Form::close()!!}</div></div></div>';
                			@else
            					return '';
        					@endif
            				},
            					targets: 6
                          },
                          {
                				render: function ( data, type, row )
                				{
                					return row[6];
                				},
                					targets: 7								
                              }, 
                           
            ],
            initComplete: function(settings, json){
                $('a#deleteIn').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('id'),
							$container_name = $('p#name');
						$container_name.html('Sei sicuro di voler eliminare l\'ingresso '+$name+' ?');
						$('form').attr('action','/admin/ingresso/'+$idproduct)
                    })
                $('a#editIn').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('id'),
							$carico = $(this).data('lordo'),
							$tara = $(this).data('tara'),
							$container_name = $('p#name');
						$container_name.html($name);
						$('input[name="carico"]').val($carico)
						$('input[name="tara"]').val($tara)
						$('form').attr('action','/admin/ingresso/'+$idproduct)
                    })                    
                },
	})
	$('table.uscita').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            lordo = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            tara = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );   
            total = lordo-tara;
            // Total over this page
            lordoTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            taraTotal = api
            .column( 3, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            pageTotal = lordoTotal-taraTotal
            // Update footer
            $( api.column( 5 ).footer() ).html(
                'Netto '+pageTotal +' ( '+ total +' Netto totale)'
            );
        },
    	dom: '<"row"<"col-lg-3 col-sm-12 text-center"B><"col-lg-3 col-sm-12 text-center"f><"col-lg-3 col-sm-12 text-center"l><"col-lg-3 col-sm-12 text-center"<"totale">>>rtp',
        language: {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Nessun risultato",
            "infoEmpty": "Nessun record",
            search: "",
            searchPlaceholder: "Cerca..."
        },
    	lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tutti"]],
        buttons: [
            {
				extend: 'pdfHtml5',
				title: 'Uscita',
				filename: "{{ $user->name }}-<?php echo \Carbon\Carbon::now(); ?>",
				exportOptions: {
					columns: [0,1,2,3,4,5,6]
					}
                },
                {
    				extend: 'excel',
    				title: 'Uscita',
    				filename: "{{ $user->name }}-<?php echo \Carbon\Carbon::now(); ?>",
    				exportOptions: {
    					columns: [0,1,2,3,4,5,6]
    					}
                    },
        ],
		processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/prodotti/uscita') }}/{{ $user->id }}",
        columnDefs: [
            {
                render: function ( data, type, row ) 
                {
                    return row[5];
                },
                    targets: 0
                },
             { 
              	render: function ( data, type, row ) 
              	{
                	return row[0];
                },
                    targets: 1
              },
              {
				render: function ( data, type, row )
				{
					return row[1];
				},
					targets: 2
              },
              {
    				render: function ( data, type, row )
    				{
    					return row[2];
    				},
    					targets: 3
                  },
                  {
        				render: function ( data, type, row )
        				{
        					return row[3];
        				},
        					targets: 4
                      },                  
                  {
        				render: function ( data, type, row )
        				{
        					return row[2]-row[3];
        				},
        					targets: 5
                      },
                      {
            				render: function ( data, type, row )
            				{   
                			@if(Entrust::hasRole("ADMIN") || Entrust::hasRole("SUPER_ADMIN"))
            					return '<a id="editEx" class="btn btn-xs btn-edit btn-info" data-toggle="modal" data-target="#editUscita" data-lordo="'+row[2]+'" data-tara="'+row[3]+'" data-name="'+row[1]+'" data-id="'+row[4]+'" style="margin-right:3px"><i class="fa fa-edit"></i></a>'
                			+'<a id="deleteEx" class="btn btn-xs btn-delete btn-danger" data-toggle="modal" data-target="#deleteUscita" data-name="'+row[1]+'" data-id="'+row[4]+'"><i class="fa fa-trash"></i></a>'
        					+'<div class="modal fade" id="editUscita" role="dialog" aria-labelledby="myModal"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModal">Aggiorna Uscita</h4></div>'
        					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".uscita.update", ""], "method" => "PUT"])!!}'
        					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
            				+'<div class="modal-body"><div class="box-body">'
            				+'<p id="name"></p>'
            				+'<div class="form-group" style="border-bottom: 0px">'
            				+'<label for="scarico" class="col-md-2">Pesata </label>'
        					+'<div class="col-md-10">'
            				+'<input name="scarico" type="number" value="'+row[2]+'" class="form-control" required>'
            				+'</div></div>'
            				+'<div class="form-group" style="border-bottom: 0px">'
            				+'<label for="carico" class="col-md-2">Tara </label>'
        					+'<div class="col-md-10">'
            				+'<input name="tara" type="number" value="'+row[3]+'" class="form-control">'
            				+'</div></div>'           				 				
            				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
            				+'{!!Form::submit( "Aggiorna", ["class"=>"btn btn-danger"])!!}</div>'
            				+'{!!Form::close()!!}</div></div></div>'
        					+'<div class="modal fade" id="deleteUscita" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Elimina Uscita</h4></div>'
        					+'{!!Form::open(["route" => [config("laraadmin.adminRoute") . ".uscita.destroy", ""], "method" => "delete", "style" => "display:inline"])!!}'
        					+'<input type="hidden" name="users_id" value="{{$employee->id}}">'
            				+'<div class="modal-body"><div class="box-body"><p id="name"></p>'
            				+'</div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>'
            				+'{!!Form::submit( "Elimina", ["class"=>"btn btn-danger"])!!}</div>'
            				+'{!!Form::close()!!}</div></div></div>';
                			@else
            					return '';
        					@endif
            				},
            					targets: 7
                          },
                          {
                				render: function ( data, type, row )
                				{
                					return row[6];
                				},
                					targets: 6								
                              },                             
            ],
            initComplete: function(settings, json){
                $('a#deleteEx').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('id'),
							$container_name = $('p#name');
						$container_name.html('Sei sicuro di voler eliminare l\'uscita '+$name+' ?');
						$('form').attr('action','/admin/uscita/'+$idproduct)
                    })
                $('a#editEx').on('click',function(){
						var $name = $(this).data('name'),
							$idproduct = $(this).data('id'),
							$carico = $(this).data('lordo'),
							$tara = $(this).data('tara'),
							$container_name = $('p#name');
						$container_name.html($name);
						$('input[name="scarico"]').val($carico)
						$('input[name="tara"]').val($tara)
						$('form').attr('action','/admin/uscita/'+$idproduct)
                    })                    
                },
	})		
});
</script>
@endpush