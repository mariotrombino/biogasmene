@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/prodotti') }}">Prodotti</a> :
@endsection
@section("contentheader_description", $prodotti->$view_col)
@section("section", "Prodotti")
@section("section_url", url(config('laraadmin.adminRoute') . '/prodotti'))
@section("sub_section", "Modifica")

@section("htmlheader_title", "Modifica Prodotti : ".$prodotti->$view_col)

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
                {!! Form::model($prodotti, ['route' => [config('laraadmin.adminRoute') . '.prodotti.update', $prodotti->id ], 'method'=>'PUT', 'id' => 'prodotti-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'name')
					@la_input($module, 'codice')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Aggiorna', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/prodotti') }}" class="btn btn-default pull-right">Cancella</a>
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
    $("#prodotti-edit-form").validate({
        
    });
});
</script>
@endpush
