    @extends('layout.master')

    @push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    @endpush

    @section('content')
    @php
        use App\category;
        $data = category::get();
    @endphp
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Category</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adddata">Tambah Data</button>
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Range Umur</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="tb-category">
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td> {{$item->name}}</td>
                                <td> {{ $item->keterangan }}</td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{$item->id}}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal{{$item->id}}">Delete</button>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.modifyCategory') }}" method="POST">
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
    
                            <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{route('admin.modifyCategory')}}" method="POST">
                                        @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editLabel">Edit Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="exampleInputUsername1" class="form-label">Nama</label>
                                                <input type="text" name="nama" id="nama" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Kelas" value="{{$item->name}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Range Umur</label>
                                                <input type="text" name="umur" id="umur" class="form-control" id="exampleInputEmail1" placeholder="Umur" value="{{$item->keterangan}}">
                                                <input type="hidden" name="idEdit" id="idEdit" value="{{$item->id}}">
                                                <input type="hidden" name="status" id="status" value="edit">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Edit Data</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <form class="forms-sample" method="POST" action="{{ route('admin.modifyCategory') }}" >
                        @csrf
                    <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleInputUsername1" class="form-label">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Kelas">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Range Umur</label>
                                <input type="text" name="umur" id="umur" class="form-control" id="exampleInputEmail1" placeholder="Umur">
                                <input type="hidden" name="status" id="status" value="add">
                            </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
