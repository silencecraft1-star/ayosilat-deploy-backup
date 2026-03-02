@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
@endpush

@section('content')
    @php
        use App\PersertaModel;
        use App\KontigenModel;
        use App\category;
        use App\kelas;
        $data = PersertaModel::take(30)->get();
        $dataKontigen = KontigenModel::get();
        $dataKelas = kelas::get();
        $dataKategori = category::get();
    @endphp
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Perserta</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adddata">Tambah
                        Peserta</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#uploadModal"
                        class="btn btn-success">Tambah Excel</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#clearModal"
                        class="btn btn-danger">Bersihkan Data</button>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kontigen</th>
                                        <th>Kelas</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tb-category">
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td> {{ $item->name }}</td>
                                            @php

                                                $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                $kelas = kelas::where('id', $item->kelas)->first();
                                                $category = category::where('id', $item->category)->first();

                                            @endphp
                                            <td>{{ $kontigen->kontigen ?? 'Non' }}</td>
                                            <td>{{ $kelas->name ?? 'Non' }}</td>
                                            <td>{{ $category->name ?? 'Non' }}</td>
                                            <td>
                                                <button data-form-edit="{{ $item->id }}" class="btn btn-primary">
                                                    Edit
                                                </button>
                                                {{-- <form id="form-delete-{{ $item->id }}" action="{{ route('category.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form> --}}
                                                <button class="btn btn-danger btn-sm delete-button"
                                                    data-form-delete="{{ $item->id }}">Delete</button>
                        </div>
                        </td>
                        </tr>
                        @endforeach

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ url('/admin/add-excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ url('/admin/clear-data') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Konfirmasi Bersihkan Data</h5>
                        </div>
                        <div class="modal-body">
                            <label for="passwordClear" class="form-label">Masukan Password</label>
                            <input type="text" name="passwordClear" id="passwordClear" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Hapus Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adddata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" action="{{ route('admin.newPeserta') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Nama Peserta</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" name="name"
                                required autocomplete="off" placeholder="">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kontigen Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kontigenPeserta"
                                    id="kontigenPeserta">
                                    @foreach ($dataKontigen as $item)
                                        <option value="{{ $item->id }}">{{ $item->kontigen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kelas Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kelasPeserta"
                                    id="kelasPeserta">
                                    @foreach ($dataKelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kategori Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kategoriPeserta"
                                    id="kategoriPeserta">
                                    @foreach ($dataKategori as $item)
                                        @if ($item->name != '')
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Peserta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" action="{{ route('admin.editPeserta') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Nama Peserta</label>
                            <input type="text" class="form-control" id="editPesertaName" name="name" required
                                autocomplete="off" placeholder="">
                        </div>
                        <input type="hidden" id="idEdit" name="id" value="">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kontigen Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kontigenPeserta"
                                    id="editKontigenPeserta">
                                    @foreach ($dataKontigen as $item)
                                        <option value="{{ $item->id }}">{{ $item->kontigen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kelas Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kelasPeserta"
                                    id="editKelasPeserta">
                                    @foreach ($dataKelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="">Kategori Peserta</div>
                                <select class="js-example-basic-single" style="width: 100%;" name="kategoriPeserta"
                                    id="editKategoriPeserta">
                                    @foreach ($dataKategori as $item)
                                        @if ($item->name != '')
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Peserta</button>
                    </form>
                </div>
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
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1000, // Wait 1 second after user stops typing
                ajax: {
                    url: '/search-peserta-full',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        // Add any additional parameters you need
                        return d;
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                    }
                },
                columns: [{
                        data: 'index',
                        name: 'index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'kontigen',
                        name: 'kontigen'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<button data-form-edit="' + row.id +
                                '" class="btn btn-primary" onclick="editPeserta(' + row.id +
                                ')" >Edit</button> ' +
                                '<button class="btn btn-danger delete-button" data-form-delete="' +
                                row.id + '">Delete</button>';
                        }
                    }
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });


            $('#adddata .js-example-basic-single').select2({
                dropdownParent: $('#adddata'),
                width: '100%'
            });


            $('#editData .js-example-basic-single').select2({
                dropdownParent: $('#editData'),
                width: '100%'
            });


            $('#adddata').on('shown.bs.modal', function() {
                $('#adddata .js-example-basic-single').select2({
                    dropdownParent: $('#adddata'),
                    width: '100%'
                });
            });

            $('#editData').on('shown.bs.modal', function() {
                $('#editData .js-example-basic-single').select2({
                    dropdownParent: $('#editData'),
                    width: '100%'
                });
            });
        });

        //onclick Buttons
        $('#clearData').on('click', () => {
            clearPeserta();
        });

        function editPeserta(value) {
            let button = $(`[data-form-edit="${value}"]`);
            let text = button.text();

            button.text('Loading');

            $.ajax({
                url: '{{ route('admin.getPeserta') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    id: value
                }),
                success: function(response) {
                    $('#idEdit').val(value);
                    $('#editPesertaName').val(response.name);
                    $('#editKelasPeserta').val(response.kelas);
                    $('#editKontigenPeserta').val(response.kontigen);
                    $('#editKategoriPeserta').val(response.kategori);

                    $('#editData').modal('show');
                    button.text(text);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Error loading participant data: ' + error);
                }
            })
        }

        //Function
        function clearPeserta() {
            fetch('{{ route('admin.clearPeserta') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success Clear Data');
                    window.location.reload();
                })
        }
    </script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}">
        < />
    @endpush
