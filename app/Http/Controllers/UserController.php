<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class UserController extends Controller
{
    
	public function all() {
		
	}

	public function find($id) {

	}

	public function auth() {
		$id = Auth::id();
		$user = User::with('role')->find($id);
		
		return $user;
	}

	public function role() {

	}

}
