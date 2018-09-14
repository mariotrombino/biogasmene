<?php
/**
 * Model generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terzisti extends Model
{
    
    protected $table = 'terzisti';
    
    protected $hidden = [
    
    ];
    
    protected $guarded = [];
    
    protected $dates = ['deleted_at'];
    /**
     * @return mixed
     */
    public function associati()
    {
        return $this->hasMany('App\Models\Associati');
    }
    public function prodotti()
    {
        return $this->hasMany('App\Models\ProdottiAssociati','users_id');
    }
}