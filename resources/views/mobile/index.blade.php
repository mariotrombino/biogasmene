<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Mario Trombino">

    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="https://biogasmenè.it/mobile" />
    <meta property="og:sitename" content="Biogasmenè" />
    
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/mobile/css/bootstrap-4.1.1.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/la-assets/plugins/select2/select2.min.css') }}" rel="stylesheet">
	<link href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/r-2.2.1/sl-1.2.5/datatables.min.css" rel="stylesheet">
<style type="text/css">
.select2{
    width: 100% !important	
}
.error{
	color: red
}
</style>
</head>

<body>
<!-- <nav class="navbar navbar-expand-lg navbar-light bg-dark"> -->
<!-- <a class="navbar-brand" href="#">Biogasmenè</a> -->
<!-- <a href="/mobile/logout">Logout</a> -->
<!-- </nav> -->
<section class="container">
<article class="row">
<div class="col-lg-12">
<h2 class="text-center">{{ $user }}</h2>
<?php
use App\Models\Ingresso;
use App\Models\Uscita;

$prodottoterzistai = Ingresso::where('ingresso.users_id',Auth::id())->where('tara','=','')->select('tara')->limit(1)->get();
$prodottoterzistau = Uscita::where('uscita.users_id',Auth::id())->where('tara','=','')->select('tara')->limit(1)->get();
$carico = Ingresso::where('users_id',Auth::id())->where('carico','=','')->exists();
$scarico = Uscita::where('users_id',Auth::id())->where('scarico','=','')->exists();
?>
@foreach($prodottoterzistai as $tara)
								<p class="text-center alert alert-dismissible alert-danger" role="alert">Manca la Tara in Ingresso!
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
@endforeach
@foreach($prodottoterzistau as $tara)
								<p class="text-center alert alert-dismissible alert-danger" role="alert">Manca la Tara in Uscita!
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
@endforeach
@if($carico == true)
								<p class="text-center alert alert-dismissible alert-danger" role="alert">Ingresso : Tara in StandBy!
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
@endif
@if($scarico == true)
								<p class="text-center alert alert-dismissible alert-danger" role="alert">Uscita : Tara in StandBy!
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    						@if(Session::has('error_message_carico'))
								<p class="text-center alert alert-dismissible {{ Session::get('alert-class', 'alert-danger') }}" role="alert">{{ Session::get('error_message_carico') }}
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
							@endif
    						@if(Session::has('error_message_scarico'))
								<p class="text-center alert alert-dismissible {{ Session::get('alert-class', 'alert-danger') }}" role="alert">{{ Session::get('error_message_scarico') }}
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
							@endif							
    						@if(Session::has('success_message_carico'))
								<p class="text-center alert alert-dismissible {{ Session::get('alert-class', 'alert-success') }}" role="alert">{{ Session::get('success_message_carico') }}
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
							@endif
    						@if(Session::has('success_message_scarico'))
								<p class="text-center alert alert-dismissible {{ Session::get('alert-class', 'alert-success') }}" role="alert">{{ Session::get('success_message_scarico') }}
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
  								  </button>
								</p>
							@endif							
</div>
<div class="col-lg-12">
    <ul class="nav nav-pills text-center" role="tablist">
		<li class="nav-item col-lg-6"><a class="nav-link active tab-quota-carico" role="tab" data-toggle="tab" href="#tab-quota-carico" aria-controls="tab-quota-carico" aria-selected="true"><i class="fa fa-table"></i> INGRESSO</a></li>
		<li class="nav-item col-lg-6"><a class="nav-link tab-quota-scarico" role="tab" data-toggle="tab" href="#tab-quota-scarico" aria-controls="tab-quota-scarico" data-target="#tab-quota-scarico"><i class="fa fa-table"></i> USCITA</a></li>
    </ul>
    <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-quota-carico" role="tabpanel" aria-labelledby="tab-quota-carico">
  <div class="card-body text-center">
<button type="button" class="btn btn-warning">
  Quota Disponibile <span class="badge badge-light pesata"></span>
</button>
<button type="button" class="btn btn-warning">
 Quota Evasa <span class="badge badge-light evaso"></span>
</button>
<button type="button" class="btn btn-warning">
  Rimanenza <span class="badge badge-light rimanenza"></span>
</button>
  </div>

