

@include('layouts.htmlheader')
@include('layouts.scripts')
<html>
<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">

         @include('layouts.header')

         @include('layouts.leftsidebar')

            <!-- Page wrapper  -->
            <div class="page-wrapper">

                @yield('breadcrumbs')
                @yield('content')

                @include('layouts.footer')

            </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->


    @yield('scripts')
</body>

</html>