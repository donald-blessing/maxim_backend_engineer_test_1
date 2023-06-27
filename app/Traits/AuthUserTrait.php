<?php

namespace App\Traits;

use App\Models\User;

/**
 * Get auth users details
 */
trait AuthUserTrait
{

    /**
     * get auth users details
     *
     * @return User
     */
    public function getAuthUser(): User
    {
        return (new User)->find(auth('sanctum')->user()->id);
    }

}