@if(Auth::user()->type =='Socio')
{!! Form::open(['action' => 'MobileController@caricosocio', 'id' => 'ingresso-add-form','style' => 'margin-top: 10px']) !!}
						<div class="form-group">
						<label for="prodotti_associati">Prodotti Associati</label>
						<select name="prodotti_associati" class="form-control" required>
						<option></option>
						</select>
						</div>
						<div class="form-group">
						<label for="carico">Pesata</label>
						<input type="number" name="carico" value="" class="form-control">
						</div>
						<div class="form-group">
						<label for="tara">Tara</label>
						<input @if($carico == true) disabled @endif type="number" name="tara" value="" class="form-control">
						</div>		
						<div class="form-group">
						{!! Form::submit( 'Invia', ['class'=>'btn btn-success btn-lg form-control','id' => 'send']) !!}
						</div>						
{!! Form::close() !!}
@endif
@if(Auth::user()->type =='Terzisti')
{!! Form::open(['action' => 'MobileController@caricoterzisti', 'id' => 'ingresso-add-form','style' => 'margin-top: 10px']) !!}

@if(!empty($soci))
<div class="form-group">
<label for="socio_id">Seleziona Socio</label>
<select name="socio_id" id="socio_id" class="form-control" required>
<option></option>
@foreach($soci as $socio)
<option value="{{ $socio->id }}">{{ $socio->name }}</option>
@endforeach
</select>
</div>
@endif
						<div class="form-group">
						<label for="prodotti_associati">Prodotti Associati</label>
						<select name="prodotti_associati" class="form-control" required>
						<option></option>
						</select>
						</div>
						<div class="form-group">
						<label for="carico">Pesata</label>
						<input type="number" name="carico" value="" class="form-control">
						</div>
						<div class="form-group">
						<label for="tara">Tara</label>
						<input @if($carico == true) disabled @endif type="number" name="tara" value="" class="form-control">
						</div>		
						<div class="form-group">
						{!! Form::submit( 'Invia', ['class'=>'btn btn-success btn-lg form-control','id' => 'send']) !!}
						</div>				
            {!! Form::close() !!}
            @endif
<table class="table ingresso responsive" style="width: 100%">
<thead>
<tr>
<td>
Socio
</td>
<td>
Prodotto
</td>
<td>
Lordo
</td>
<td>
Tara
</td>
<td>
Netto
</td>
<td>
Effettuato
</td>
<td>
Id
</td>
</tr>
</thead>
</table>
</div>
<div class="tab-pane fade" id="tab-quota-scarico" role="tabpanel" aria-labelledby="tab-quota-scarico">
  <div class="card-body text-center">
<button type="button" class="btn btn-warning">
  Quota Disponibile <span class="badge badge-light upesata"></span>
</button>
<button type="button" class="btn btn-warning">
  Quota Evasa <span class="badge badge-light uevaso"></span>
</button>
<button type="button" class="btn btn-warning">
  Rimanenza <span class="badge badge-light urimanenza"></span>
</button>
  </div>
@if(Auth::user()->type =='Socio')
{!! Form::open(['action' => 'MobileController@scaricosocio', 'id' => 'uscita-add-form','style' => 'margin-top: 10px']) !!}

						<div class="form-group">
						<label for="prodotti_associati_uscita">Prodotti Associati</label>
						<select name="prodotti_associati_uscita" class="form-control" required>
						<option></option>
						</select>
						</div>
						<div class="form-group">
						<label for="carico">Pesata</label>
						<input type="number" name="scarico" value="" class="form-control">
						</div>
						<div class="form-group">
						<label for="tara">Tara</label>
						<input @if($scarico == true) disabled @endif type="number" name="tarau" value="" class="form-control">
						</div>		
						<div class="form-group">
						{!! Form::submit( 'Invia', ['class'=>'btn btn-success btn-lg form-control','id' => 'sendexit']) !!}
						</div>				
            {!! Form::close() !!}
            @endif
@if(Auth::user()->type =='Terzisti')
{!! Form::open(['action' => 'MobileController@scaricoterzisti', 'id' => 'uscita-add-form','style' => 'margin-top: 10px']) !!}

