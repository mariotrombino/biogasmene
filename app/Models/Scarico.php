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

class Scarico extends Model
{
    
    protected $table = 'scarico';
    
    protected $hidden = [
    
    ];
    
    protected $guarded = [];
    
    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}