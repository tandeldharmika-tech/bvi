<aside class="vh-100 py-3 px-1 text-white">
    <!-- User Role & Dashboard Header (optional) -->
    <!--
    <h5 class="text-light text-uppercase text-center">
        @foreach(Auth::user()->getRoleNames() as $role)
            {{ $role }}
        @endforeach
        Dashboard
    </h5>
    -->
<div class="sidebar d-flex flex-column p-0">
    <ul class="nav nav-pills flex-column mb-auto">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-chart-line icon-dashboard"></i> Dashboard
            </a>
        </li>

        <!-- Attendance Dropdown -->
        @can('view attendance')
        <li class="nav-item">
            <a href="{{ route('attendance.index') }}" class="nav-link text-white {{ request()->is('attendance*') ? 'active bg-primary' : '' }}">
                <i class="fa-regular fa-clock icon-attendance"></i> Attendance
            </a>
        </li>
        @endcan
        
        <!-- Users -->
        @can('manage users')
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link text-white {{ request()->routeIs('users.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-users icon-users"></i> Users
            </a>
        </li>
        @endcan

        <!-- Roles -->
        @can('manage roles')
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link text-white {{ request()->routeIs('roles.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-user-shield icon-roles"></i> Roles
            </a>
        </li>
        @endcan

        <!-- Tasks -->
        @can('view task')
        <li class="nav-item">
            <a href="{{ route('tasks.index') }}" class="nav-link text-white {{ request()->routeIs('tasks.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-list-check icon-tasks"></i> Tasks
            </a>
        </li>
        @endcan

        <!-- Projects -->
        @can('view projects')
        <li class="nav-item">
            <a href="{{ route('projects.index') }}" class="nav-link text-white {{ request()->routeIs('projects.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-diagram-project icon-projects"></i> Projects
            </a>
        </li>
        @endcan

        <!-- Leaves -->
        @canany(['view leaves'])
        <li class="nav-item">
            <a href="{{ route('leaves.index') }}" class="nav-link text-white {{ request()->routeIs('leaves.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-leaf icon-leaves"></i> Leaves
            </a>
        </li>
        @endcanany

        <!-- Holidays -->
        @canany(['view holidays'])
        <li class="nav-item">
            <a href="{{ route('holidays.index') }}"
            class="nav-link text-white {{ request()->routeIs('holidays.*') ? 'active bg-primary' : '' }}">
                <i class="fa-regular fa-calendar-days icon-holidays"></i> Holidays
            </a>
        </li>
        @endcanany

        @canany(['view calendar'])
        <li class="nav-item">
            <a href="{{ route('calendar.index') }}"
            class="nav-link text-white {{ request()->routeIs('calendar.index') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-calendar-week icon-calendar"></i> Calendar
            </a>
        </li>
        @endcanany

        <!-- CRM -->
        @can('view crm')
        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="fa-solid fa-magnet icon-crm"></i> CRM
            </a>
        </li>
        @endcan

        <!-- Payroll -->
        @can('view payroll')
        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="fa-solid fa-coins icon-payroll"></i> Payroll
            </a>
        </li>
        @endcan

        <!-- Recruitment -->
        @can('view recruitment')
        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                📋 Recruitment
            </a>
        </li>
        @endcan

        <!-- Departments -->
        @can('view departments')
        <li class="nav-item">
            <a href="{{ route('departments.index') }}" class="nav-link text-white {{ request()->routeIs('departments.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-building icon-departments"></i> Departments
            </a>
        </li>
        @endcan

        <!-- Designations -->
        @can('view designation')
        <li class="nav-item">
            <a href="{{ route('designations.index') }}" class="nav-link text-white {{ request()->routeIs('designations.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-file-signature icon-designations"></i> Designations
            </a>
        </li>
        @endcan


        <!-- Permissions -->
        @can('manage permissions')
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link text-white {{ request()->routeIs('permissions.*') ? 'active bg-primary' : '' }}">
                <i class="fa-solid fa-key icon-permissions"></i> Permissions
            </a>
        </li>
        @endcan

        <!-- Settings -->
        @can('manage settings')
        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="fa-solid fa-gear icon-settings"></i> Settings
            </a>
        </li>
        @endcan

        <!-- Logout -->
        <li class="nav-item mt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">🚪 Logout</button>
            </form>
        </li>

    </ul>
</div>
</aside>
