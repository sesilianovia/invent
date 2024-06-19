<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barangmasuk';

    protected $fillable = ['tgl_masuk', 'qty_masuk', 'barang_id'];

    // Definisikan relasi dengan model Barang jika diperlukan
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}