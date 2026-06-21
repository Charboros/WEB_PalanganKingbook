<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'member_code', 'level', 'tier', 'xp'])]
class Member extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function xpLogs(): HasMany
    {
        return $this->hasMany(MemberXpLog::class);
    }

    /**
     * Menambahkan XP dan mencatat log, lalu memperbarui tier jika perlu.
     */
    public function addXP(int $amount, string $description, ?int $bookingId = null): void
    {
        $this->xp += $amount;
        $this->save();

        $this->xpLogs()->create([
            'booking_id' => $bookingId,
            'xp_amount' => $amount,
            'description' => $description,
        ]);

        $this->recalculateTier();
    }

    /**
     * Mengurangi XP dan mencatat log, lalu memperbarui tier jika perlu.
     */
    public function subtractXP(int $amount, string $description, ?int $bookingId = null): void
    {
        $this->xp = max(0, $this->xp - $amount);
        $this->save();

        $this->xpLogs()->create([
            'booking_id' => $bookingId,
            'xp_amount' => -$amount,
            'description' => $description,
        ]);

        $this->recalculateTier();
    }

    /**
     * Memperbarui tier dan level keanggotaan berdasarkan jumlah XP saat ini.
     */
    public function recalculateTier(): void
    {
        $oldTier = $this->tier;

        if ($this->xp >= 300) {
            $this->tier = 'gold';
            $this->level = 3;
        } elseif ($this->xp >= 100) {
            $this->tier = 'silver';
            $this->level = 2;
        } else {
            $this->tier = 'bronze';
            $this->level = 1;
        }

        if ($this->isDirty('tier') || $this->isDirty('level')) {
            $this->save();
        }
    }
}
