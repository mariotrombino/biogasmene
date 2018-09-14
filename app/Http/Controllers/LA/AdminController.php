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
use App\Role;
use Mail;
use Log;
use PhpParser\Node\Stmt\If_;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
	public $show_action = true;

	/**
	 * Display the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Users", "view")) {
			
			$employee = User::find($id);
			if(isset($employee->id)) {
				$module = Module::get('Users');
				$module->row = $employee;
				
				// Get User Table Information
				$user = User::where('id', '=', $id)->where('context_id', '=', '0')->firstOrFail();
				return view('la.admin.show', [
					'user' => $user,
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
	 * Remove the specified employee from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Users", "delete")) {
		    User::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	/**
     * Change Employee Password
     *
     * @return
     */
	public function admin_change_password($id, Request $request) {
		
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
        ]);
		
		if ($validator->fails()) {
			return \Redirect::to(config('laraadmin.adminRoute') . '/users/'.$id)->withErrors($validator);
		}
		
		$user = User::find($id)->where('type', 'Admin')->orWhere('type','SuperAdmin')->first();
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
		
		return redirect(config('laraadmin.adminRoute') . '/admin/'.$id.'#tab-account-settings');
	}
}