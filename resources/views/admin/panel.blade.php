@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
  @php
      use App\PersertaModel;
      use App\Setting;
      $data_setting = Setting::first();
      $data_perserta = PersertaModel::get();
  @endphp
  <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <a href="{{ url('/admin/panels/rekap-medali') }}">
              <button class="btn-primary btn">
                Rekap Medali Tanding
              </button>
            </a>
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