<?php

namespace Stevebauman\Authorization\Traits;

trait HasRolesTrait
{
    /**
     * A model may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $model = config('authorization.role');

        return $this->belongsToMany($model);
    }
}
