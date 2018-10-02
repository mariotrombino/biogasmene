<?php
/**
 * Controller generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Associati;
use App\Models\ProdottiAssociatiTerzisti;
use Validator;
use App\Models\Ingresso;
use App\Models\ProdottiAssociati;
use App\Models\Uscita;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class MobileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('mobile');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
       if(Auth::id() != 0){
           $user = Auth::user()->name;
           $id = Auth::id();
           if(Auth::user()->type=="Terzisti") {
               $soci = Associati::where('associati.terzisti_id',Auth::user()->terzisti_id)->join('users','users.id','=','associati.users_id')->get();
           }
           else {
               $soci = '';
           }
           return view('mobile.index',[
               'user' => $user,
               'soci' => $soci,
               
           ]);
       }
        else
        {
            return view('mobile.login');
        }
    }
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/mobile/login');
    }
    public function prodingressoajax($id){
        if(Auth::user()->type=='Socio'){
            $prodotti = ProdottiAssociati::where('users_id','=', $id)->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->where('prodotti.tipo','=','ingresso')->select('prodotti.*','prodotti_associati.ingresso','prodotti_associati.evaso','prodotti_associati.tara')->get();
            return response()->json($prodotti);
        }
        elseif(Auth::user()->type=='Terzisti'){
            $prodotti = ProdottiAssociatiTerzisti::where('prodotti_associati_terzisti.users_id','=', Auth::id())->join('prodotti_associati','prodotti_associati.prodotti_id','=','prodotti_associati_terzisti.prodotti_id')->join('prodotti','prodotti.id','=','prodotti_associati_terzisti.prodotti_id')->where('prodotti_associati.users_id','=',$id)->where('prodotti.tipo','=','ingresso')->select('prodotti.*','prodotti_associati.evaso','prodotti_associati.ingresso','prodotti_associati.tara')->distinct()->get();
            return response()->json($prodotti);
        }
    }
    public function produscitaajax($id){
        if(Auth::user()->type=='Socio'){
            $prodotti = ProdottiAssociati::where('users_id','=', $id)->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->where('prodotti.tipo','=','uscita')->select('prodotti.*','prodotti_associati.evaso','prodotti_associati.ingresso','prodotti_associati.tara')->get();
            return response()->json($prodotti);
        }
        elseif(Auth::user()->type=='Terzisti'){
            $prodotti = ProdottiAssociatiTerzisti::where('prodotti_associati_terzisti.users_id','=', Auth::id())->join('prodotti','prodotti.id','=','prodotti_associati_terzisti.prodotti_id')->join('prodotti_associati','prodotti_associati.prodotti_id','=','prodotti_associati_terzisti.prodotti_id')->where('prodotti_associati.users_id','=',$id)->where('prodotti.tipo','=','uscita')->select('prodotti.*','prodotti_associati.evaso','prodotti_associati.ingresso','prodotti_associati.tara')->distinct()->get();
            return response()->json($prodotti);
        }
    }
    public function caricosocio(Request $request){
        
        $validator = Validator::make($request->all(), [
            'prodotti_associati' => 'required'
        ]);
        $carico = Ingresso::where('users_id',Auth::id())->where('prodotti_id',$request->prodotti_associati)->where('socio_id',Auth::id())->where('carico','=','')->exists();
        if($validator->fails())
        {
            return \Redirect::to('/mobile')->withErrors($validator);
        }
        else if($request->carico == "")
        {
            \Session::flash('error_message_carico','Errore! Immettere la pesata.');
            return redirect('/mobile');
        }
//         else if($request->carico == "" && $request->tara != "")
//         {
//             Ingresso::create([
//                 'socio_id' => Auth::id(),
//                 'users_id' => Auth::id(),
//                 'prodotti_id' => $request->prodotti_associati,
//                 'carico' => $request->carico,
//                 'tara' => $request->tara
//             ]);
//             return redirect('/mobile');
//         }
//         else if($request->carico != "" && $carico == true)
//         {
//             Ingresso::where('users_id',Auth::id())->where('carico','=','')->where('prodotti_id',$request->prodotti_associati)->where('socio_id',Auth::id())->update(['carico'=> $request->carico]);
            
//             $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',Auth::id())->first();
//             $socio->evaso = $socio->evaso+$request->carico;
//             $socio->save();
            
//             \Session::flash('success_message_carico','Ingresso eseguito completo.');
//             return redirect('/mobile');
//         }
        else if($request->carico != "" && $request->tara == "")
        {
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',Auth::id())->first();
            $socio->evaso = $socio->evaso+$request->carico;
            $socio->save();
            Ingresso::create([
                'socio_id' => Auth::id(),
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati,
                'carico' => $request->carico,
                'tara' => $request->tara
            ]);
            \Session::flash('success_message_carico','Ingresso eseguito con pesata.');
            return redirect('/mobile');
        }
        else if($request->carico != "" && $request->tara != "")
        {
            Ingresso::create([
                'socio_id' => Auth::id(),
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati,
                'carico' => $request->carico,
                'tara' => $request->tara
            ]);
            Ingresso::where('users_id',Auth::id())->where('socio_id',Auth::id())->where('tara','=','')->update(['tara'=> $request->tara]);
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',Auth::id())->first();
            $uptara = Ingresso::where('prodotti_id','=',$request->prodotti_associati)->where('socio_id','=',Auth::id())->where('users_id','=',Auth::id())->sum('tara');
            $socio->evaso = $socio->evaso+$request->carico;
            $socio->save();
            ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',Auth::id())->update(['tara'=> $uptara]);
            \Session::flash('success_message_carico','Ingresso eseguito con pesata e tara.');
            return redirect('/mobile');
        }
    }
    public function scaricosocio(Request $request){
        
        $validator = Validator::make($request->all(), [
            'prodotti_associati_uscita' => 'required'
        ]);
        $carico = Uscita::where('users_id',Auth::id())->where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',Auth::id())->where('scarico','=','')->exists();
        if($validator->fails())
        {
            return \Redirect::to('/mobile')->withErrors($validator);
        }
        else if($request->scarico == "")
        {
            \Session::flash('error_message_scarico','Errore! Immettere la pesata.');
            return redirect('/mobile');
        }
//         else if($request->scarico == "" && $request->tarau != "")
//         {
//             Uscita::create([
//                 'socio_id' => Auth::id(),
//                 'users_id' => Auth::id(),
//                 'prodotti_id' => $request->prodotti_associati_uscita,
//                 'scarico' => $request->scarico,
//                 'tara' => $request->tarau
//             ]);
//             return redirect('/mobile');
//         }
//         else if($request->scarico != "" && $carico == true)
//         {
//             Uscita::where('users_id',Auth::id())->where('scarico','=','')->where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',Auth::id())->update(['scarico'=> $request->scarico]);
            
//             $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',Auth::id())->where('socio_id','=',Auth::id())->first();
//             $socio->evaso = $socio->evaso+$request->scarico;
//             $socio->save();
            
//             \Session::flash('success_message_scarico','Uscita eseguita completa.');
//             return redirect('/mobile');
//         }
        else if($request->scarico != "" && $request->tarau == "")
        {
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',Auth::id())->first();
            $socio->evaso = $socio->evaso+$request->scarico;
            $socio->save();
            Uscita::create([
                'socio_id' => Auth::id(),
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati_uscita,
                'scarico' => $request->scarico,
                'tara' => $request->tarau
            ]);
            \Session::flash('success_message_scarico','Uscita eseguita con pesata.');
            return redirect('/mobile');
        }
        else if($request->scarico != "" && $request->tarau != "")
        {
            Uscita::create([
                'socio_id' => Auth::id(),
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati_uscita,
                'scarico' => $request->scarico,
                'tara' => $request->tarau
            ]);
            Uscita::where('users_id',Auth::id())->where('socio_id',Auth::id())->where('tara','=','')->update(['tara'=> $request->tarau]);
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id',Auth::id())->first();
            $uptara = Uscita::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('socio_id','=',Auth::id())->where('users_id',Auth::id())->sum('tara');
            $socio->evaso = $socio->evaso+$request->scarico;
            $socio->save();
            ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',Auth::id())->update(['tara'=> $uptara]);
            \Session::flash('success_message_scarico','Uscita eseguita con pesata e tara.');
            return redirect('/mobile');
        }
    }
    public function caricoterzisti(Request $request){
        
        $validator = Validator::make($request->all(), [
            'prodotti_associati' => 'required',
            'socio_id' => 'required'
        ]);
        $carico = Ingresso::where('users_id',Auth::id())->where('prodotti_id',$request->prodotti_associati)->where('socio_id',$request->socio_id)->where('carico','=','')->exists();
        if($validator->fails())
        {
                        return \Redirect::to('/mobile')->withErrors($validator);
        }
        else if($request->carico == "")
        {
                        \Session::flash('error_message_carico','Errore! Immettere la pesata.');
                        return redirect('/mobile');
        }
//         else if($request->carico == "" && $request->tara != "")
//         {
//             Ingresso::create([
//                 'socio_id' => $request->socio_id,
//                 'users_id' => Auth::id(),
//                 'prodotti_id' => $request->prodotti_associati,
//                 'carico' => $request->carico,
//                 'tara' => $request->tara
//             ]);
//             return redirect('/mobile');
//         }
//         else if($request->carico != "" && $carico == true)
//         {
//             Ingresso::where('users_id',Auth::id())->where('carico','=','')->where('prodotti_id',$request->prodotti_associati)->where('socio_id',$request->socio_id)->update(['carico'=> $request->carico]);
//             $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati)->where('socio_id',$request->socio_id)->where('users_id',Auth::id())->first();
//             $prodotto->evaso = $prodotto->evaso+$request->carico;
//             $prodotto->save();
            
//             $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',$request->socio_id)->first();
//             $socio->evaso = $socio->evaso+$request->carico;
//             $socio->save();
            
//             \Session::flash('success_message_carico','Ingresso eseguito completo.');
//             return redirect('/mobile');
//         }
        else if($request->carico != "" && $request->tara == "")
        {
            $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati)->where('socio_id',$request->socio_id)->where('users_id',Auth::id())->first();
            
            $prodotto->evaso = $prodotto->evaso+$request->carico;
            $prodotto->save();
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',$request->socio_id)->first();
            $socio->evaso = $socio->evaso+$request->carico;
            $socio->save();
            Ingresso::create([
                'socio_id' => $request->socio_id,
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati,
                'carico' => $request->carico,
                'tara' => $request->tara
            ]);
            \Session::flash('success_message_carico','Ingresso eseguito con pesata.');
            return redirect('/mobile');
        }
        else if($request->carico != "" && $request->tara != "")
        {
            $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati)->where('socio_id',$request->socio_id)->where('users_id',Auth::id())->first();
            
            $prodotto->evaso = $prodotto->evaso+$request->carico;
            $prodotto->save();
            Ingresso::create([
                'socio_id' => $request->socio_id,
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati,
                'carico' => $request->carico,
                'tara' => $request->tara
            ]);
            Ingresso::where('users_id',Auth::id())->where('tara','=','')->update(['tara'=> $request->tara]);
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',$request->socio_id)->first();
            $uptara = Ingresso::where('prodotti_id','=',$request->prodotti_associati)->where('socio_id','=',$request->socio_id)->sum('tara');
            $socio->evaso = $socio->evaso+$request->carico;
            $socio->save();
            ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati)->where('users_id','=',$request->socio_id)->update(['tara'=> $uptara]);
            \Session::flash('success_message_carico','Ingresso eseguito con pesata e tara.');
            return redirect('/mobile');
        }
    }
    public function scaricoterzisti(Request $request){

        $validator = Validator::make($request->all(), [
            'prodotti_associati_uscita' => 'required',
            'socio_id_uscita' => 'required'
        ]);
        $carico = Uscita::where('users_id',Auth::id())->where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',$request->socio_id_uscita)->where('scarico','=','')->exists();
        if($validator->fails())
        {
            return \Redirect::to('/mobile')->withErrors($validator);
        }
        else if($request->scarico == "")
        {
            \Session::flash('error_message_scarico','Errore! Immettere la pesata.');
            return redirect('/mobile');
        }
//         else if($request->scarico == "" && $request->tarau != "")
//         {
//             Uscita::create([
//                 'socio_id' => $request->socio_id_uscita,
//                 'users_id' => Auth::id(),
//                 'prodotti_id' => $request->prodotti_associati_uscita,
//                 'scarico' => $request->scarico,
//                 'tara' => $request->tarau
//             ]);
//             return redirect('/mobile');
//         }
//         else if($request->scarico != "" && $carico == true)
//         {
//             Uscita::where('users_id',Auth::id())->where('scarico','=','')->where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',$request->socio_id_uscita)->update(['scarico'=> $request->scarico]);
//             $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',$request->socio_id_uscita)->where('users_id',Auth::id())->first();
//             $prodotto->evaso = $prodotto->evaso+$request->scarico;
//             $prodotto->save();
            
//             $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',$request->socio_id_uscita)->first();
//             $socio->evaso = $socio->evaso+$request->scarico;
//             $socio->save();
            
//             \Session::flash('success_message_scarico','Uscita eseguita completa.');
//             return redirect('/mobile');
//         }
        else if($request->scarico != "" && $request->tarau == "")
        {
            $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',$request->socio_id_uscita)->where('users_id',Auth::id())->first();
            
            $prodotto->evaso = $prodotto->evaso+$request->scarico;
            $prodotto->save();
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',$request->socio_id_uscita)->first();
            $socio->evaso = $socio->evaso+$request->scarico;
            $socio->save();
            Uscita::create([
                'socio_id' => $request->socio_id_uscita,
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati_uscita,
                'scarico' => $request->scarico,
                'tara' => $request->tarau
            ]);
            \Session::flash('success_message_scarico','Uscita eseguita con pesata.');
            return redirect('/mobile');
        }
        else if($request->scarico != "" && $request->tarau != "")
        {
            $prodotto = ProdottiAssociatiTerzisti::where('prodotti_id',$request->prodotti_associati_uscita)->where('socio_id',$request->socio_id_uscita)->where('users_id',Auth::id())->first();
            
            $prodotto->evaso = $prodotto->evaso+$request->scarico;
            $prodotto->save();
            Uscita::create([
                'socio_id' => $request->socio_id_uscita,
                'users_id' => Auth::id(),
                'prodotti_id' => $request->prodotti_associati_uscita,
                'scarico' => $request->scarico,
                'tara' => $request->tarau
            ]);
            Uscita::where('users_id',Auth::id())->where('tara','=','')->update(['tara'=> $request->tarau]);
            $socio = ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',$request->socio_id_uscita)->first();
            $uptara = Uscita::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('socio_id','=',$request->socio_id_uscita)->sum('tara');
            $socio->evaso = $socio->evaso+$request->scarico;
            $socio->save();
            ProdottiAssociati::where('prodotti_id','=',$request->prodotti_associati_uscita)->where('users_id','=',$request->socio_id_uscita)->update(['tara'=> $uptara]);
            \Session::flash('success_message_scarico','Uscita eseguita con pesata e tara.');
            return redirect('/mobile');
        }
    }
    public function ingressojson(){
        if(Auth::user()->type=='Socio'){
        $prodotto = Ingresso::where('ingresso.socio_id',Auth::id())->join('prodotti','prodotti.id','=','ingresso.prodotti_id')->join('users','users.id','=','ingresso.users_id')->where('prodotti.tipo','=','ingresso')->select('ingresso.carico','ingresso.tara','ingresso.created_at','prodotti.name','prodotti.codice','prodotti.tipo','ingresso.id','users.name as username')->orderBy('ingresso.id','desc')->get();
        return $prodotto->toJson();
        }
        else{
            $prodotto = Ingresso::where('ingresso.users_id',Auth::id())->join('prodotti','prodotti.id','=','ingresso.prodotti_id')->join('users','users.id','=','ingresso.socio_id')->where('prodotti.tipo','=','ingresso')->select('ingresso.carico','ingresso.tara','ingresso.created_at','prodotti.name','prodotti.codice','prodotti.tipo','ingresso.id','users.name as username')->orderBy('ingresso.id','desc')->get();
        return $prodotto->toJson();
        }
    }
    public function uscitajson(){
        if(Auth::user()->type=='Socio'){
            $prodotto = Uscita::where('uscita.users_id',Auth::id())->join('prodotti','prodotti.id','=','uscita.prodotti_id')->join('users','users.id','=','uscita.users_id')->where('prodotti.tipo','=','uscita')->select('uscita.scarico','uscita.tara','uscita.created_at','prodotti.name','prodotti.codice','prodotti.tipo','users.name as username')->orderBy('uscita.id','desc')->get();
            return $prodotto->toJson();
        }
        else {
        $prodotto = Uscita::where('uscita.users_id',Auth::id())->join('prodotti','prodotti.id','=','uscita.prodotti_id')->join('users','users.id','=','uscita.socio_id')->where('prodotti.tipo','=','uscita')->select('uscita.scarico','uscita.tara','uscita.created_at','prodotti.name','prodotti.codice','prodotti.tipo','users.name as username')->orderBy('uscita.id','desc')->get();
      //  $prodotto = Uscita::where('uscita.users_id',Auth::id())->join('prodotti','prodotti.id','=','uscita.prodotti_id')->join('prodotti_associati','prodotti_associati.prodotti_id','=','uscita.prodotti_id')->where('prodotti.tipo','=','uscita')->select('uscita.scarico','uscita.tara','uscita.created_at','prodotti.name','prodotti.codice','prodotti.tipo','prodotti_associati.ingresso')->orderBy('uscita.id','desc')->get();
        return $prodotto->toJson();
        }
    }
    
    public function saveselected(){
        
    }
}