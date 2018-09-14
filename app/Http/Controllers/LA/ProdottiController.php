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

use App\Models\Prodotti;
use Illuminate\Validation\Rule;

class ProdottiController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Prodotti.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Prodotti');
        
        if(Module::hasAccess($module->id)) {
            return View('la.prodotti.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Prodotti'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new prodotti.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created prodotti in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Prodotti", "create")) {
            
            $rules = Module::validateRules("Prodotti", $request);
            
            $validator = Validator::make($request->all(),['codice'=>'required|unique:prodotti'], $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Prodotti", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.prodotti.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified prodotti.
     *
     * @param int $id prodotti ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Prodotti", "view")) {
            
            $prodotti = Prodotti::find($id);
            if(isset($prodotti->id)) {
                $module = Module::get('Prodotti');
                $module->row = $prodotti;
                
                return view('la.prodotti.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('prodotti', $prodotti);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("prodotti"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified prodotti.
     *
     * @param int $id prodotti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Prodotti", "edit")) {
            $prodotti = Prodotti::find($id);
            if(isset($prodotti->id)) {
                $module = Module::get('Prodotti');
                
                $module->row = $prodotti;
                
                return view('la.prodotti.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('prodotti', $prodotti);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("prodotti"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified prodotti in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id prodotti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Prodotti", "edit")) {
            
            $rules = Module::validateRules("Prodotti", $request, true);
            $product = Prodotti::where('id',$id)->first();
            $validator = Validator::make($request->all(),['codice'=>['required',Rule::unique('prodotti')->ignore($product->id)]], $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Prodotti", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.prodotti.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified prodotti from storage.
     *
     * @param int $id prodotti ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Prodotti", "delete")) {
            Prodotti::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.prodotti.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = Module::get('Prodotti');
        $listing_cols = Module::getListingColumns('Prodotti');
        
        $values = DB::table('prodotti')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Prodotti');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/prodotti/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Prodotti", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/prodotti/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Prodotti", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.prodotti.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}