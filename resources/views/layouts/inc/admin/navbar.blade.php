<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" >
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="">
        <img src="{{ asset('admin/images/screen.png') }}" alt="logo" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="">
        <img src="{{ asset('admin/images/screen.png') }}" alt="logo" />
      </a>
    </div>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-navv">
      <li class="nav-item fw-semibold d-none d-lg-block ms-0">
          <h2 class="welcome-text">Saytda hozir, <span class="text-black fw-bold">{{ auth()->user()->firstName }}</span></h2>
          @if (auth()->user() && auth()->user()->hasRole('Super Admin'))
            <h5 class="welcome-sub-text">IMRS xodimlari <strong>KPI</strong>ni xisoblash sayti <b>Super Admin</b> paneli</h5>
          @else
            <h5 class="welcome-sub-text">IMRS xodimlari <strong>KPI</strong>ni xisoblash sayti <b>Admin</b> paneli</h5>
          @endif
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
      </li>
      <li class="nav-item">
        <form class="search-form" action="#">
          <i class="icon-search"></i>
          <input type="search" class="form-control" placeholder="Search Here" title="Search here">
        </form>
      </li>
      <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="{{ asset('admin/images/user.png') }}" alt="Profile image"><span class="fw-bold mx-1">{{ Auth::user()->name }}</span></a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          
          <div class="dropdown-header text-center">
            <img class="img-xs rounded-circle mb-1" src="{{ asset('admin/images/user.png') }}" alt="Profile image"> </a>
            <p class="mb-1 mt-3 fw-semibold">{{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</p>
            <p class="fw-light text-muted mb-0">{{ auth()->user()->email }}</p>
          </div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
              <a class="dropdown-item" type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>{{ __('Log Out') }}
              </a>
          </form>      
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>