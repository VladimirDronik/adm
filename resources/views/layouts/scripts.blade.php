<!-- All Jquery -->
<script src="{{ asset('ela/js/lib/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('ela/js/lib/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('ela/js/jquery.slimscroll.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('ela/js/sidebarmenu.js') }}"></script>
<!--stickey kit -->
<script src="{{ asset('ela/js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<!--Custom JavaScript -->

<!-- Amchart -->
{{--<script src="/js/lib/morris-chart/raphael-min.js"></script>--}}
{{--<script src="/js/lib/morris-chart/morris.js"></script>--}}
{{--<script src="/js/lib/morris-chart/dashboard1-init.js"></script>--}}

<script src="{{ asset('ela/js/lib/calendar-2/moment.latest.min.js') }}"></script>
<!-- scripit init-->
<script src="{{ asset('ela/js/lib/calendar-2/semantic.ui.min.js') }}"></script>
<!-- scripit init-->
<script src="{{ asset('ela/js/lib/calendar-2/prism.min.js') }}"></script>
<!-- scripit init-->
<script src="{{ asset('ela/js/lib/calendar-2/pignose.calendar.min.js') }}"></script>
<!-- scripit init-->
<script src="{{ asset('ela/js/lib/calendar-2/pignose.init.js') }}"></script>

<script src="{{ asset('ela/js/lib/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/owl-carousel/owl.carousel-init.js') }}"></script>
<script src="{{ asset('ela/js/scripts.js') }}"></script>
<!-- scripit init-->

<script src="{{ asset('ela/js/custom.min.js') }}"></script>
<script src="{{ asset('ela/js/extfunctions.js') }}"></script>

<script>
    let _token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': _token},
        type: 'POST',
        datatype: 'JSON',
        error: function() {
            showErrorModal('Сервер временно недоступен');
        }
    });
</script>