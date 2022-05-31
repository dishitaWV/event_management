<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Crud</title>
    <link rel="stylesheet" href="https://www.uh.edu/css/refresh/bootstrap.css">
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<body>
<div class="container" style="margin-top: 5px">
@yield('content')
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>
<script src="https://uh.edu/js/bootstrap.min.js"></script>
{{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        // $( ".custom_date_picker" ).datepicker();

        $( ".custom_date_picker" ).datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $('input[name="search_dates"]').daterangepicker();

        $('body').on('click', '#deleteEvent', function (e) {
            if(confirm('Are you sure you wish to delete this event?')){
                $("#deleteEventForm").submit();
            }
        });

        $('body').on('click', '#searchBtn', function (e) {
            // console.log($('#search_dates').val());
            var start_date = $('#search_dates').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end_date = $('#search_dates').data('daterangepicker').endDate.format('YYYY-MM-DD');
            // console.log("start_date:",start_date);
            // console.log("end_date:",end_date);
            $.ajax({
                type: 'POST',
                url: "{{ url('search_event') }}",
                data: {"_token": "{{csrf_token()}}", start_date: start_date, end_date: end_date},
                success: function (res) {
                    $("#event_table tbody").html(res['html']);
                },
                error: function (data) {

                }
            });
        });

        $('body').on('click', '#download_pdf_btn', function (e) {
            var start_date = $('#search_dates').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end_date = $('#search_dates').data('daterangepicker').endDate.format('YYYY-MM-DD');
            var url = "{{ url('generate_pdf') }}" + "/" + start_date + "/" + end_date;
            window.open(url, "_blank");
        });
        /*$('.date_range_picker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });*/
    })

</script>
@yield('js')
</body>
</html>


