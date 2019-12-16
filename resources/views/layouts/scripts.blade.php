<script src="{{ asset('ela/js/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('ela/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('ela/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('ela/js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/calendar-2/moment.latest.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/calendar-2/semantic.ui.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/calendar-2/prism.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/calendar-2/pignose.calendar.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/calendar-2/pignose.init.js') }}"></script>
<script src="{{ asset('ela/js/lib/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('ela/js/lib/owl-carousel/owl.carousel-init.js') }}"></script>
<script src="{{ asset('ela/js/scripts.js') }}"></script>
<script src="{{ asset('ela/js/custom.min.js') }}"></script>
<script src="{{ asset('ela/js/extfunctions.js') }}"></script>
<script>
    const _token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': _token},
        type: 'POST',
        datatype: 'JSON',
        error: function() {
            showErrorModal('Сервер временно недоступен');
        }
    });
</script>