<div class="sidebar" data-color="purple" data-image="{{ asset('img/sidebar-1.jpg') }}">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <a href="{{ url('admin') }}" class="simple-text logo-normal">
            {{ __('Steel24') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">

                <a class="nav-link" href="{{ url('admin') }}">
                    <i class="material-icons"></i>
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
                    <i class="material-icons"></i>
                    <p>{{ __('Notification') }}</p>
                </a>
            </li>
            {{-- @dd($activePage ) --}}

            <li class="nav-item{{ $activePage == 'customers' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    <i class="material-icons"></i>
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
                    <i class="material-icons"></i>
                    <p>{{ __('Lot Categories') }}</p>
                </a>
            </li>
            {{-- @dd($activePage ) --}}

            <li class="nav-item{{ $activePage == 'lots' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/lots') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Lots') }}</p>
                </a>
            </li>

            <li class="nav-item{{ $activePage == 'Payment Plan' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/payment_plan') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Payments Plan') }}</p>
                </a>
            </li>



            <li class="nav-item{{ $activePage == 'Live Lots' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/live_lots') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Live Lots') }}</p>
                </a>
            </li>

            <li class="nav-item{{ $activePage == '' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('admin/complete_lots') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Completed Lots') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'payments' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.payments.index') }}">
                    <i class="material-icons"></i>
                    <p>{{ __('Payments') }}</p>
                </a>
            </li>

            <div class="dropdown-divider"></div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}
                    <i class="material-icons"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
