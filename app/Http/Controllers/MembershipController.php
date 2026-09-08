<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tiers = MembershipTier::orderBy('harga_paket')->get();

        // Membership aktif
        $active = Membership::with('tier')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->first();

        // Membership pending (menunggu verifikasi)
        $pending = Membership::with('tier')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->first();

        // Riwayat semua membership
        $riwayat = Membership::with('tier')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.membership', compact('tiers', 'active', 'pending', 'riwayat'));
    }
}