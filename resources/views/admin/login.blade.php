<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
  @php
    use App\Setting;
    $settingData = Setting::where('keterangan', 'admin-setting')->first();
    $imgData = $settingData->partai ?? "";
  @endphp
</head>

<body>

  <!-- Login Section -->
  <section>
    <div class=" bg-gradient-to-r from-blue-500 to-blue-100 h-svh flex items-center justify-center">
      <div
        class="border bg-white font-sans shadow-lg shadow-gray-400 border-blue-500 w-full xl:w-1/4 rounded-lg py-8 px-10 mx-5">
        <div class="grid grid-cols-5 mb-2">
          <div class=" col-span-4">
            <div class="text-2xl">Login Ayo<span class="text-blue-500">Silat</span></div>
            <div class="mb-3">
              <small>Silakan login untuk masuk ke website</small>
            </div>
          </div>
          <div class="col-span-1 flex justify-end items-center xl:items-start">
            <img src="{{asset("assets/Assets/uploads/$imgData")}}" class="w-12" alt="">
          </div>
        </div>
        <div class="mb-6">
          <div class=" mb-2">
            Username
          </div>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <input class="border border-gray-400 ps-2 rounded active:border-blue-500 w-full py-2" type="text"
              placeholder="Masukan Username..." name="username" id="">
        </div>
        <div class="mb-6">
          <div class="mb-2">
            Password
          </div>
          <input class="border border-gray-400 ps-2 rounded active:border-blue-500 w-full py-2" type="password"
            placeholder="Masukan Password..." name="password" id="">
        </div>

        <div class="mb-3">
          <button type="submit"
            class=" w-full text-center text-white  bg-gradient-to-r from-blue-500 to-blue-300 hover:from-blue-400 hover:to-blue-200 active:from-blue-500 active:to-blue-600 rounded-lg py-2">
            <div class="h-full w-full flex justify-center">
              <div class="relative w-24">
                <i class="ti ti-login absolute top-1 left-2"></i>
                Login
              </div>
            </div>
          </button>
          </form>
        </div>
        <div class="text-end">
          <small>Created By NustraGroup 2024</small>
        </div>
      </div>
    </div>
  </section>

</body>

</html>