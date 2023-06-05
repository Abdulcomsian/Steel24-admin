<div class="wrapper ">
  @include('seller.layouts.navbars.sidebar')
  <div class="main-panel">
    @include('seller.layouts.navbars.navs.auth')
      @yield('content')
    @include('seller.layouts.footers.auth')
  </div>
</div>
