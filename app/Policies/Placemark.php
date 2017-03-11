<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Placemark as PlacemarkModel;

class Placemark
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user) {
        return $user->id == $placemark->user_id;
    }

    public function update(User $user, PlacemarkModel $placemark) {
        return $user->id == $placemark->user_id;
    }

    public function delete() {

    }


}
