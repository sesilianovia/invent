<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        // Perform search query on the Barang model
        $resultsBarang = Barang::where('merk', 'LIKE', "%{$query}%")
                         ->get();
        $resultsKategori = Kategori::where('deskripsi', 'LIKE', "%{$query}%")
                         ->get();

        return view('search.index', compact('resultsBarang', 'resultsKategori', 'query'));
    }

}
