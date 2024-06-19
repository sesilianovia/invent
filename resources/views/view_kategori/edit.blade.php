@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>EDIT KATEGORI</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('kategori.update',$kategori->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">DESKRIPSI</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" value="{{ old('deskripsi',$kategori->deskripsi) }}" placeh>

                                <!-- error message untuk deskripsi -->
                                @error('deskripsi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">KATEGORI</label>
                                <select class="form-control @error('kategori') is-invalid @enderror" name="kategori" aria-label="Default select example">
                                    <option value="blank" selected>Pilih kategori</option>
                                    <option value="M" @if(old('kategori', $kategori->kategori) == 'M') selected @endif>Barang Modal</option>
                                    <option value="A" @if(old('kategori', $kategori->kategori) == 'A') selected @endif>Alat</option>
                                    <option value="BHP" @if(old('kategori', $kategori->kategori) == 'BHP') selected @endif>Bahan Habis Pakai</option>
                                    <option value="BTHP" @if(old('kategori', $kategori->kategori) == 'BTHP') selected @endif>Bahan Tidak Habis Pakai</option>
                                </select>

                                <!-- error message for kategori -->
                                @error('kategori')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                            <a href="{{ route('kategori.index') }}" class="btn btn-md btn-primary">Back</a>
                        </form>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
