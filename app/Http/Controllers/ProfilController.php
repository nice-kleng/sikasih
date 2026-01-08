<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\IbuHamil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Show profil page
     */
    public function index()
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        return view('app.profil', compact('user', 'ibuHamil'));
    }

    /**
     * Update foto profil (separate method)
     */
    public function updateFoto(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        // Store new photo
        $path = $request->file('foto')->store('fotos', 'public');

        // Update user
        $user->update(['foto' => $path]);

        return redirect()->route('app.profil')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Update user
        $user->update([
            'nama' => $validated['nama'],
            'no_telepon' => $validated['no_telepon'],
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('fotos', 'public');
            $user->update(['foto' => $path]);
        }

        // Update ibu hamil
        if ($ibuHamil) {
            $ibuHamil->update([
                'nama_lengkap' => $validated['nama'],
                'alamat_lengkap' => $validated['alamat_lengkap'] ?? $ibuHamil->alamat_lengkap,
                'rt' => $validated['rt'] ?? $ibuHamil->rt,
                'rw' => $validated['rw'] ?? $ibuHamil->rw,
                'kelurahan' => $validated['kelurahan'] ?? $ibuHamil->kelurahan,
                'kecamatan' => $validated['kecamatan'] ?? $ibuHamil->kecamatan,
                'kabupaten' => $validated['kabupaten'] ?? $ibuHamil->kabupaten,
                'provinsi' => $validated['provinsi'] ?? $ibuHamil->provinsi,
                'kode_pos' => $validated['kode_pos'] ?? $ibuHamil->kode_pos,
            ]);
        }

        return redirect()->route('app.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('app.profil')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Show pengaturan page
     */
    public function pengaturan()
    {
        $user = auth()->user();

        return view('app.pengaturan', compact('user'));
    }

    /**
     * Update pengaturan notifikasi
     */
    public function updatePengaturan(Request $request)
    {
        $user = auth()->user();

        // Here you can handle notification preferences
        // For now, just return success

        return redirect()->route('app.pengaturan')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
