<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Placemark;
use App\PlacemarkType;

class PlacemarkApiController extends Controller
{
    
	public function all() {
		return Placemark::with('type')->get();
	}

	public function find($id) {
		return Placemark::with('type')->find($id);
	}

	public function type($type) {
		return Placemark::with('type')->whereType($type);
	}

}
