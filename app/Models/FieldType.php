<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'icon'])]
class FieldType extends Model
{
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}
