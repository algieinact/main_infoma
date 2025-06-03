<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller{
    public function index()
    {
        // Tampilkan halaman kontak
        return view('kontak');
    }

    public function store(Request $request)
    {
        
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Simpan pesan kontak (bisa disimpan ke database atau dikirim via email)
        // Contoh: Mail::to('
}
}