@if(!empty($soci))
<div class="form-group">
<label for="socio_id_uscita">Seleziona Socio</label>
<select name="socio_id_uscita" id="socio_id_uscita" class="form-control" required>
<option></option>
@foreach($soci as $socio)
<option value="{{ $socio->id }}">{{ $socio->name }}</option>
@endforeach
</select>
</div>
@endif
						<div class="form-group">
						<label for="prodotti_associati_uscita">Prodotti Associati</label>
						<select name="prodotti_associati_uscita" class="form-control" required>
						<option></option>
						</select>
						</div>
						<div class="form-group">
						<label for="carico">Pesata</label>
						<input type="number" name="scarico" value="" class="form-control">
						</div>
						<div class="form-group">
						<label for="tara">Tara</label>
						<input @if($scarico == true) disabled @endif type="number" name="tarau" value="" class="form-control">
						</div>		
						<div class="form-group">
						{!! Form::submit( 'Invia', ['class'=>'btn btn-success btn-lg form-control','id' => 'sendexit']) !!}
						</div>				
            {!! Form::close() !!}
            @endif
<table class="table uscita responsive" style="width: 100%">
<thead>
<tr>
<td>
Socio
</td>
<td>
Prodotto
</td>
<td>
Lordo
</td>
<td>
Tara
</td>
<td>
Netto
</td>
<td>
Effettuato
</td>
</tr>
</thead>
</table>
</div>
</div>
</div>
</article>
</section>
    
    <script src="{{ asset('/la-assets/mobile/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/la-assets/mobile/jquery/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('/la-assets/plugins/select2/select2.min.js') }}"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/r-2.2.1/sl-1.2.5/datatables.min.js"></script>
		<script src="{{ asset('/la-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript">
