<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ env('APP_URL') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Home</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="modal_error('Fitur belum tersedia')">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Agenda Mendatang</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="modal_error('Fitur belum tersedia')">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Cuti</p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="#" class="nav-link" onclick="modal_error('Fitur belum tersedia')">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Surat Tugas</p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('rekap.detail_user') }}" class="nav-link">
              <i class="nav-icon far fa-circle text-success"></i>
              <p>Rekap</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="modal_error('Fitur belum tersedia')">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Monitoring Kinerja</p>
            </a>
        </li>
        <hr style="border: 1px solid #444; margin: 0;">

        <!-- Logout Button -->
        <li class="nav-item mt-auto">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                <p>Logout</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
