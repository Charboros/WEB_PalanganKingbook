<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $member = $user->member;

        $logs = null;
        if ($member) {
            $logs = $member->xpLogs()->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('user.membership.index', compact('user', 'member', 'logs'));
    }

    public function activate(Request $request)
    {
        $user = auth()->user();

        if ($user->member) {
            return back()->with('error', 'Anda sudah menjadi member aktif.');
        }

        // Generate unique member code: MB-YYYYMMDD-XXXX
        $dateStr = now()->format('Ymd');
        $randomStr = strtoupper(Str::random(4));
        $memberCode = "MB-{$dateStr}-{$randomStr}";

        // Ensure uniqueness
        while (Member::where('member_code', $memberCode)->exists()) {
            $randomStr = strtoupper(Str::random(4));
            $memberCode = "MB-{$dateStr}-{$randomStr}";
        }

        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => $memberCode,
            'level' => 1,
            'tier' => 'bronze',
            'xp' => 0,
        ]);

        // Add welcome log
        $member->xpLogs()->create([
            'xp_amount' => 0,
            'description' => 'Aktivasi membership awal (Bronze)',
        ]);

        return redirect()->route('user.membership')->with('success', 'Selamat! Fitur Membership Anda berhasil diaktifkan.');
    }
}
