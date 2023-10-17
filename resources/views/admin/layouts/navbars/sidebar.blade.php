<div class="sidebar show" id="sidebar" data-color="purple" data-image="{{ asset('img/sidebar-1.jpg') }}">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <a href="{{ url('admin') }}" class="simple-text logo-normal">
            {{ __('Steel24') }}
        </a>
    </div>
    <div class="sidebar-wrapper" id="scroll-container">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">

                <a class="nav-link" href="{{ url('admin') }}">
                    <i class="material-icons">home</i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            {{-- <li class="nav-item{{ $activePage == 'users' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Users') }}</p>
                </a>
            </li> --}}
            <li class="nav-item{{ $activePage == 'Notification' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/notification') }}">
                    @php 
                        $unreadNotification = App\Helper\NotificationHelper::countNotification();
                    @endphp 
                    <i class="material-icons">notifications</i>
                    <p class="notification-link">{{ __('Notification') }}   @if($unreadNotification > 0) <span class="notification-blink @if(request()->is('admin/notification')) active @endif">{{$unreadNotification}}</span> @endif </p>
                </a>
            </li>
            {{-- @dd($activePage ) --}}

            <li class="nav-item{{ $activePage == 'customers' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    <i class="material-icons">person</i>
                    <p>{{ __('Customers') }}</p>
                </a>
            </li>
            {{-- <li class="nav-item{{ $activePage == 'materials' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/materials') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Materials') }}</p>
                </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'materialFiles' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/materialFiles/create') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Upload Images') }}</p>
                </a>
            </li> --}}

            <li class="nav-item{{ $activePage == 'categories' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/categories') }}">
                    <i class="material-icons">library_books</i>
                    <p>{{ __('Lot Categories') }}</p>
                </a>
            </li>
            {{-- @dd($activePage ) --}}

            <li class="nav-item{{ $activePage == 'lots' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/lots') }}">
                    <i class="material-icons">show_chart</i>
                    <p>{{ __('Lots') }}</p>
                </a>
            </li>

            <li class="nav-item{{ $activePage == 'Payment Plan' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/payment_plan') }}">
                    <i class="material-icons">payment</i>
                    <p>{{ __('Payments Plan') }}</p>
                </a>
            </li>


            <li class="nav-item{{ $activePage == 'Live Lots' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/live_lots') }}">
                    <i class="material-icons">lens</i>
                    <p>{{ __('Live Lots') }}</p>
                </a>
            </li>

            <li class="nav-item{{ $activePage == '' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/complete_lots') }}">
                    <i class="material-icons">check_circle</i>
                    <p>{{ __('Completed Lots') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'payments' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.payments.index') }}">
                    <i class="material-icons">payment</i>
                    <p>{{ __('Payments') }}</p>
                </a>
            </li>

            <li class="nav-item{{ $activePage == 'productimages' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/productimage') }}">
                    <i class="material-icons">image</i>
                    <p>{{ __('Product Images') }}</p>
                </a>
            </li>




            <!-- <div class="dropdown-divider"></div> -->

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="material-icons">exit_to_app</i><p>{{ __('Log out') }}</p>
                    
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    const element = document.getElementById("scroll-container");
const ps = new PerfectScrollbar(element);
    </script>