<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByNameScope implements Scope
{
    /** @param  Builder<Model>  $builder */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderBy('name');
    }
}
