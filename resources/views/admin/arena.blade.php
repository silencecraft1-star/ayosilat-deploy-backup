@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
    @endpush

@section('content')
    @php
        use App\PersertaModel;
        use App\Setting;
        use App\arena;
        use App\kelas;
        use App\juri;
        $data_kelas = kelas::all();
        $data = arena::all();
        $data_setting = Setting::first();
        $data_perserta = PersertaModel::get();
        $data_juri = juri::orderBy('name', 'ASC')->get();
        $jumlahJuri = 8;

    @endphp
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Arena</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adddata">Tambah
                        Data</button>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="mb-3">
                                <div class="w-full bg-success-subtle text-success px-3 py-2 rounded">
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tb-category">
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td> {{ $item->name }}</td>
                                            <td>
                                                <div class="text-end">
                                                    <button class="btn btn-primary btn-sm edit-call" id="edit{{ $item->id }}"
                                                        key="{{ $item->id }}">Edit</button>
                                                    <a href='{{ url("/redirect/?arena=$item->id&role=timer") }}'
                                                        class="btn btn-warning btn-sm">Timer</a>
                                                    <a class="btn btn-success btn-sm"
                                                        href='{{ url("redirect?arena=$item->id&role=arena") }}'>Panel</a>
                                                    <button class="btn btn-danger btn-sm delete-button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $item->id }}">Delete</button>
                                                </div>
                                        </tr>
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="deleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('admin.arenamodify') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteLabel">Hapus Item</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="btn-close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Yakin Hapus Data ini?
                                                            <input type="hidden" name="id" id="idHapus" value="{{ $item->id }}">
                                                            <input type="hidden" name="status" id="status" value="hapus">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Hapus
                                                                Data</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Add Data Arena</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample" method="post" action="{{ route('arena.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputName" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control w-100" autocomplete="off"
                                    placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputName" class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control w-100" autocomplete="off"
                                    placeholder="GEL 1...">
                            </div>
                            @for($i = 1; $i <= $jumlahJuri; $i++)
                                <div class="mb-3">
                                    <div class="mb-1">
                                        <label for="exampleInputName" class="form-label">Juri {{$i}} @if($i >= 4) <span
                                        class="text-danger">*Hanya Untuk Seni</span> @endif </label>
                                    </div>
                                    <select name="juri_{{$i}}" class="select2-basic">
                                        @foreach ($data_juri as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endfor
                            {{-- <div class="mb-3">
                                <label for="exampleInputName" class="form-label">Juri 2</label>
                                <select name="juri_2" class="form-control select2-basic">
                                    @foreach ($data_juri as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputName" class="form-label">Juri 3</label>
                                <select name="juri_3" class="form-control select2-basic">
                                    @foreach ($data_juri as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {{-- @php
                            $juriSeni = 4;
                            @endphp
                            @for ($i = 4; $i <= $juriSeni; $i++) <div class="mb-3">
                                <label for="exampleInputName" class="form-label">Juri {{ $i }} <span
                                        class="text-danger">*Hanya Untuk Seni</span></label>
                                <select name="juri_{{ $i }}" class="form-control select2-basic">
                                    @foreach ($data_juri as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                    </div>
                    @endfor --}}
                    <!-- ... Other form elements ... -->
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Status</label>
                        <select name="kelas" class="form-control select2-basic">
                            <option value="Tanding">Tanding</option>
                            <option value="Solo_Kreatif">Seni Solo Kreatif</option>
                            <option value="Ganda_Kreatif">Seni Ganda Kreatif</option>
                            <option value="Tunggal">Seni Tunggal</option>
                            <option value="Group">Seni Group</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Arena</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="post" action="{{ route('admin.arenamodify') }}">
                        @csrf
                        <input type="hidden" name="status" value="edit">
                        <input type="hidden" id="idEdit" name="id" value="id">
                        <div class="mb-3">
                            <label for="exampleInputName" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Name"
                                id="editName">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputName" class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" autocomplete="off"
                                placeholder="GEL 1..." id="editKeterangan">
                        </div>
                        @for($i = 1; $i <= $jumlahJuri; $i++)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                        <label for="exampleInputName" class="form-label">Juri {{ $i }} @if($i >= 4) <span
                                        class="text-danger">* Hanya untuk Seni </span> @endif </label>
                                        <select id="edit_juri_{{$i}}" name="juri_{{$i}}" class="form-control select2-basic">
                                            @foreach ($data_juri as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endfor
                        {{-- <div class="mb-3">
                            <div class="row">
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri 2</label>
                                    <input type="text" class="form-control" readonly name="" id="juri_2_data">
                                </div>
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri 2 Baru</label>
                                    <select name="juri_2" class="form-control select2-basic">
                                        @foreach ($data_juri as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri 3</label>
                                    <input type="text" class="form-control" readonly name="" id="juri_3_data">
                                </div>
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri 3 Baru</label>
                                    <select name="juri_3" class="form-control select2-basic">
                                        @foreach ($data_juri as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @php
                        $juriSeni = 4;
                        @endphp
                        @for ($i = 4; $i <= $juriSeni; $i++) <div class="mb-3">
                            <div class="row">
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri {{ $i }}
                                        <span class="text-danger">*Hanya Untuk Seni</span></label>
                                    <input type="text" class="form-control" readonly name="" id="juri_4_data">
                                </div>
                                <div class="col">
                                    <label for="exampleInputName" class="form-label">Juri 4 Baru</label>
                                    <select name="juri_{{ $i }}" class="form-control select2-basic">
                                        @foreach ($data_juri as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                </div>
                @endfor --}}
                <!-- ... Other form elements ... -->
                {{-- <div class="mb-3">
                    <label for="exampleInputName" class="form-label">Status</label>
                    <select name="kelas" class="form-control">
                        <option value="Tanding">Tanding</option>
                        <option value="Solo_Kreatif">Seni Solo Kreatif</option>
                        <option value="Ganda_Kreatif">Seni Ganda Kreatif</option>
                        <option value="Tunggal">Seni Tunggal</option>
                        <option value="Group">Seni Group</option>
                    </select>
                </div> --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-basic').css({
                "width": "100%"
            });

            $('#adddata .select2-basic').select2({
                dropdownParent: $('#adddata'),
            });

            $('#editData .select2-basic').select2({
                dropdownParent: $('#editData'),
            });

        });
        $('.edit-call').on('click', function () {
            var button = $(this);
            var originalText = button.text();
            button.text('Loading...');
            $('#idEdit').val(button.attr('key'));

            $.ajax({
                url: "{{ route('admin.getarena') }}",
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data: JSON.stringify({
                    arena: button.attr('key'),
                    action: 'get_data'
                }),
                success: function (response) {
                    console.log(response.juri_1);
                    $('#editName').val(response.judul);
                    $('#editKeterangan').val(response.keterangan);
                    $("#edit_juri_1").val(response.juri_1);
                    $("#edit_juri_2").val(response.juri_2);
                    $("#edit_juri_3").val(response.juri_3);
                    $("#edit_juri_4").val(response.juri_4);
                    $("#edit_juri_5").val(response.juri_5);
                    $("#edit_juri_6").val(response.juri_6);
                    $("#edit_juri_7").val(response.juri_7);
                    $("#edit_juri_8").val(response.juri_8);

                    $('#editData').modal('show');
                    button.text(originalText);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    button.text(originalText);
                    alert('Error loading data. Please try again.');
                }
            });
        });
    </script>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush