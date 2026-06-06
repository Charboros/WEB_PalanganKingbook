<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\FieldType;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    private function createField()
    {
        $fieldType = FieldType::create([
            'name' => 'Futsal',
            'description' => 'Futsal field type',
        ]);

        return Field::create([
            'field_type_id' => $fieldType->id,
            'name' => 'Lapangan A',
            'price_offpeak' => 100000.00,
            'price_peak' => 120000.00,
            'is_active' => true,
        ]);
    }

    public function test_user_can_activate_membership(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->post(route('user.membership.activate'));

        $response->assertRedirect(route('user.membership'));
        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'tier' => 'bronze',
            'level' => 1,
            'xp' => 0,
        ]);

        $member = $user->fresh()->member;
        $this->assertNotNull($member);
        $this->assertDatabaseHas('member_xp_logs', [
            'member_id' => $member->id,
            'xp_amount' => 0,
        ]);
    }

    public function test_membership_tiering_logic(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => 'MB-TEST',
            'level' => 1,
            'tier' => 'bronze',
            'xp' => 0,
        ]);

        // Bronze threshold: 0-99 XP
        $member->addXP(50, 'Test XP 50');
        $member->refresh();
        $this->assertEquals('bronze', $member->tier);
        $this->assertEquals(1, $member->level);

        // Silver threshold: 100-299 XP
        $member->addXP(50, 'Test XP 50 to Silver');
        $member->refresh();
        $this->assertEquals('silver', $member->tier);
        $this->assertEquals(2, $member->level);

        // Gold threshold: 300+ XP
        $member->addXP(200, 'Test XP 200 to Gold');
        $member->refresh();
        $this->assertEquals('gold', $member->tier);
        $this->assertEquals(3, $member->level);

        // Subtracting XP back to Silver
        $member->subtractXP(150, 'Subtract XP to Silver');
        $member->refresh();
        $this->assertEquals('silver', $member->tier);
        $this->assertEquals(2, $member->level);
    }

    public function test_booking_applies_membership_discount(): void
    {
        $field = $this->createField();

        // 1. Bronze Member (0% discount)
        $userBronze = User::factory()->create(['role' => 'user']);
        Member::create([
            'user_id' => $userBronze->id,
            'member_code' => 'MB-BRONZE',
            'level' => 1,
            'tier' => 'bronze',
            'xp' => 0,
        ]);

        $responseBronze = $this->actingAs($userBronze)
            ->post(route('user.bookings.store'), [
                'field_id' => $field->id,
                'booking_date' => now()->next('Monday')->format('Y-m-d'), // weekday
                'slots' => [9, 10], // 2 hours off-peak: 200,000
            ]);

        $bookingBronze = Booking::where('user_id', $userBronze->id)->first();
        $this->assertNotNull($bookingBronze);
        $this->assertEquals(200000.00, $bookingBronze->original_price);
        $this->assertEquals(0.00, $bookingBronze->discount_amount);
        $this->assertEquals(200000.00, $bookingBronze->total_price);

        // 2. Silver Member (10% discount)
        $userSilver = User::factory()->create(['role' => 'user']);
        Member::create([
            'user_id' => $userSilver->id,
            'member_code' => 'MB-SILVER',
            'level' => 2,
            'tier' => 'silver',
            'xp' => 120,
        ]);

        $responseSilver = $this->actingAs($userSilver)
            ->post(route('user.bookings.store'), [
                'field_id' => $field->id,
                'booking_date' => now()->next('Monday')->format('Y-m-d'),
                'slots' => [11, 12], // 2 hours off-peak: 200,000
            ]);

        $bookingSilver = Booking::where('user_id', $userSilver->id)->first();
        $this->assertNotNull($bookingSilver);
        $this->assertEquals(200000.00, $bookingSilver->original_price);
        $this->assertEquals(200000.00 * 0.10, $bookingSilver->discount_amount);
        $this->assertEquals(200000.00 * 0.90, $bookingSilver->total_price);

        // 3. Gold Member (20% discount)
        $userGold = User::factory()->create(['role' => 'user']);
        Member::create([
            'user_id' => $userGold->id,
            'member_code' => 'MB-GOLD',
            'level' => 3,
            'tier' => 'gold',
            'xp' => 350,
        ]);

        $responseGold = $this->actingAs($userGold)
            ->post(route('user.bookings.store'), [
                'field_id' => $field->id,
                'booking_date' => now()->next('Monday')->format('Y-m-d'),
                'slots' => [13, 14], // 2 hours off-peak: 200,000
            ]);

        $bookingGold = Booking::where('user_id', $userGold->id)->first();
        $this->assertNotNull($bookingGold);
        $this->assertEquals(200000.00, $bookingGold->original_price);
        $this->assertEquals(200000.00 * 0.20, $bookingGold->discount_amount);
        $this->assertEquals(200000.00 * 0.80, $bookingGold->total_price);
    }

    public function test_booking_status_update_awards_and_revokes_xp(): void
    {
        $field = $this->createField();
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => 'MB-AWARDS',
            'level' => 1,
            'tier' => 'bronze',
            'xp' => 0,
        ]);

        $this->actingAs($user)->post(route('user.bookings.store'), [
            'field_id' => $field->id,
            'booking_date' => now()->next('Monday')->format('Y-m-d'),
            'slots' => [9, 10], // 2 hours = 20 XP expected
        ]);

        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertFalse($booking->xp_awarded);

        // Transition status to terkonfirmasi (awards XP)
        $responseConfirm = $this->actingAs($admin)
            ->patch(route('admin.bookings.updateStatus', $booking), [
                'status' => 'terkonfirmasi',
            ]);

        $booking->refresh();
        $member->refresh();
        $this->assertTrue($booking->xp_awarded);
        $this->assertEquals(20, $member->xp);

        // Transition status to dibatalkan (deducts XP)
        $responseCancel = $this->actingAs($admin)
            ->patch(route('admin.bookings.updateStatus', $booking), [
                'status' => 'dibatalkan',
            ]);

        $booking->refresh();
        $member->refresh();
        $this->assertFalse($booking->xp_awarded);
        $this->assertEquals(0, $member->xp);
    }

    public function test_admin_can_adjust_member_xp(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => 'MB-ADJUST',
            'level' => 1,
            'tier' => 'bronze',
            'xp' => 50,
        ]);

        // Admin adds XP
        $responseAdd = $this->actingAs($admin)
            ->post(route('admin.members.adjust-xp', $member), [
                'xp_amount' => 60,
                'type' => 'add',
                'description' => 'Bonus promo',
            ]);

        $member->refresh();
        $this->assertEquals(110, $member->xp);
        $this->assertEquals('silver', $member->tier); // automatically upgraded

        // Admin subtracts XP
        $responseSubtract = $this->actingAs($admin)
            ->post(route('admin.members.adjust-xp', $member), [
                'xp_amount' => 30,
                'type' => 'subtract',
                'description' => 'Salah hitung',
            ]);

        $member->refresh();
        $this->assertEquals(80, $member->xp);
        $this->assertEquals('bronze', $member->tier); // automatically downgraded
    }
}