$(document).ready(function(){
	$.fn.dataTable.Api.register( 'sum()', function ( ) {
	    return this.flatten().reduce( function ( a, b ) {
	        if ( typeof a === 'string' ) {
	            a = a.replace(/[^\d.-]/g, '') * 1;
	        }
	        if ( typeof b === 'string' ) {
	            b = b.replace(/[^\d.-]/g, '') * 1;
	        }
	 
	        return a + b;
	    }, 0 );
	} );
	var $tab = localStorage.getItem('active-tab');
	if($tab == '0'){
		$('#tab-quota-scarico').removeClass('show active');
		$('a.tab-quota-scarico').removeClass('active');
		$('#tab-quota-carico').addClass('show active');
		$('a.tab-quota-carico').addClass('active');
		}
	else if($tab == '1'){
		$('#tab-quota-carico').removeClass('show active');
		$('a.tab-quota-carico').removeClass('active');
		$('#tab-quota-scarico').addClass('show active');
		$('a.tab-quota-scarico').addClass('active');			
		}
    @if(Auth::user()->type=='Socio')
    $('select[name="prodotti_associati"]').select2({
        language: "it",
        placeholder: "Prodotto",
        allowClear: true,
        minimumResultsForSearch: -1,
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
        minimumResultsForSearch: -1,
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
    var $id = "{{Auth::id()}}";
	$('input#send').on('click',function(e){
		if($id != '' && $('select[name="prodotti_associati"]').val() != '')
			{
		localStorage.setItem('active-tab','0');
		localStorage.setItem('ingresso-socio', $id);
		localStorage.setItem('prodotto_ingresso-socio', $('select[name="prodotti_associati"]').val());
			}
		})
	$('input#sendexit').on('click',function(e){
		if($id != '' && $('select[name="prodotti_associati_uscita"]').val() != '')
			{
		localStorage.setItem('active-tab','1');
		localStorage.setItem('uscita-socio', $id);
		localStorage.setItem('prodotto_uscita-socio', $('select[name="prodotti_associati_uscita"]').val());
			}
		})
	$.getJSON("/mobile/ingresso/"+$id,function(json){
		$('select[name="prodotti_associati"]').empty();
		$('select[name="prodotti_associati"]').append('<option></option>');
		for (var i = 0; i < json.length; i++) {
			$('select[name="prodotti_associati"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
			$('select[name="prodotti_associati"]').on("select2:select", function(a) {
				$('span.pesata').html(a.params.data.element.dataset.pesata)
				$('span.evaso').html(a.params.data.element.dataset.evasa)
				$('span.rimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa)
			});
		}
	})
    			$.getJSON("/mobile/uscita/"+$id,function(json){
    	    		$('select[name="prodotti_associati_uscita"]').empty();
    	    		$('select[name="prodotti_associati_uscita"]').append('<option></option>');
    					for (var i = 0; i < json.length; i++) {
    						$('select[name="prodotti_associati_uscita"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>')
    						$('select[name="prodotti_associati_uscita"]').on("select2:select", function(a) {
    							$('span.upesata').html(a.params.data.element.dataset.pesata)
    							$('span.uevaso').html(a.params.data.element.dataset.evasa)
    							$('span.urimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa)
    						});
        					}
    				});
	var $ingresso_socio = localStorage.getItem('ingresso-socio'),
	$prodotto_ingresso_socio = localStorage.getItem('prodotto_ingresso-socio'),
	$uscita_socio = localStorage.getItem('uscita-socio'),
	$prodotto_uscita_socio = localStorage.getItem('prodotto_uscita-socio');

	if($uscita_socio != null && $uscita_socio != '' && $prodotto_uscita_socio != null && $prodotto_uscita_socio != ''){
	$.getJSON("/mobile/uscita/"+$uscita_socio,function(json){
		$('select[name="prodotti_associati_uscita"]').empty();
		$('select[name="prodotti_associati_uscita"]').append('<option></option>');
		for (var i = 0; i < json.length; i++) {
			$('select[name="prodotti_associati_uscita"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
			$('select[name="prodotti_associati_uscita"]').val($prodotto_uscita_socio).trigger('change');
			@if($scarico == true)
				$('select[name="prodotti_associati_uscita"] option:not(:selected)').attr('disabled',true);
			@endif
			if($prodotto_uscita_socio == json[i].id){
				var uevaso = json[i].evaso-json[i].tara;
				$('span.upesata').html(json[i].ingresso);
				$('span.uevaso').html(uevaso);
				$('span.urimanenza').html(json[i].ingresso-uevaso);
				}
			$('select[name="prodotti_associati_uscita"]').on("select2:select", function(a) {
					$('span.upesata').html(a.params.data.element.dataset.pesata)
					$('span.uevaso').html(a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara)
					$('span.urimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara)
			})
					}
	});
}
if($ingresso_socio != null && $ingresso_socio != '' && $prodotto_ingresso_socio != null && $prodotto_ingresso_socio != ''){
		$.getJSON("/mobile/ingresso/"+$ingresso_socio,function(json){
			$('select[name="prodotti_associati"]').empty();
			$('select[name="prodotti_associati"]').append('<option></option>');
			for (var i = 0; i < json.length; i++) {
				$('select[name="prodotti_associati"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
				$('select[name="prodotti_associati"]').val($prodotto_ingresso_socio).trigger('change');
				@if($carico == true)
					$('select[name="prodotti_associati"] option:not(:selected)').attr('disabled',true);
				@endif
				if($prodotto_ingresso_socio == json[i].id){
					var evaso = json[i].evaso-json[i].tara;
					$('span.pesata').html(json[i].ingresso);
					$('span.evaso').html(evaso);
					$('span.rimanenza').html(json[i].ingresso-evaso);
					}
				$('select[name="prodotti_associati"]').on("select2:select", function(a) {
					$('span.pesata').html(a.params.data.element.dataset.pesata);
					$('span.evaso').html(a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara);
					$('span.rimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara);
				});
				}
		})
	}	 
    @endif
	   var tableingresso = $('table.ingresso').DataTable({
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
	            'excel', 'pdf'
	        ],
	        stateSave: false,
	        ajax: {
	            url: "{{ url('/mobile/ingressojson') }}",
				dataSrc: ""
	            },
	        columns: [
	        	{ data: 'username'},
	        	{ data: 'name' },
	        	{ data: 'carico' },
	        	{ data: 'tara' },
	        	{ data: '' },
	        	{ data: 'created_at' },
	        	{ data: 'id'}
	            ],
	            order: [[ 5, 'desc' ]],
	        columnDefs: [
	            	{
	                render: function ( data, type, row ) 
	                {
	                    return row.carico-row.tara;
	                },
	                    targets: 4
	                },
	            	{
		                render: function ( data, type, row ) 
		                {
		                    return row.id;
		                },
		                    targets: 6,
		                    className: "invisible"
		                },	                             
	                ],
	                initComplete: function(){
		                var totalenetto = tableingresso.column(2).data().sum()-tableingresso.column(3).data().sum();
                	$('div.totale').html('<button type="button" class="btn btn-primary">Totale Netto: <span class="badge badge-light">'+totalenetto+'</span></button>');
	                	$("table.ingresso").on('search.dt', function() {
		                	var summa = tableingresso.column(2, {page:'current'} ).data().sum()-tableingresso.column(3, {page:'current'} ).data().sum();       	
	                    	$('div.totale').html('<button type="button" class="btn btn-primary">Totale Netto: <span class="badge badge-light">'+summa+'</span></button>');
	                	});
	                    }
	        }); 
    var tableuscita = $('table.uscita').DataTable({
    	dom: '<"row"<"col-lg-3 col-sm-12 text-center"B><"col-lg-3 col-sm-12 text-center"f><"col-lg-3 col-sm-12 text-center"l><"col-lg-3 col-sm-12 text-center"<"totaleu">>>rtp',
        language: {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Nessun risultato",
            "infoEmpty": "Nessun record",
            search: "",
            searchPlaceholder: "Cerca..."
        },
    	lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tutti"]],
        buttons: [
            'excel', 'pdf'
        ],
        stateSave: false,
        ajax: {
            url: "{{ url('/mobile/uscitajson') }}",
			dataSrc: ""
            },
        columns: [
        	{ data: 'username'},
        	{ data: 'name' },
        	{ data: 'scarico' },
        	{ data: 'tara' },
        	{ data: '' },
        	{ data: 'created_at' }
            ],
            order: [[ 5, 'desc' ]],
        columnDefs: [
            	{
                render: function ( data, type, row ) 
                {
                    return row.scarico-row.tara;
                },
                    targets: 4
                },              
                ],
                initComplete: function(){
	                var totalenettou = tableuscita.column(2).data().sum()-tableuscita.column(3).data().sum();
                	$('div.totaleu').html('<button type="button" class="btn btn-primary">Totale Netto: <span class="badge badge-light">'+totalenettou+'</span></button>');
                	$("table.uscita").on('search.dt', function() {
                    	var summau = tableuscita.column(2, {page:'current'} ).data().sum()-tableuscita.column(3, {page:'current'} ).data().sum();
                    	$('div.totaleu').html('<button type="button" class="btn btn-primary">Totale Netto: <span class="badge badge-light">'+summau+'</span></button>')
                	});                    
                    }     
        });  
    @if(Auth::user()->type=='Terzisti')
	$('select[name="socio_id_uscita"]').select2({
      language: "it",
      placeholder: "Socio",
      allowClear: true,
      minimumResultsForSearch: -1,
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
  })	
	$('select[name="socio_id"]').select2({
      language: "it",
      placeholder: "Socio",
      allowClear: true,
      minimumResultsForSearch: -1,
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
  })
      $('select[name="prodotti_associati"]').select2({
      language: "it",
      placeholder: "Prodotto",
      allowClear: true,
      minimumResultsForSearch: -1,
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
      minimumResultsForSearch: -1,
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
    	$('select[name="socio_id_uscita"]').on("select2:select", function(e) {
    		var $id = e.params.data.id;
    		$('select[name="prodotti_associati_uscita"]').empty();
    		$('select[name="prodotti_associati_uscita"]').append('<option></option>');
    			$.getJSON("/mobile/uscita/"+$id,function(json){
    					for (var i = 0; i < json.length; i++) {
    						$('select[name="prodotti_associati_uscita"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>')
    						$('select[name="prodotti_associati_uscita"]').on("select2:select", function(a) {
    							$('span.upesata').html(a.params.data.element.dataset.pesata)
    							$('span.uevaso').html(a.params.data.element.dataset.evasa)
    							$('span.urimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa)
    						});
        					}
    				})
    		});
	$('select[name="socio_id"]').on("select2:select", function(e) {
		var $id = e.params.data.id;
		$('select[name="prodotti_associati"]').empty();
		$('select[name="prodotti_associati"]').append('<option></option>');
			$.getJSON("/mobile/ingresso/"+$id,function(json){
					for (var i = 0; i < json.length; i++) {
						$('select[name="prodotti_associati"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
						$('select[name="prodotti_associati"]').on("select2:select", function(a) {
							$('span.pesata').html(a.params.data.element.dataset.pesata)
							$('span.evaso').html(a.params.data.element.dataset.evasa)
							$('span.rimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa)
						});
					}
				})
		});
	$('input#send').on('click',function(e){
		if($('select[name="socio_id"]').val() != '' && $('select[name="prodotti_associati"]').val() != '')
			{
		localStorage.setItem('active-tab','0');
		localStorage.setItem('ingresso', $('select[name="socio_id"]').val());
		localStorage.setItem('prodotto_ingresso', $('select[name="prodotti_associati"]').val());
			}
		})
	$('input#sendexit').on('click',function(e){
		if($('select[name="socio_id_uscita"]').val() != '' && $('select[name="prodotti_associati_uscita"]').val() != '')
			{
		localStorage.setItem('active-tab','1');
		localStorage.setItem('uscita', $('select[name="socio_id_uscita"]').val());
		localStorage.setItem('prodotto_uscita', $('select[name="prodotti_associati_uscita"]').val());
			}
		})		
	    $("#ingresso-add-form").validate({
        });
    $("#uscita-add-form").validate({
    });

    	var $ingresso = localStorage.getItem('ingresso'),
		$prodotto_ingresso = localStorage.getItem('prodotto_ingresso'),
		$uscita = localStorage.getItem('uscita'),
		$prodotto_uscita = localStorage.getItem('prodotto_uscita');
	if($uscita != null && $uscita != '' && $prodotto_uscita != null && $prodotto_uscita != ''){
		$('select[name="socio_id_uscita"]').val($uscita).trigger('change');
		@if($scarico == true)
			$('select[name="socio_id_uscita"] option:not(:selected)').attr('disabled',true);
		@endif
		$.getJSON("/mobile/uscita/"+$uscita,function(json){
			for (var i = 0; i < json.length; i++) {
				$('select[name="prodotti_associati_uscita"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
				$('select[name="prodotti_associati_uscita"]').val($prodotto_uscita).trigger('change');
				@if($scarico == true)
					$('select[name="prodotti_associati_uscita"] option:not(:selected)').attr('disabled',true);
				@endif
				if($prodotto_uscita == json[i].id){
					var uevaso = json[i].evaso-json[i].tara;
					$('span.upesata').html(json[i].ingresso);
					$('span.uevaso').html(uevaso);
					$('span.urimanenza').html(json[i].ingresso-uevaso);
					}
				$('select[name="prodotti_associati_uscita"]').on("select2:select", function(a) {
						$('span.upesata').html(a.params.data.element.dataset.pesata)
						$('span.uevaso').html(a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara)
						$('span.urimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara)
				})
						}
		});
	}
	if($ingresso != null && $ingresso != '' && $prodotto_ingresso != null && $prodotto_ingresso != ''){
			$('select[name="socio_id"]').val($ingresso).trigger('change');
			@if($carico == true)
				$('select[name="socio_id"] option:not(:selected)').attr('disabled',true);
			@endif
			$.getJSON("/mobile/ingresso/"+$ingresso,function(json){
				for (var i = 0; i < json.length; i++) {
					$('select[name="prodotti_associati"]').append('<option data-id="'+json[i].id+'" data-tara="'+json[i].tara+'" data-evasa="'+json[i].evaso+'" data-pesata="'+json[i].ingresso+'" value="'+json[i].id+'">'+json[i].name+'</option>');
					$('select[name="prodotti_associati"]').val($prodotto_ingresso).trigger('change');
					@if($carico == true)
						$('select[name="prodotti_associati"] option:not(:selected)').attr('disabled',true);
					@endif
					if($prodotto_ingresso == json[i].id){
						var evaso = json[i].evaso-json[i].tara;
						$('span.pesata').html(json[i].ingresso);
						$('span.evaso').html(evaso);
						$('span.rimanenza').html(json[i].ingresso-evaso);
						}
					$('select[name="prodotti_associati"]').on("select2:select", function(a) {
						$('span.pesata').html(a.params.data.element.dataset.pesata);
						$('span.evaso').html(a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara);
						$('span.rimanenza').html(a.params.data.element.dataset.pesata-a.params.data.element.dataset.evasa-a.params.data.element.dataset.tara);
					});
					}
			})
		}            
    @endif
})
	</script>
</body>
</html>