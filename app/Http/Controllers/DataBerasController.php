<?php

namespace App\Http\Controllers;

use App\Models\DataBeras;
use App\Http\Requests\DataBerasRequest;
use Illuminate\Http\Request;

class DataBerasController extends Controller
{
    public function index(Request $request)
    {
        $dataBeras = DataBeras::orderBy('tahun', 'desc')
                              ->orderBy('bulan', 'desc')
                              ->paginate(12);
        return view('admin.data-beras.index', compact('dataBeras'));
    }

    public function create()
    {
        return view('admin.data-beras.create');
    }

    public function store(DataBerasRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['impor_ton'] = 0;
        $validatedData['ekspor_ton'] = 0;
        $validatedData['stok_awal_ton'] = 0; 
        
        $validatedData['konsumsi_ton'] = 73.75;

        DataBeras::create($validatedData);
        return redirect()->route('admin.data-beras.index')->with('success', 'Data beras berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $dataBeras = DataBeras::findOrFail($id);
        return view('admin.data-beras.edit', compact('dataBeras'));
    }

    public function update(DataBerasRequest $request, string $id)
    {
        $dataBeras = DataBeras::findOrFail($id);
        $validatedData = $request->validated();
        $validatedData['impor_ton'] = 0;
        $validatedData['ekspor_ton'] = 0;
        $validatedData['stok_awal_ton'] = 0; 
        
        $validatedData['konsumsi_ton'] = 73.75;

        $dataBeras->update($validatedData);
        return redirect()->route('admin.data-beras.index')->with('success', 'Data beras berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $dataBeras = DataBeras::findOrFail($id);
        $dataBeras->delete();
        return redirect()->route('admin.data-beras.index')->with('success', 'Data beras berhasil dihapus!');
    }
}