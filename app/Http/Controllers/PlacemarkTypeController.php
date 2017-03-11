<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PlacemarkType;

class PlacemarkTypeController extends Controller
{
    
	public function index() {
		return PlacemarkType::all();
	}

	public function all() {
		return PlacemarkType::all();
	}

	public function find($id) {
		return PlacemarkType::find($id);
	}

	public function create() {
		return view('placemark.type.create');
	}

	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required|unique:placemark_type,id',
			// 'description' => '',
		]);

		PlacemarkType::create($request->all());
	}

	public function edit($id) {
		$type = PlacemarkType::find($id);
		return view('placemark.type.edit', compact('type'));
	}

	public function update(Request $request, $id) {

	}

	public function delete($id) {
		return PlacemarkType::delete($id);
	}

}
