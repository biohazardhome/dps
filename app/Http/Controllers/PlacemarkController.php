<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Placemark;
use Auth;

// use Symfony\Component\HttpKernel\Exception\HttpException;

class PlacemarkController extends Controller
{
    
	public function create() {
		return view('placemark.create');
	}

	public function store(Request $request) { // создавать могут только из определенной группы пользователей (придумать название) 
		// dd($user->can('create', Placemark::class));

		try {
			$this->authorize('moder'/*, Placemark::class*/);
		} catch (\Throwable $e) {
			return [
				'success' => false,
				'error' => [$e->getMessage()]
			];
		}

		$this->validate($request, [
			// 'name' => 'required',
			// 'name' => '',
			// 'description' => '',
			'coords' => 'required|json',
			'type_id' => 'exists:placemarks_type,id',
			'user_id' => 'exists:users,id',
		]);

		return Placemark::create(array_merge(
			$request->except('_token', 'coords'),
			[
				'coords' => json_decode($request->get('coords')),
				'user_id' => Auth::id()
			]
		));
	}

	public function edit(Request $request, $id) { // проверка прав, кто создал тот и может редактировать или модератор и т.д
		$placemark = Placemark::find($id);
		
		return view('placemark.edit', compact('placemark'));
	}

	public function update(Request $request, $id) { // проверка прав, кто создал тот и может редактировать или модератор и т.д
		try {
			$this->authorize('moder', Placemark::find($id));
		} catch (\Throwable $e) {
			return [
				'success' => false,
				'error' => [$e->getMessage()]
			];
		}

		$userId = Auth::id();

		$this->validate($request, [
			// 'name' => 'required',
			// 'name' => '',
			// 'description' => '',
			'coords' => 'required|json',
			/*'type_id' => 'exists:placemarks_type,id,'. $request->get('type_id'),
			'user_id' => 'exists:users,id,'. $userId,*/
			'type_id' => 'exists:placemarks_type,id',
			'user_id' => 'exists:users,id',
		]);

		
		$placemark = Placemark::find($id);

		$placemark->update(array_merge(
			$request->except('_token', 'coords'),
			[
				'coords' => json_decode($request->get('coords')),
				'user_id' => $userId
			]
		));

		return $placemark;

	}

	public function delete($id) { // удалаять могут создатель и модераторы
		$this->authorize('moder'/*, Placemark::class*/);

	}

	public function updateCoords(Request $request, $id) {
		try {
			$this->authorize('moder'/*, Placemark::class*/);
		} catch (\Throwable $e) {
			return [
				'success' => false,
				'error' => [$e->getMessage()]
			];
		}

		$this->validate($request, [
			'coords' => 'required|json',
		]);

		$placemark = Placemark::find($id);
		$placemark->update([
			'coords' => json_decode($request->get('coords')),
		]);

		return $placemark;

	}

}
