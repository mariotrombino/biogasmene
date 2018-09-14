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
use Dwij\Laraadmin\Helpers\LAHelper;
use Artisan;

use App\Models\Ingresso;

class IngressoController extends Controller
{
    public function edit($id, Request $request){
        $prodotto = Ingresso::find($id);
        
    }
    public function update(Request $request, $id){
        $prodotto = Ingresso::where('id', $id)->first();
        $prodotto->carico = $request->carico;
        $prodotto->tara = $request->tara;
        $prodotto->save();
        return redirect(config('laraadmin.adminRoute') . '/employees/'.$request->users_id.'#ingresso');
    }
    public function destroy($id, Request $request)
    {
            Ingresso::find($id)->delete();
            // Redirecting to index() method
            return redirect(config('laraadmin.adminRoute') . '/employees/'.$request->users_id.'#ingresso');
    }
}