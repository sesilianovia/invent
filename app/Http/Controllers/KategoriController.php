<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;


class KategoriController extends Controller
{
    public function index(Request $request)
    {
    /**
    * Display a listing of the resource.
    */

        // $rsetKategori = Kategori::getKategoriAll();
//        $rsetKategori = DB::table('kategori')->select('id','kategori',DB::raw('ketKategori(jenis) as ketkategori'))->get();
        // return $rsetKategori;

//        return DB::table('kategori')->get();
        //$rsetKategori = DB::select('CALL getKategoriAll');
       // return view('v_eksperimen.index', compact('rsetKategori'));
        //return $set;

        // $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('ketKategoriko(kategori) as kategori'))->get();
        // return view('v_eksperimen.index',compact('rsetKategori'));

        // $rsetKategori = Kategori::getKategoriAll()->paginate(20);
        // return view('v_eksperimen.index',compact('rsetKategori'));

        // $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('ketKategori(kategori) as ketkategori'))->paginate(10);
        // return view('view_kategori.index',compact('rsetKategori'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);

        $rsetKategori = Kategori::all();
        return view('view_kategori.index', compact('rsetKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aKategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('view_kategori.create',compact('aKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    use ValidatesRequests;
    public function store(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'deskripsi'   => 'required',
            'kategori'     => 'required | in:M,A,BHP,BTHP',
        ]);


        //create post
        try {
            DB::beginTransaction(); // <= Starting the transaction
            // Insert a new order history
            DB::table('kategori')->insert([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
            ]);
        
            DB::commit(); // <= Commit the changes
        } catch (\Exception $e) {
            report($e);
            
            DB::rollBack(); // <= Rollback in case of an exception
        }
        ;

        //redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);

        // $rsetKategori = Kategori::select('id','deskripsi','kategori',
        //     \DB::raw('(CASE
        //         WHEN kategori = "M" THEN "Modal"
        //         WHEN kategori = "A" THEN "Alat"
        //         WHEN kategori = "BHP" THEN "Bahan Habis Pakai"
        //         ELSE "Bahan Tidak Habis Pakai"
        //         END) AS ketKategori'))->where('id', '=', $id);

        return view('view_kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
        {
        // $akategori = Kategori::all();
        $kategori = Kategori::find($id);

        // $selectedKategori = Kategori::find($kategori->kategori_id);
        return view('view_kategori.edit', compact('kategori'));
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this->validate($request, [
            'deskripsi'              => 'required',
            'kategori'              => 'required',
            // 'spesifikasi'       => 'required',
            // 'stok'              => 'required',
            // 'kategori_id'       => 'required',
        ]);

        $kategori = Kategori::find($id);
            $kategori->update([
                'deskripsi'              => $request->deskripsi,
                'kategori'              => $request->kategori,
                // 'spesifikasi'       => $request->spesifikasi,
                // 'stok'              => $request->stok,
                // 'kategori_id'       => $request->kategori_id
            ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Kategori Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
	if (DB::table('barang')-> where('kategori_id','$id')->exists()){
	return redirect()->route('kategori.index')->with(['Gagal'=> 'Data gagal dihapus!']);

	} else {
	$rsetKategori = Kategori::find($id);
	$rsetKategori ->delete();
	return redirect()->route('kategori.index')->with(['success'=> 'Data berhasil dihapus!']);
	}


        //$rsetKategori = Kategori::find($id);
        //delete image
        // Storage::delete('public/foto/'. $rsetKategori->foto);

        //delete post
        //$rsetKategori->delete();

        //redirect to index
        //return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}