<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Exception;

class DataController extends Controller
{
    public function index()
    {
        $data = Data::orderBy('namaLengkap', 'asc')->get();

        return view('data.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaLengkap' => 'required|max:255',
            'nomorKTP' => 'required|unique:data|max:255',
            'tanggalLahir' => 'required|date',
            'alamat' => 'required',
            'foto' => 'nullable|image|max:200', // Validasi untuk foto (opsional dan maks 200 KB)
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();

            // Kompres gambar menggunakan Intervention Image
            $compressedImage = Image::make($foto)->encode('jpg', 75); // Kompres ke format JPG dengan kualitas 75%

            // Simpan gambar ke direktori uploads/foto
            Storage::put('uploads/foto/' . $filename, (string) $compressedImage->encode());

            $validated['foto'] = $filename;
        }

        Data::create($validated);

        return redirect()->route('data.index')
            ->with('success', 'Data KTP berhasil disimpan!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Data::findOrFail($id);

        return view('data.edit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'namaLengkap' => 'required|max:255',
            'nomorKTP' => 'required|max:255|unique:data,nomorKTP,' . $id,
            'tanggalLahir' => 'required|date',
            'alamat' => 'required',
            'foto' => 'nullable|image|max:200', // Validasi untuk foto (opsional dan maks 200 KB)
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();

            // Kompres gambar menggunakan Intervention Image
            $compressedImage = Image::make($foto)->encode('jpg', 75); // Kompres ke format JPG dengan kualitas 75%

            // Simpan gambar ke direktori uploads/foto
            Storage::put('uploads/foto/' . $filename, (string) $compressedImage->encode());

            $validated['foto'] = $filename;
        }

        $data = Data::findOrFail($id);
        $data->update($validated);

        Alert::success('Success', 'Data KTP berhasil diperbarui!');
        return redirect()->route('data.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $data = Data::findOrFail($id);
        $data->status = $request->input('status');
        $data->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $data = Data::findOrFail($id);
            $data->delete();

            Alert::success('Success', 'Data KTP berhasil dihapus!');
        } catch (Exception $ex) {
            Alert::error('Error', 'Tidak dapat menghapus, data KTP telah digunakan!');
        }

        return redirect()->route('data.index');
    }
}
