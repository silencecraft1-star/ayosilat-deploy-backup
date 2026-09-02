@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Peserta Kontigen: {{ $kontigen->kontigen ?? 'Tidak Diketahui' }}</h4>
                    <a href="{{ url('admin/panels/kontigen') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4 text-center">
                        <div class="col-md-4">
                            <div class="card text-dark bg-warning">
                                <div class="card-body py-2">
                                    <h5 class="card-title mb-1">Emas</h5>
                                    <h3 class="mb-0">{{ $emas }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-secondary">
                                <div class="card-body py-2">
                                    <h5 class="card-title mb-1">Perak</h5>
                                    <h3 class="mb-0">{{ $perak }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white" style="background-color: #cd7f32;">
                                <div class="card-body py-2">
                                    <h5 class="card-title mb-1">Perunggu</h5>
                                    <h3 class="mb-0">{{ $perunggu }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable-lihat" class="table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Kategori</th>
                                    <th>Medali</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function () {
            $('#datatable-lihat').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1000,
                ajax: {
                    url: '/search-peserta-kontigen',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: function (d) {
                        d.id_kontigen = {{ $kontigen->id ?? 0 }};
                        return d;
                    }
                },
                columns: [
                    { data: 'index', name: 'index', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'kelas', name: 'kelas' },
                    { data: 'category', name: 'category' },
                    { data: 'medali', name: 'medali', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush