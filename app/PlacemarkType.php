<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class PlacemarkType extends Model
{
    
	use Sluggable;

	protected
		$table = 'placemarks_type',
		$fillable = [
			'id',
			'name',
            'slug',
			'description',
		];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

}
