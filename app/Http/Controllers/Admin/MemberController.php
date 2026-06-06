<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('member_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function show(Member $member)
    {
        $member->load(['user', 'xpLogs' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        $logs = $member->xpLogs()->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.members.show', compact('member', 'logs'));
    }

    public function adjustXP(Request $request, Member $member)
    {
        $request->validate([
            'xp_amount' => 'required|integer|min:1',
            'type' => 'required|in:add,subtract',
            'description' => 'required|string|max:255',
        ]);

        $xp = (int) $request->xp_amount;
        $type = $request->type;
        $desc = $request->description;

        if ($type === 'add') {
            $member->addXP($xp, "{$desc} (Penyesuaian Manual Admin)");
            $message = "Berhasil menambahkan {$xp} XP secara manual.";
        } else {
            $member->subtractXP($xp, "{$desc} (Penyesuaian Manual Admin)");
            $message = "Berhasil mengurangi {$xp} XP secara manual.";
        }

        return back()->with('success', $message);
    }
}
