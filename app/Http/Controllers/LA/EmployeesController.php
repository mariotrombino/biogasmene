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
use Dwij\Laraadmin\Models\LAConfigs;
use Dwij\Laraadmin\Helpers\LAHelper;

use App\User;
use App\Models\Employee;
use App\Role;
use Mail;
use Log;
use App\Models\Associati;
use App\Models\Codice;
use App\Models\Scarico;
use App\Models\Carico;
use PhpParser\Node\Stmt\If_;
use Illuminate\Validation\Rule;
use App\Models\Tara;
use App\Models\ProdottiAssociati;
use App\Models\Prodotti;
use App\Models\Ingresso;
use App\Models\Uscita;

class EmployeesController extends Controller
{
	public $show_action = true;
	
	/**
	 * Display a listing of the Employees.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Employees');
		
		if(Module::hasAccess($module->id)) {
			return View('la.employees.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => Module::getListingColumns('Employees'),
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new employee.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created employee in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Employees", "create")) {
		
			$rules = Module::validateRules("Employees", $request);
			
			$validator = Validator::make($request->all(),['codice'=>'required|unique:codice','email'=>'required|unique:users'], $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			if(!empty($request->input('password'))){
			    $password = $request->input('password');
			}
			else {
			    // generate password
			    $password = LAHelper::gen_password();
			}

			
			// Create Employee
			$employee_id = Module::insert("Employees", $request);
			// Create User
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($password),
				'context_id' => $employee_id,
				'type' => "Socio",
			]);
			    Codice::create([
			        'users_id' => $user->id,
			        'codice' => $request->codice,
			    ]);
			// update user role
			$user->detachRoles();
			$role = Role::find($request->dept);
			$user->attachRole($role);
			
			if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
				// Send mail to User his Password
				Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
					$m->from('hello@laraadmin.com', 'Biogasbenè');
					$m->to($user->email, $user->name)->subject('Le tue credenziali di accesso!');
				});
			} else {
				Log::info("User created: username: ".$user->email." Password: ".$password);
			}
			
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Employees", "view")) {
			
			$employee = Employee::find($id);
			if(isset($employee->id)) {
				$module = Module::get('Employees');
				$module->row = $employee;
				
				// Get User Table Information
				$user = User::where('context_id', '=', $id)->where('context_id', '!=', '0')->firstOrFail();
				$carico = User::where('context_id', '=', $id)->where('context_id', '!=', '0')->firstOrFail()->carico;
				$codice = User::where('context_id', '=', $id)->where('context_id', '!=', '0')->firstOrFail()->code;
				$prodotti = User::where('context_id', '=', $id)->join('prodotti_associati','users.id','=','prodotti_associati.users_id')->join('prodotti','prodotti.id','=','prodotti_associati.prodotti_id')->select('prodotti.*')->get();
				return view('la.employees.show', [
					'user' => $user,
				    'codice' => $codice,
				    'carico' => $carico,
				    'prodotti' => $prodotti,
					'module' => $module,
					'view_col' => $module->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Employees", "edit")) {
			
			$employee = Employee::find($id);
			if(isset($employee->id)) {
				$module = Module::get('Employees');
				
				$module->row = $employee;
				
				// Get User Table Information
				$user = User::where('context_id', '=', $id)->firstOrFail();
				
				return view('la.employees.edit', [
					'module' => $module,
					'view_col' => $module->view_col,
					'user' => $user,
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified employee in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Employees", "edit")) {
			
			$rules = Module::validateRules("Employees", $request, true);
			$employee_id = Module::updateRow("Employees", $request, $id);
			$user = User::where('context_id', $employee_id)->first();
			$codice = Codice::where('users_id',$user->id)->first();
			
			$validator = Validator::make($request->all(),['codice'=>['required',Rule::unique('codice')->ignore($codice->id)],'email'=>['required',Rule::unique('users')->ignore($user->id)]], $rules);
			
			if($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
        	
			// Update User
			$user->name = $request->name;
			$user->save();
			$codice->codice = $request->codice;
			$codice->save();
			
			// update user role
			$user->detachRoles();
			$role = Role::find($request->dept);
			$user->attachRole($role);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified employee from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Employees", "delete")) {
		    Employee::find($id)->delete();
		    $user = User::where('context_id', $id)->first();
		    $associati = Associati::where('users_id', $id);
		    $associati->delete();
		    Carico::where('users_id',$user->id)->delete();
		    Scarico::where('users_id',$user->id)->delete();
		    Codice::where('users_id',$user->id)->delete();
		    Tara::where('users_id',$user->id)->delete();
		    User::find($user->id)->delete();
			ProdottiAssociati::where('users_id',$user->id)->delete();
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Datatable Ajax fetch product
	 *
	 * @return json file
	 */
	public function prodajax($id){
	    $prodotti = Prodotti::join('prodotti_associati','prodotti_associati.prodotti_id','=','prodotti.id')->join('users','users.id','=','prodotti_associati.users_id')->select('prodotti.codice','prodotti.name','prodotti.tipo','prodotti_associati.ingresso','prodotti_associati.id','prodotti_associati.users_id','prodotti_associati.evaso','prodotti_associati.tara')->where('users.id','=',$id);//->get();
	    $out = Datatables::of($prodotti)->make();
	    //$prodotto = $prodotti->toJson();
	    $data = $out->getData();
	    $out->setData($data);
	    return $out;
	}
	public function prodottiingressoajax($id){
	    $prodotti = Ingresso::join('prodotti','prodotti.id','=','ingresso.prodotti_id')->join('users','users.id','=','ingresso.users_id')->select('prodotti.codice','prodotti.name','ingresso.carico','ingresso.tara','ingresso.id','users.name as username')->where('ingresso.socio_id','=',$id)->orderBy('ingresso.id','desc');
	    $out = Datatables::of($prodotti)->make();
	    $data = $out->getData();
	    $out->setData($data);
	    return $out;
	}
	public function prodottiuscitaajax($id){
	    $prodotti = Uscita::join('prodotti','prodotti.id','=','uscita.prodotti_id')->join('users','users.id','=','uscita.users_id')->select('prodotti.codice','prodotti.name','uscita.scarico','uscita.tara','uscita.id','users.name as username')->where('uscita.socio_id','=',$id)->orderBy('uscita.id','desc');
	    $out = Datatables::of($prodotti)->make();
	    $data = $out->getData();
	    $out->setData($data);
	    return $out;
	}
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax(Request $request)
	{
		$module = Module::get('Employees');
		$listing_cols = Module::getListingColumns('Employees');
		
		$values = DB::table('employees')->select($listing_cols)->whereNull('deleted_at')->where('dept','!=','1');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Employees');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($listing_cols); $j++) { 
				$col = $listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $module->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Employees", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
	/**
	 * Change Soci Carico/Scarico
	 *
	 * @return
	 */
	public function change_carico($id, Request $request) {
	    
	    $validator = Validator::make($request->all(), [
	        'carico' => 'required',
	        'prodotti_associati' => 'required'
	    ]);
	    
	    if($validator->fails())
	    {
	        return \Redirect::to(config('laraadmin.adminRoute') . '/employees/' . $id)->withErrors($validator);
	    }
	    $dipendente = Employee::find($id);
	    $user = User::where("context_id", $dipendente->id)->where('type', 'Socio')->first();
	    			        ProdottiAssociati::create([
	    			            'users_id' => $user->id,
	    			            'prodotti_id' => $request->prodotti_associati,
	    			            'ingresso' => $request->carico,
	    			        ]);
	    
	    \Session::flash('success_message_carico','Prodotto in ingresso assegnato correttamente.');
	    
	    return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-quota-carico');
	}
	public function change_scarico($id, Request $request) {
	    
	    $validator = Validator::make($request->all(), [
	        'carico' => 'required',
	        'prodotti_associati' => 'required'
	    ]);
	    
	    if($validator->fails())
	    {
	        return \Redirect::to(config('laraadmin.adminRoute') . '/employees/' . $id)->withErrors($validator);
	    }
	    $dipendente = Employee::find($id);
	    $user = User::where("context_id", $dipendente->id)->where('type', 'Socio')->first();
	    ProdottiAssociati::create([
	        'users_id' => $user->id,
	        'prodotti_id' => $request->prodotti_associati,
	        'ingresso' => $request->carico
	    ]);
	    
	    \Session::flash('success_message_scarico','Prodotto in uscita assegnato correttamente.');
	    
	    return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-quota-scarico');
	}
	public function change_tara($id, Request $request) {
	    
	    $validator = Validator::make($request->all(), [
	        'tara' => 'required|min:1'
	    ]);
	    
	    if($validator->fails())
	    {
	        return \Redirect::to(config('laraadmin.adminRoute') . '/employees/' . $id)->withErrors($validator);
	    }
	    $dipendente = Employee::find($id);
	    $user = User::where("context_id", $dipendente->id)->where('type', 'Socio')->first();
	    $tara = Tara::where('users_id', $user->id)->first();
	    $tara->tara = $request->tara;
	    $tara->save();
	    
	    \Session::flash('success_message_tara','La tara è stata modificata');
	    
	    return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-tara');
	}
	/**
     * Change Employee Password
     *
     * @return
     */
	public function change_password($id, Request $request) {
		
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
        ]);
		
		if ($validator->fails()) {
			return \Redirect::to(config('laraadmin.adminRoute') . '/employees/'.$id)->withErrors($validator);
		}
		
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Socio')->first();
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
			Log::info("Socio change_password: username: ".$user->email." Password: ".$request->password);
		}
		
		return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-account-settings');
	}
}