<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipTier;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Membership::with(['user', 'tier'])->orderBy('created_at', 'desc')->get();
        $tiers = MembershipTier::all();
        $editTier = null;
        return view('admin.member', compact('members', 'tiers', 'editTier'));
    }

    public function verifikasi($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->update([
            'status_pembayaran' => 'lunas',
            'status' => 'aktif',
        ]);
        return back()->with('success', 'Membership berhasil diverifikasi dan diaktifkan.');
    }

    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();
        return back()->with('success', 'Riwayat membership berhasil dihapus.');
    }

    // CRUD MEMBERSHIP TIERS
    public function createTier()
    {
        return view('admin.member', [
            'editTier' => null,
            'tiers' => MembershipTier::all(),
            'members' => Membership::with(['user', 'tier'])->get(),
        ]);
    }

    public function storeTier(Request $request)
    {
        $request->validate([
            'nama_tier' => 'required|string|unique:membership_tiers',
            'harga_paket' => 'required|numeric|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'diskon_persen' => 'required|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        MembershipTier::create($request->all());
        return redirect()->route('admin.member')->with('success', 'Paket membership berhasil ditambahkan.');
    }

    public function editTier($id)
    {
        $editTier = MembershipTier::findOrFail($id);
        $tiers = MembershipTier::all();
        $members = Membership::with(['user', 'tier'])->get();
        return view('admin.member', compact('editTier', 'tiers', 'members'));
    }

    public function updateTier(Request $request, $id)
    {
        $tier = MembershipTier::findOrFail($id);
        $request->validate([
            'nama_tier' => 'required|string|unique:membership_tiers,nama_tier,'.$id,
            'harga_paket' => 'required|numeric|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'diskon_persen' => 'required|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $tier->update($request->all());
        return redirect()->route('admin.member')->with('success', 'Paket membership berhasil diperbarui.');
    }

    public function destroyTier($id)
    {
        $tier = MembershipTier::findOrFail($id);
        $count = Membership::where('tier_id', $id)->count();
        if ($count > 0) {
            return back()->with('error', 'Paket ini tidak bisa dihapus karena masih digunakan oleh member.');
        }
        $tier->delete();
        return back()->with('success', 'Paket membership berhasil dihapus.');
    }
}