					<div class="form-group">
						<label for="role">Prodotti :</label>
						<select id="prodotti_id" class="form-control" data-placeholder="Seleziona Prodotti" rel="select2" name="prodotti_id[]" multiple>
							<?php
							$prodotti = App\Models\Prodotti::join('prodotti_associati','prodotti_associati.prodotti_id','=','prodotti.id')->join('users','users.id','=','prodotti_associati.users_id')->select('prodotti.*')->get();
							$users = App\User::where('context_id',$employee->id)->first(); 
							$prodotti = App\Models\Prodotti::all();
							$prodottiassociati = App\Models\ProdottiAssociati::all();
							?>
								@if(!$prodotti->isEmpty())
								@if($users->id != 1)
								@foreach($prodotti as $prodotto)					
									<option  @foreach($prodottiassociati as $prodottoassociato) @if($prodotto->id == $prodottoassociato->prodotti_id && $prodottoassociato->users_id == $users->id) selected @endif @endforeach value="{{ $prodotto->id }}">{{ $prodotto->name }} - ({{ $prodotto->codice }})</option>
								@endforeach
								@endif
								@else
								@if($users->id != 1)
									<option value="{{ $prodotto->id }}">{{ $prodotto->name }} - ({{ $prodotto->codice }})</option>
								@endif
								@endif
						</select>
					</div>					