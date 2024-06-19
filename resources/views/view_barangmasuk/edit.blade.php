@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Edit Barang Masuk</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangmasuk.update', $barangMasuk->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">TANGGAL MASUK</label>
                                <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" name="tgl_masuk" value="{{ old('tgl_masuk', $barangMasuk->tgl_masuk) }}" placeholder="Masukkan Tanggal Masuk Barang">
                           
                                <!-- error message untuk tgl_masuk -->
                                @error('tgl_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">JUMLAH MASUK</label>
                                <input type="number" min="0" class="form-control @error('qty_masuk') is-invalid @enderror" name="qty_masuk" value="{{ old('qty_masuk', $barangMasuk->qty_masuk) }}" placeholder="Masukkan Jumlah Masuk Barang">
                           
                                <!-- error message untuk qty_masuk -->
                                @error('qty_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">PILIH BARANG</label>
                                <select class="form-control" name="barang_id" aria-label="Default select example">
                                    <option value="blank">Pilih Barang</option>
                                    @foreach ($abarangmasuk as $rowbarangmasuk)
                                        <option value="{{ $rowbarangmasuk->id }}" {{ $barangMasuk->barang_id == $rowbarangmasuk->id ? 'selected' : '' }}>
                                            {{ $rowbarangmasuk->merk }}
                                        </option>
                                    @endforeach
                                </select>
                               
                                <!-- error message untuk kategori -->
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">UPDATE</button>
                            <a href="{{ route('barangmasuk.index') }}" class="btn btn-md btn-secondary">BATAL</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
