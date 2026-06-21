<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['field_type_id', 'name', 'price_offpeak', 'price_peak', 'description', 'photo', 'is_active'])]
class Field extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price_offpeak' => 'decimal:2',
            'price_peak' => 'decimal:2',
        ];
    }

    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(FieldType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingSlots(): HasMany
    {
        return $this->hasMany(BookingSlot::class);
    }
}
