<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\MembershipTier;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Payment reservasi
    public function reservasi($id)
    {
        $reservasi = Reservasi::with('lapangan')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('customer.payment', compact('reservasi'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'bukti' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $reservasi = Reservasi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $file = $request->file('bukti');
        $filename = 'payment_' . time() . '_' . $id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti', $filename, 'public');

        $reservasi->update([
            'status_pembayaran' => 'menunggu_verifikasi',
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => '/storage/' . $path,
            'tanggal_bayar' => now(),
        ]);

        return redirect()->route('customer.riwayat')->with('success', 'Bukti berhasil diupload. Menunggu verifikasi admin.');
    }

    // Payment membership
   public function membership($id)
{
    $tier = MembershipTier::find($id);
    if (!$tier) {
        return redirect()->route('customer.membership')->with('error', 'Paket membership tidak ditemukan.');
    }
    return view('customer.payment-membership', compact('tier'));
}

    public function uploadMembership(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'nama_rekening' => 'required|string|max:255',
            'bukti' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $tier = MembershipTier::findOrFail($id);
        $user = Auth::user();

        // Cek membership aktif
        $existingActive = Membership::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->first();

        if ($existingActive) {
            return back()->with('error', 'Anda masih memiliki membership aktif. Tunggu hingga habis masa berlakunya.');
        }

        // Upload file
        $file = $request->file('bukti');
        $filename = 'membership_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti', $filename, 'public');

        // Simpan membership
        $membership = new Membership();
        $membership->user_id = $user->id;
        $membership->tier_id = $tier->id;
        $membership->tanggal_mulai = date('Y-m-d');
        $membership->tanggal_berakhir = date('Y-m-d', strtotime("+{$tier->durasi_hari} days"));
        $membership->status = 'pending';
        $membership->status_pembayaran = 'menunggu_verifikasi';
        $membership->metode_pembayaran = $request->metode_pembayaran;
        $membership->bukti_pembayaran = '/storage/' . $path;
        $membership->tanggal_bayar = now();
        $membership->save();

        // Redirect ke halaman membership dengan pesan sukses
        return redirect()->route('customer.membership')->with('success', 'Pembayaran membership berhasil diupload. Menunggu verifikasi admin.');
    }
}