@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
    use App\KontigenModel;
    $data = KontigenModel::get();
@endphp
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Kontigen</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adddata">Tambah Data</button>
            <div class="card-body">
                <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Kontigen</th>
                        <th>Manager</th>
                        <th>Official</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="tb-category">
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td> {{$item->kontigen}}</td>
                            <td>{{ $item->manager }}</td>
                            <td>{{ $item->official }}</td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{$item->id}}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal{{$item->id}}">Delete</button>
                            </td>
                            </div>
                        </tr>
                        
                        <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Kontigen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                    </div>
                                    <form action="{{ route('admin.modifyKontigen') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="kontigen" class="form-label">Kontigen</label>
                                                <input type="text" name="kontigen" class="form-control" id="kontigen" autocomplete="off" placeholder="Kontigen" value="{{$item->kontigen}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="manager" class="form-label">Manager</label>
                                                <input type="text" name="manager" class="form-control" id="manager" placeholder="Manager" value="{{$item->manager}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="official" class="form-label">Official</label>
                                                <input type="text" name="official" class="form-control" id="official" autocomplete="off" placeholder="Official" value="{{$item->official}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="nohp" class="form-label">No Hp</label>
                                                <input type="text" name="nohp" class="form-control" id="nohp" autocomplete="off" placeholder="Nomor Hp" value="{{$item->hp}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <input type="text" name="provinsi" class="form-control" id="provinsi" autocomplete="off" placeholder="Provinsi" value="{{$item->provinsi}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="kota" class="form-label">Kota</label>
                                                <input type="text" name="kota" class="form-control" id="kota" autocomplete="off" placeholder="Nama Kota" value="{{$item->kota}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                                <input type="text" name="kecamatan" class="form-control" id="kecamatan" autocomplete="off" placeholder="Nama Kecamatan" value="{{$item->kecamatan}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="desa" class="form-label">Desa</label>
                                                <input type="text" name="desa" class="form-control" id="desa" autocomplete="off" placeholder="Nama Desa" value="{{$item->desa}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" name="alamat" class="form-control" id="alamat" autocomplete="off" placeholder="Alamat" value="{{$item->alamat}}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="idEdit" id="idEdit" value="{{$item->id}}">
                                            <input type="hidden" name="status" id="status" value="edit">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Edit Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.modifyKontigen') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteLabel">Hapus Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin Hapus Data ini?
                                        <input type="hidden" name="idHapus" id="idHapus" value="{{$item->id}}">
                                        <input type="hidden" name="status" id="status" value="hapus">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Hapus Data</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>

                        @endforeach 
                    
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adddata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Kontigen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{ route('admin.modifyKontigen') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kontigen" class="form-label">Kontigen</label>
                            <input type="text" name="kontigen" class="form-control" id="kontigen" autocomplete="off" placeholder="Kontigen">
                        </div>
                        <div class="mb-3">
                            <label for="manager" class="form-label">Manager</label>
                            <input type="text" name="manager" class="form-control" id="manager" placeholder="Manager">
                        </div>
                        <div class="mb-3">
                            <label for="official" class="form-label">Official</label>
                            <input type="text" name="official" class="form-control" id="official" autocomplete="off" placeholder="Official">
                        </div>
                        <div class="mb-3">
                            <label for="nohp" class="form-label">No Hp</label>
                            <input type="text" name="nohp" class="form-control" id="nohp" autocomplete="off" placeholder="Nomor Hp">
                        </div>
                        <div class="mb-3">
                            <label for="provinsi" class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" id="provinsi" autocomplete="off" placeholder="Provinsi">
                        </div>
                        <div class="mb-3">
                            <label for="kota" class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control" id="kota" autocomplete="off" placeholder="Nama Kota">
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" id="kecamatan" autocomplete="off" placeholder="Nama Kecamatan">
                        </div>
                        <div class="mb-3">
                            <label for="desa" class="form-label">Desa</label>
                            <input type="text" name="desa" class="form-control" id="desa" autocomplete="off" placeholder="Nama Desa">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" id="alamat" autocomplete="off" placeholder="Alamat">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="status" id="status" value="add">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
