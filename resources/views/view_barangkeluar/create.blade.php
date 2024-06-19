@extends('layouts.adm-main')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
		<div class="pull-left">
		    <h2>BARANG KELUAR</h2>
		</div>
        @if (Session::has('failed'))
            <script>
                alert("{{ Session::get('failed') }}");
            </script>
        @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangkeluar.store') }}" method="POST" enctype="multipart/form-data">                    
                            @csrf


                            <div class="form-group">
                                <label class="font-weight-bold">TANGGAL KELUAR</label>
                                <input type="date" class="form-control @error('tgl_keluar') is-invalid @enderror" name="tgl_keluar" value="{{ old('tgl_keluar') }}" placeholder="Masukkan Tanggal Keluar Barang">
                           
                                <!-- error message untuk tgl_keluar -->
                                @error('tgl_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>




                            <div class="form-group">
                                <label class="font-weight-bold">JUMLAH KELUAR</label>
                                <input type="number" min="0" class="form-control @error('qty_keluar') is-invalid @enderror" name="qty_keluar" value="{{ old('qty_keluar') }}" placeholder="Masukkan Jumlah Keluar Barang">
                           
                                <!-- error message untuk qty_keluar -->
                                @error('qty_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>




                            <div class="form-group">
                                <label class="font-weight-bold">PILIH BARANG</label>
                                <select class="form-control" name="barang_id" aria-label="Default select example">
                                    <option value="blank">Pilih Barang</option>
                                    @foreach ($abarangkeluar as $rowbarangkeluar)
                                        <option value="{{ $rowbarangkeluar->id  }}">{{ $rowbarangkeluar->merk  }}</option>
                                    @endforeach
                                </select>
                               
                                <!-- error message untuk kategori -->
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
