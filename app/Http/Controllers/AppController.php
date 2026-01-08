<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\IbuHamil;
use App\Models\Puskesmas;
use App\Models\Artikel;
use App\Models\VideoEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AppController extends Controller
{
    /**
     * Show public landing page (NEW - for guest users)
     */
    public function home()
    {
        // If already logged in, redirect to beranda
        if (auth()->check() && auth()->user()->hasRole('ibu_hamil')) {
            return redirect()->route('app.beranda');
        }

        // Get latest articles (3 items)
        $artikels = Artikel::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Get latest videos (3 items)
        $videos = VideoEdukasi::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('public.beranda-public', compact('artikels', 'videos'));
    }

    /**
     * Show login page
     */
    public function login()
    {
        if (auth()->check() && auth()->user()->hasRole('ibu_hamil')) {
            return redirect()->route('app.beranda');
        }

        return view('app.login');
    }

    /**
     * Handle login request
     */
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Check if user is ibu_hamil
            if (!$user->hasRole('ibu_hamil')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akses ini khusus untuk ibu hamil.',
                ]);
            }

            return redirect()->intended(route('app.beranda'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Show register page
     */
    public function register()
    {
        if (auth()->check() && auth()->user()->hasRole('ibu_hamil')) {
            return redirect()->route('app.beranda');
        }

        // Get all active puskesmas for dropdown
        $puskesmas = Puskesmas::where('status', 'aktif')->get();

        return view('app.register', compact('puskesmas'));
    }

    /**
     * Handle register request
     */
    public function registerPost(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'no_telepon' => 'required|string|max:20',
            'nik' => 'required|string|size:16|unique:ibu_hamil,nik',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat_lengkap' => 'required|string',
            'puskesmas_id' => 'required|exists:puskesmas,id',
        ]);

        // Create user with pending status
        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_telepon' => $validated['no_telepon'],
            'status' => 'pending', // Pending approval
        ]);

        // Assign ibu_hamil role
        $role = Role::firstOrCreate(['name' => 'ibu_hamil']);
        $user->assignRole($role);

        // Generate No. RM
        $puskesmas = Puskesmas::find($validated['puskesmas_id']);
        $lastIbuHamil = IbuHamil::where('puskesmas_id', $validated['puskesmas_id'])
            ->latest('id')
            ->first();
        $lastNumber = $lastIbuHamil ? (int) substr($lastIbuHamil->no_rm, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $noRm = 'RM-' . date('Y') . '-' . $newNumber;

        // Calculate age
        $birthDate = new \DateTime($validated['tanggal_lahir']);
        $today = new \DateTime('today');
        $age = $birthDate->diff($today)->y;

        // Create ibu_hamil record
        IbuHamil::create([
            'user_id' => $user->id,
            'puskesmas_id' => $validated['puskesmas_id'],
            'no_rm' => $noRm,
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'umur' => $age,
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'status' => 'aktif',
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('app.beranda')->with('success', 'Pendaftaran berhasil! Akun Anda menunggu persetujuan admin.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('app.home'); // Redirect to public home instead of login
    }
}
