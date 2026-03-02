<nav class="sidebar">
  <div class="sidebar-header">
    <a href="/admin/panel" class="sidebar-brand">
      Ayo<span>Silat</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>

      @if ($status == "arena" )
      <li class="nav-item {{ active_class(['/admin/panels/kelas']) }}">
        <a href="{{url("redirect?arena=$arena&role=arena")}}" class="nav-link">
          <i class="link-icon" data-feather="home"></i>
          <span class="link-title">Home</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panel']) }}">
        <a href="{{url("redirect?arena=$arena&role=arena-jadwal")}}" class="nav-link">
          <i class="link-icon" data-feather="calendar"></i>
          <span class="link-title">Jadwal</span>
        </a>
      </li>
      @endif
      @if ($status =="admin")
      <li class="nav-item {{ active_class(['/admin/panel']) }}">
        <a href="{{ url('/admin/panel') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/kelas']) }}">
        <a href="{{ url('/admin/panels/kelas') }}" class="nav-link">
          <i class="link-icon" data-feather="home"></i>
          <span class="link-title">Kelas</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/perserta']) }}">
        <a href="{{ url('/admin/panels/perserta') }}" class="nav-link">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Perserta</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/kontigen']) }}">
        <a href="{{ url('/admin/panels/kontigen') }}" class="nav-link">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title">Kontigen</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/juri']) }}">
        <a href="{{ url('/admin/panels/juri') }}" class="nav-link">
          <i class="link-icon" data-feather="user"></i>
          <span class="link-title">Juri</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/arena']) }}">
        <a href="{{ url('/admin/panels/arena') }}" class="nav-link">
          <i class="link-icon" data-feather="inbox"></i>
          <span class="link-title">Arena</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/category']) }}">
        <a href="{{ url('/admin/panels/category') }}" class="nav-link">
          <i class="link-icon" data-feather="inbox"></i>
          <span class="link-title">Category</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/admin/panels/category']) }}">
        <a href="{{ url('/admin/panels/settings') }}" class="nav-link">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Setting</span>
        </a>
      </li>
      @endif
    </ul>
  </div>
</nav>
<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Sidebar:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" checked>
          Light
        </label>
      </div>
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark">
          Dark
        </label>
      </div>
    </div>
  </div>
</nav>