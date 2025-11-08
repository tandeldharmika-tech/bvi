<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'BVInfy'))</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://14ef66bcf523.ngrok-free.app/css/app.css">
    <script src="https://14ef66bcf523.ngrok-free.app/js/app.js"></script> -->
</head>
<style>
    /* ===== ICON COLORS ===== */
.icon-dashboard      { color: #0dcaf0; } /* Light Blue */
.icon-attendance     { color: #20c997; } /* Teal */
.icon-tasks          { color: #ffc107; } /* Yellow */
.icon-projects       { color: #fd7e14; } /* Orange */
.icon-leaves         { color: #198754; } /* Green */
.icon-holidays       { color: #0d6efd; } /* Blue */
.icon-calendar       { color: #6f42c1; } /* Purple */
.icon-crm            { color: #e83e8c; } /* Pink */
.icon-payroll        { color: #f39c12; } /* Gold */
.icon-users          { color: #17a2b8; } /* Cyan */
.icon-departments    { color: #6610f2; } /* Indigo */
.icon-designations   { color: #d63384; } /* Rose */
.icon-roles          { color: #28a745; } /* Green */
.icon-permissions    { color: #dc3545; } /* Red */
.icon-settings       { color: #adb5bd; } /* Gray */
.icon-logout         { color: #ff4d4d; } /* Bright Red */

.sidebar .nav-link i {
    transition: transform 0.3s, color 0.3s;
}

.sidebar .nav-link:hover i {
    transform: scale(1.2);
    filter: brightness(1.3);
}

</style>
<body class="font-sans antialiased">
    <div class="container-fluid dark:bg-gray-800">
        @include('layouts.navigation')
        <div class="row min-vh-100">

            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 border-end p-0">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-10 col-md-9 p-0">
                <!-- Page Heading -->
                @hasSection('header')
                <div class="shadow-sm p-3 border-bottom">
                    @yield('header')
                </div>
                @endif

                <!-- Page Content -->
                <div class="p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @yield('scripts')
    @stack('scripts')
    <!-- Bootstrap JS (optional if you use dropdowns/modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>