<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="">
	<meta name="author" content="NustraStudio">
	<meta name="keywords" content="S">

  <title>Nustra Studio</title>

  <!-- Fonts -->
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <script src="{{ asset('js/app.js') }}"></script>
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
    <div class="page-wrapper full-page">
      @yield('content')
      @if (session('success'))
      <script>
          Swal.fire({
              icon: 'success',
              title: 'Sukses',
              text: '{{ session('success') }}'
          });
      </script>
      @endif
      @if (session('error'))
          <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: '{{ session('error') }}'
              });
          </script>
          
  @endif
    </div>
  </div>
      <script>
        // Tambahkan event listener untuk tombol atau tautan
        document.addEventListener('DOMContentLoaded', function () {
            var deleteButtons = document.getElementsByClassName('delete-button');
      
            Array.from(deleteButtons).forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var formId = this.getAttribute('data-form-delete');
      
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Tindakan ini tidak dapat diurungkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirimkan request penghapusan
                            document.getElementById('form-delete-' + formId).submit();
                        }
                    });
                });
            });
        });
      </script>
    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>