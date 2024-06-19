<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $rsetBarangKeluar = BarangKeluar::with('barang')->latest()->paginate(10);
        return view('view_barangkeluar.index', compact('rsetBarangKeluar'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function create()
    {
        $abarangkeluar = Barang::all();
        return view('view_barangkeluar.create',compact('abarangkeluar'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|numeric|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Validate if qty_keluar is greater than stok
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }

        DB::beginTransaction();
        try {
            // Create BarangKeluar entry
            BarangKeluar::create([
                'tgl_keluar' => $request->tgl_keluar,
                'qty_keluar' => $request->qty_keluar,
                'barang_id' => $request->barang_id,
            ]);

            // Update Barang stock
            $barang->stok -= $request->qty_keluar;
            $barang->save();

            DB::commit();

            return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating BarangKeluar entry: ' . $e->getMessage());

            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menyimpan data!']);
        }
    }
    
    public function show($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        return view('view_barangkeluar.show', compact('barangKeluar'));
    }
    

    //delete record di tambel barangkeluar tanpa memengaruhi stok di tabel barang
    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barangKeluar->delete();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


    public function edit($id)
    {
        $barangKeluar= BarangKeluar::findOrFail($id);
        $abarangkeluar = Barang::all();

        return view('view_barangkeluar.edit', compact('barangKeluar', 'abarangkeluar'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'tgl_keluar'     => 'required',
            'qty_keluar'     => 'required',
            'barang_id'     => 'required',
        ]);

        $barang = Barang::find($request->barang_id);

        //Validasi jika jumlah qty_keluar lebih besar dari stok saat itu maka muncul pesan eror
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }
        
        //update record barang keluar
        $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->update([
                'tgl_keluar' => $request->tgl_keluar,
                'qty_keluar' => $request->qty_keluar,
                'barang_id' => $request->barang_id,
            ]);

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

}