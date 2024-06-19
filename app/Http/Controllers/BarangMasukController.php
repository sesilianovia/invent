<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $rsetBarangMasuk = BarangMasuk::with('barang')->latest()->paginate(10);
        return view('view_barangmasuk.index', compact('rsetBarangMasuk'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function create()
    {
        $abarangmasuk = Barang::all();
        return view('view_barangmasuk.create', compact('abarangmasuk'));
    }
    
    
    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'required|numeric|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        DB::beginTransaction();
        try {
            // Check if BarangMasuk entry already exists for this Barang
            $existingBarangMasuk = BarangMasuk::where('barang_id', $request->barang_id)
                                              ->where('tgl_masuk', $request->tgl_masuk)
                                              ->first();

            if ($existingBarangMasuk) {
                // Jika sudah ada entri, tambahkan ke stok berdasarkan selisih
                $barang = Barang::findOrFail($request->barang_id);
                $barang->stok += $request->qty_masuk;
                $barang->save();
            } else {
                // Jika belum ada entri, buat entri baru
                BarangMasuk::create([
                    'tgl_masuk' => $request->tgl_masuk,
                    'qty_masuk' => $request->qty_masuk,
                    'barang_id' => $request->barang_id,
                ]);

                // Update Barang stock
                $barang = Barang::findOrFail($request->barang_id);
                $barang->stok += $request->qty_masuk;
                $barang->save();
            }

            DB::commit();

            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating BarangMasuk entry: ' . $e->getMessage());

            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menyimpan data!']);
        }
    }


    public function show($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        return view('view_barangmasuk.show', compact('barangMasuk'));
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        
        // Rollback stock update
        $barang = Barang::findOrFail($barangMasuk->barang_id);
        $barang->stok -= $barangMasuk->qty_masuk;
        $barang->save();

        $barangMasuk->delete();

        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


    public function edit($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $abarangmasuk = Barang::all();

        return view('view_barangmasuk.edit', compact('barangMasuk', 'abarangmasuk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'required|numeric|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        DB::beginTransaction();
        try {
            // Update BarangMasuk entry
            $barangMasuk = BarangMasuk::findOrFail($id);
            $barangMasuk->update([
                'tgl_masuk' => $request->tgl_masuk,
                'qty_masuk' => $request->qty_masuk,
                'barang_id' => $request->barang_id,
            ]);

            // Adjust stock based on quantity change
            $previous_qty_masuk = $barangMasuk->qty_masuk;
            $new_qty_masuk = $request->qty_masuk;
            $difference = $new_qty_masuk - $previous_qty_masuk;

            // Update Barang stock
            $barang = Barang::findOrFail($request->barang_id);
            $barang->stok += $request->qty_masuk; // Tambahkan qty_masuk ke stok barang
            $barang->save();

            DB::commit();

            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating BarangMasuk entry: ' . $e->getMessage());

            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat mengupdate data!']);
        }
    }
}
