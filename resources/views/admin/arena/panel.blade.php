@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
  @php
      use App\PersertaModel;
      use App\Setting;
      $data_setting = Setting::where('arena',$arena)->first();
      $data_perserta = PersertaModel::get();
  @endphp
  <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <form method="post" action="{{ route('panel.store') }}">
              @csrf
              <div class="mb-3">
                <label for="exampleInputText1" class="form-label">Judul</label>
                <input type="text" value="{{$data_setting->judul}}"  class="form-control" id="exampleInputText1" name="judul"  placeholder="Enter Name">
              </div>
              <div class="mb-3">
                <label for="exampleFormControlSelect1" class="form-label">Babak</label>
                <select class="form-select" name="babak" id="exampleFormControlSelect1">
                  <option {{ 1 == $data_setting->babak ? 'selected' : '' }} value="1">1</option>
                  <option value="2"  {{ 2 == $data_setting->babak ? 'selected' : '' }}>2</option>
                  <option value="3"  {{ 3 == $data_setting->babak ? 'selected' : '' }}>3</option>
                </select>
              </div>
          <button class="btn btn-primary" type="submit">Update</button>
            </form>
          </div>
        </div>
      </div>
  </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush