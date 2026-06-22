<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Certificate::latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_lsp', 'like', "%{$search}%");
            });
        }

        $certificates = $query->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($certificates);
        }

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'provinsi'           => 'required|string|max:255',
            'kabupaten'          => 'required|string|max:255',
            'klasifikasi'        => 'required|string|max:255',
            'subklasifikasi'     => 'required|string|max:255',
            'kualifikasi'        => 'required|string|max:255',
            'kode_jabatan'       => 'required|string|max:255',
            'jabatan_kerja'      => 'required|string|max:255',
            'nomor_registrasi'   => 'required|string|max:255|unique:certificates,nomor_registrasi',
            'nama_lsp'           => 'required|string|max:255',
            'nama_asosiasi'      => 'required|string|max:255',
            'tanggal_ditetapkan' => 'required|date',
            'tanggal_berlaku'    => 'required|date',
        ]);

        $validated['tanggal_ditetapkan'] = \Carbon\Carbon::parse($validated['tanggal_ditetapkan'])->format('Y-m-d H:i:s');
        $validated['tanggal_berlaku'] = \Carbon\Carbon::parse($validated['tanggal_berlaku'])->format('Y-m-d H:i:s');

        $cert = Certificate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil ditambahkan.',
            'data'    => $cert,
        ], 201);
    }

    /**
     * Display the specified resource (public certificate view).
     */
    public function show(Certificate $certificate)
    {
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'provinsi'           => 'required|string|max:255',
            'kabupaten'          => 'required|string|max:255',
            'klasifikasi'        => 'required|string|max:255',
            'subklasifikasi'     => 'required|string|max:255',
            'kualifikasi'        => 'required|string|max:255',
            'kode_jabatan'       => 'required|string|max:255',
            'jabatan_kerja'      => 'required|string|max:255',
            'nomor_registrasi'   => [
                'required', 'string', 'max:255',
                Rule::unique('certificates', 'nomor_registrasi')->ignore($certificate->id),
            ],
            'nama_lsp'           => 'required|string|max:255',
            'nama_asosiasi'      => 'required|string|max:255',
            'tanggal_ditetapkan' => 'required|date',
            'tanggal_berlaku'    => 'required|date',
        ]);

        $validated['tanggal_ditetapkan'] = \Carbon\Carbon::parse($validated['tanggal_ditetapkan'])->format('Y-m-d H:i:s');
        $validated['tanggal_berlaku'] = \Carbon\Carbon::parse($validated['tanggal_berlaku'])->format('Y-m-d H:i:s');

        $certificate->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diupdate.',
            'data'    => $certificate->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dihapus.',
        ]);
    }
}
