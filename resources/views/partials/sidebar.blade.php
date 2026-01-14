<aside class="page-sidebar"> 


    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
    <div class="main-sidebar" id="main-sidebar">
    <ul class="sidebar-menu" id="simple-bar">
        <li class="pin-title sidebar-main-title">  
        <div> 
            <h5 class="sidebar-title f-w-700">Pinned</h5>
        </div>
        </li>
        <li class="sidebar-main-title">
        <div>
            <h5 class="lan-1 f-w-700 sidebar-title">General</h5>
        </div>
        </li>
        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="{{ route('admin.dashboard') }}"> 
            <svg class="stroke-icon">
            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Home-dashboard') }}"></use>
            </svg>
            <h6>Dashboard</h6></a>
        </li>
        <li class="sidebar-list"> <i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="javascript:void(0)">
            <svg class="stroke-icon">
            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
            </svg>
            <h6 class="f-w-600">Users</h6><i class="iconly-Arrow-Right-2 icli"> </i></a>
        <ul class="sidebar-submenu">
            <li> <a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
            <li> <a href="{{ route('admin.practitioners.index') }}">Practitioners</a></li>
            <li> <a href="{{ route('admin.clients.index') }}">Clients</a></li>
        </ul>
        </li>

        <li class="sidebar-list"> <i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="javascript:void(0)">
            <svg class="stroke-icon">
            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Setting') }}"></use>
            </svg>
            <h6 class="f-w-600">Master Settings</h6><i class="iconly-Arrow-Right-2 icli"> </i></a>
        <ul class="sidebar-submenu">
            <li> <a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li> <a href="{{ route('admin.master-data.index', 'specializations') }}">Specializations</a></li>
            <li> <a href="{{ route('admin.master-data.index', 'expertises') }}">Ayurveda Expertises</a></li>
            <li> <a href="{{ route('admin.master-data.index', 'conditions') }}">Health Conditions</a></li>
            <li> <a href="{{ route('admin.master-data.index', 'therapies') }}">External Therapies</a></li>
        </ul>
        </li>
    </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</aside>