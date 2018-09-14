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

use App\Models\Backup;
use App\Models\ProdottiAssociati;
use App\Models\ProdottiAssociatiTerzisti;

class ProdottiAssociatiTerzistiController extends Controller
{
    public function edit($id, Request $request){
        $prodotto = ProdottiAssociatiTerzisti::find($id);
        
    }
    public function update(Request $request, $id){
        $prodotto = ProdottiAssociatiTerzisti::where('id', $id)->first();
        $prodotto->ingresso = $request->carico;
        $prodotto->save();
        return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$request->users_id.'#prodotti');
    }
    public function destroy($id, Request $request)
    {
            ProdottiAssociatiTerzisti::find($id)->delete();
            // Redirecting to index() method
            return redirect(config('laraadmin.adminRoute') . '/terzisti/'.$request->users_id.'#prodotti');
    }
}