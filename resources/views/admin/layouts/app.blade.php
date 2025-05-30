<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spike Free Bootstrap Admin Template by WrapPixel</title>
    @include('admin.layouts.includes.styles')
    @stack('after-styles')

</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        @include('admin.layouts.includes.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">

            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    <!--  Header Start -->
                    @include('admin.layouts.includes.navbar')
                    <!--  Header End -->

                    <!--  Row 1 -->
                    {{ $slot }}
                    @include('admin.layouts.includes.footer')
                </div>
            </div>
        </div>
    </div>
    @stack('modal-section')
    @include('admin.layouts.includes.scripts')
    @stack('after-scripts')
</body>

</html>
