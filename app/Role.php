<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Auth;

class Role extends Model
{

    const ROLE_ADMIN = 1;
	const ROLE_MODER = 2;

    protected
    	$table = 'roles',
    	$fillable = ['name', 'role'];

    public function users() {
    	return $this->belongsTo(User::class, 'id', 'role_id');
    }

    public function scopeIsAdmin($q/*, $user*/) {
    	$user = Auth::user();
    	return $q->find($user->role_id)->count() === 1;
    } 
}
