<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PlacemarkType;
use App\User;

class Placemark extends Model
{
    
	protected
		$table = 'placemarks',
		$fillable = ['id', 'name', 'description', 'coords', 'type_id', 'user_id'],
		$casts = [
			'coords' => 'array',
		];

	public function type() {
		return $this->hasOne(PlacemarkType::class, 'id', 'type_id');
	}

	public function user() {
		return $this->hasOne(User::class, 'id', 'user_id');
	}

}
