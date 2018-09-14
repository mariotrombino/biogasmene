<?php
/**
 * Controller generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Terzisti;
use App\Role;
use App\User;
use App\Models\Associati;
use Dwij\Laraadmin\Helpers\LAHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Dwij\Laraadmin\Models\LAConfigs;
use App\Models\Carico;
use App\Models\Scarico;
use App\Models\Codice;
use App\Models\Tara;
use Illuminate\Validation\Rule;
use App\Models\ProdottiAssociati;
use App\Models\ProdottiAssociatiTerzisti;
use App\Models\Prodotti;

class TerzistiController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Terzisti.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Terzisti');
        
        if(Module::hasAccess($module->id)) {
            return View('la.terzisti.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Terzisti'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new terzisti.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created terzisti in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Terzisti", "create")) {
            
            $rules = Module::validateRules("Terzisti", $request);
            
            $validator = Validator::make($request->all(),['email'=>'required|unique:users'], $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            if(!empty($request->input('password'))){
                $password = $request->input('password');
            }
            else {
                // generate password
                $password = LAHelper::gen_password();
            }
            
            $insert_id = Module::insert("Terzisti", $request);
            if(!empty($request->input('users_id'))){
            foreach($request->input('users_id') as $users_id){
                Associati::create([
                    'users_id' => $users_id,
                    'terzisti_id' => $insert_id
                ]);
            }
            }
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password),
                'terzisti_id' => $insert_id,
                'type' => "Terzisti",
            ]);
            if(!empty($request->input('prodotti_id'))){
                foreach($request->input('prodotti_id') as $prodotti_id){
                    ProdottiAssociati::create([
                        'users_id' => $user->id,
                        'prodotti_id' => $prodotti_id
                    ]);
                }
            }
            // update user role
            $user->detachRoles();
            $role = Role::find($request->dept);
            $user->attachRole($role);
            if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
                // Send mail to User his Password
                Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
                    $m->from('hello@laraadmin.com', 'Cavallermaggiore');
                    $m->to($user->email, $user->name)->subject('Le tue credenziali di accesso!');
                });
            } else {
                Log::info("User created: username: ".$user->email." Password: ".$password);
            }
            return redirect()->route(config('laraadmin.adminRoute') . '.terzisti.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified terzisti.
     *
     * @param int $id terzisti ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Terzisti", "view")) {
            
            $terzisti = Terzisti::find($id);
            if(isset($terzisti->id)) {
                $module = Module::get('Terzisti');
                $module->row = $terzisti;
                $user = User::where('terzisti_id', '=', $id)->where('terzisti_id', '!=', '0')->firstOrFail();
                $associato = Associati::with('user')->where('terzisti_id','=', $id)->get();
                $codice = User::where('terzisti_id', '=', $id)->firstOrFail()->code;
                $prodotti = User::where('terzisti_id', '=', $id)->join('prodotti_associati','users.id','=','prodotti_associati.users_id')->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->select('prodotti.*')->get();
                return view('la.terzisti.show', [
                    'user' => $user,
                    'associato' => $associato,
                    'prodotti' => $prodotti,
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('terzisti', $terzisti);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("terzisti"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified terzisti.
     *
     * @param int $id terzisti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Terzisti", "edit")) {
            $terzisti = Terzisti::find($id);
            if(isset($terzisti->id)) {
                $module = Module::get('Terzisti');
                $module->row = $terzisti;
                // Get User Table Information
                $user = User::where('terzisti_id', '=', $id)->firstOrFail();

                return view('la.terzisti.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'user' => $user,
                ])->with('terzisti', $terzisti);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("terzisti"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified terzisti in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id terzisti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Terzisti", "edit")) {
            
            $rules = Module::validateRules("Terzisti", $request, true);
            $insert_id = Module::updateRow("Terzisti", $request, $id);
            $user = User::where('terzisti_id', $insert_id)->first();
            $codice = Codice::where('users_id',$user->id)->first();

            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Update User
            $user->name = $request->name;
            $user->save();
            $prodotto = ProdottiAssociati::where('users_id', $user->id);
            $prodotto->delete();
            if(!empty($request->input('prodotti_id'))){
                foreach($request->input('prodotti_id') as $prodotti_id){
                    ProdottiAssociati::create([
                        'users_id' => $user->id,
                        'prodotti_id' => $prodotti_id
                    ]);
                }
            }
            else if(empty($request->input('users_id')))
            {
                Associati::where('terzisti_id',$id)->delete();
            }
            $update_associato = Associati::where('terzisti_id', $id);
            $update_associato->delete();
            if(!empty($request->input('users_id'))){
                foreach($request->input('users_id') as $users_id){
                    Associati::create([
                        'users_id' => $users_id,
                        'terzisti_id' => $id
                    ]);
                }
            }
            else if(empty($request->input('users_id')))
            {
                Associati::where('terzisti_id',$id)->delete();
            }
            // update user role
            $user->detachRoles();
            $role = Role::find($request->dept);
            $user->attachRole($role);
            return redirect()->route(config('laraadmin.adminRoute') . '.terzisti.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified terzisti from storage.
     *
     * @param int $id terzisti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Terzisti", "delete")) {
            
            $user = User::where('terzisti_id', $id)->first();
            $associati = Associati::where('terzisti_id', $id);
            $associati->delete();
            Codice::where('users_id',$user->id)->delete();
            ProdottiAssociati::where('users_id',$user->id)->delete();
            Terzisti::find($id)->delete();
            User::find($user->id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.terzisti.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Datatable Ajax fetch product
     *
     * @return json file
     */
    public function prodajax($id){
        $prodotti = Prodotti::join('prodotti_associati_terzisti','prodotti_associati_terzisti.prodotti_id','=','prodotti.id')->join('users','users.id','=','prodotti_associati_terzisti.users_id')->join('users as us','us.id','=','prodotti_associati_terzisti.socio_id')->select('prodotti.codice','prodotti.name','prodotti.tipo','prodotti_associati_terzisti.id','prodotti_associati_terzisti.users_id','us.name as nameuser')->where('users.id','=',$id);//->get();
        $out = Datatables::of($prodotti)->make();
        //$prodotto = $prodotti->toJson();
        $data = $out->getData();
        $out->setData($data);
        return $out;
    }
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = Module::get('Terzisti');
        $listing_cols = Module::getListingColumns('Terzisti');
        
        $values = DB::table('terzisti')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Terzisti');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/terzisti/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Terzisti", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/terzisti/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    /**
     * Change Terzisti Carico/Scarico
     *
     * @return
     */
    public function change_carico($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'prodotti_associati' => 'required',
            'soci' => 'required'
        ]);
        
        if($validator->fails())
        {
            return \Redirect::to(config('laraadmin.adminRoute') . '/terzisti/' . $id)->withErrors($validator);
        }
        $dipendente = Terzisti::find($id);
        $user = User::where("terzisti_id", $dipendente->id)->where('type', 'Terzisti')->first();
        ProdottiAssociatiTerzisti::create([
            'socio_id' => $request->soci,
            'users_id' => $user->id,
            'prodotti_id' => $request->prodotti_associati,
        ]);
        
        \Session::flash('success_message_carico','Prodotto in ingresso assegnato correttamente.');
        
        return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$id.'#tab-quota-carico');
    }
    public function change_scarico($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'prodotti_associati_uscita' => 'required',
            'soci_uscita' => 'required'
        ]);
        
        if($validator->fails())
        {
            return \Redirect::to(config('laraadmin.adminRoute') . '/terzisti/' . $id)->withErrors($validator);
        }
        $dipendente = Terzisti::find($id);
        $user = User::where("terzisti_id", $dipendente->id)->where('type', 'Terzisti')->first();
        ProdottiAssociatiTerzisti::create([
            'socio_id' => $request->soci_uscita,
            'users_id' => $user->id,
            'prodotti_id' => $request->prodotti_associati_uscita,
        ]);
        
        \Session::flash('success_message_scarico','Prodotto in uscita assegnato correttamente.');
        
        return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$id.'#tab-quota-scarico');
    }
    public function change_tara($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'tara' => 'required|min:1'
        ]);
        
        if($validator->fails())
        {
            return \Redirect::to(config('laraadmin.adminRoute') . '/terzisti/' . $id)->withErrors($validator);
        }
        $dipendente = Terzisti::find($id);
        $user = User::where("terzisti_id", $dipendente->id)->where('type', 'Terzisti')->first();
        $tara = Tara::where('users_id', $user->id)->first();
        $tara->tara = $request->tara;
        $tara->save();
        
        \Session::flash('success_message_tara','La tara è stata modificata');
        
        return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$id.'#tab-tara');
    }
    /**
     * Change Terzisti Password
     *
     * @return
     */
    public function change_password($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ]);
        
        if ($validator->fails()) {
            return \Redirect::to(config('laraadmin.adminRoute') . '/terzisti/'.$id)->withErrors($validator);
        }
        
        $employee = Terzisti::find($id);
        $user = User::where("terzisti_id", $employee->id)->where('type', 'Terzisti')->first();
        $user->password = bcrypt($request->password);
        $user->save();
        
        \Session::flash('success_message', 'La password è stata cambiata');
        
        // Send mail to User his new Password
        if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
            // Send mail to User his new Password
            Mail::send('emails.send_login_cred_change', ['user' => $user, 'password' => $request->password], function ($m) use ($user) {
                $m->from(LAConfigs::getByKey('default_email'), LAConfigs::getByKey('sitename'));
                $m->to($user->email, $user->name)->subject('Credenziali accesso modificate');
            });
        } else {
            Log::info("Dipendente change_password: username: ".$user->email." Password: ".$request->password);
        }
        
        return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$id.'#tab-account-settings');
    }
}