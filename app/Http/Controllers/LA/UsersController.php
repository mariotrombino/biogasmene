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

use App\User;
use App\Role;
use Mail;
use Log;
use Illuminate\Validation\Rule;
class UsersController extends Controller
{
	public $show_action = false;
	
	/**
	 * Display a listing of the Users.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Users');
		
		if(Module::hasAccess($module->id)) {
			return View('la.users.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => Module::getListingColumns('Users'),
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Users", "view")) {
			$user = User::findOrFail($id);
			if(isset($user->id)) {
				if($user['type'] == "Socio") {
					return redirect(config('laraadmin.adminRoute') . '/employees/'.$user->context_id);
				} else if($user['type'] == "Terzisti") {
					return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$user->terzisti_id);
				}
				else if($user['type'] == "Admin") {
				    return redirect(config('laraadmin.adminRoute') . '/admin/'.$user->id);
				}
				else if($user['type'] == "SuperAdmin") {
				    return redirect(config('laraadmin.adminRoute') . '/admin/'.$user->id);
				}
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("user"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	/**
	 * Store a newly created employee in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	    if(Module::hasAccess("Users", "create")) {
	        
	        $rules = Module::validateRules("Users", $request);
	        
	        $validator = Validator::make($request->all(),['email'=>'required|unique:users'],$rules);
	        
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
	       // $employee_id = Module::insert("Employees", $request);
	        // Create User
	        $user = User::create([
	            'name' => $request->name,
	            'email' => $request->email,
	            'password' => bcrypt($password),
	            //'context_id' => $employee_id,
	            'type' => "Admin",
	        ]);
// 	        Codice::create([
// 	            'users_id' => $user->id,
// 	            'codice' => $request->codice,
// 	        ]);
// 	        Carico::create([
// 	            'users_id'=> $user->id,
// 	            'quota' => $request->carico
// 	        ]);
// 	        Scarico::create([
// 	            'users_id'=> $user->id,
// 	            'quota' => $request->scarico
// 	        ]);
// 	        Tara::create([
// 	            'users_id' => $user->id,
// 	            'tara' => $request->tara,
// 	            'old_tara' => $request->tara
// 	        ]);
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
	        
	        return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
	        
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
	    if(Module::hasAccess("Users", "edit")) {
	        
	        $employee = User::find($id);
	        if(isset($employee->id)) {
	            $module = Module::get('Users');
	            
	            $module->row = $employee;
	            
	            // Get User Table Information
	            $user = User::where('id', '=', $id)->where('context_id','0')->firstOrFail();
	            
	            return view('la.users.edit', [
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
	    if(Module::hasAccess("Users", "edit")) {
	        
	        $rules = Module::validateRules("Users", $request, true);
	        $employee_id = Module::updateRow("Users", $request, $id);
	        $user = User::where('id', $id)->first();
	        
	        $validator = Validator::make($request->all(),['email'=>['required',Rule::unique('users')->ignore($user->id)]],$rules);
	        
	        if($validator->fails()) {
	            return redirect()->back()->withErrors($validator)->withInput();
	        }
	        
	        // Update User
	        $user->name = $request->name;
	        $user->email = $request->email;
	        $user->save();
	        
	        // update user role
	        $user->detachRoles();
	        $role = Role::find($request->dept);
	        $user->attachRole($role);
	        
	        return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
	        
	    } else {
	        return redirect(config('laraadmin.adminRoute')."/");
	    }
	}
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax(Request $request)
	{
		$module = Module::get('Users');
		$listing_cols = Module::getListingColumns('Users');

		$values = DB::table('users')->select($listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Users');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($listing_cols); $j++) { 
				$col = $listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $module->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/users/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
			}
		}
		$out->setData($data);
		return $out;
	}
}