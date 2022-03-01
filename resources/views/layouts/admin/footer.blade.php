<!--begin::Global Config(global config for global JS scripts)-->
<script>
    var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };
</script>
<!--end::Global Config-->
<script>
    // "global" vars for icons
    //initializing sos_id
    var SOS_ID = null;

    const ICONS = {
        bike: '{{asset('assets/admin/icons/rider_map.svg')}}',
        car: '{{asset('assets/admin/icons/car_map.svg')}}',
        rickshaw: '{{asset('assets/admin/icons/rickshaw.png')}}',
    }

    const colorCodes = {
        'pending' : '#E3B809', //yellow
        'accepted': '#FA3AA2', //pink
        'running': 'blue',
        'completed': '#57FA0D', //green
        'cancelled': 'red'
    }

    const markerIcons = {
        'pending': '{{asset('assets/admin/icons/pending.png')}}',
        'accepted': '{{asset('assets/admin/icons/accepted.png')}}',
        'running': '{{asset('assets/admin/icons/running.png')}}',
        'completed': '{{asset('assets/admin/icons/completed.png')}}',
        'cancelled': '{{asset('assets/admin/icons/cancelled.png')}}'
    }
</script>
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('assets/admin/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/admin/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
<!--end::Global Theme Bundle-->
<script src="{{asset('assets/admin/plugins/custom/toastr/toastr.init.js')}}"></script>

{!! Toastr::render() !!}
@yield('page-specific-scripts')
<!-- toastr and notification -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="{{asset('/js/sos_events_listener.js')}}"></script>
<script src="{{asset('/js/booking_timed_out_listener.js')}}"></script> --}}

<script src="{{asset('/js/pusher_events_listener.js')}}"></script>

<!-- custom scripts -->
<script src="{{asset('assets/admin/js/my-script.js')}}"></script>
{{--
<script>
    var last_check = null;
    const audio = new Audio('/notification/notification.mp3');

  
    function getNotification() {
        $.ajax({url: "{{route('admin.notification.web')}}?last_check="+last_check, success: function(result){
            renderNotification(result.bookingLog, "#bookingNotification", "New Booking Notifications");
            renderNotification(result.eventLog, "#eventNotification", "New Event Notifications");
            last_check = result.bookingLog.last_check;
        }});
    }

    function renderNotification(result, notificationId, toastrMsg) {
        if (result.newNotification && last_check !== null) {
            $(notificationId).prepend(result.view);
            if(notificationId !== "#logNotification"){
                toastr.success(toastrMsg);
                audio.play();
            }
        } else if (result.newNotification && last_check === null) {
            $(notificationId).html(result.view);
        }
    }

    getNotification();
    setInterval(getNotification, 5000);
</script>
--}}

<script>
    //     const audio = new Audio('/notification/notification.mp3');
    //     audio.muted = true;
    // audio.play();

    // var audio = document.createElement("AUDIO")
    // document.body.appendChild(audio);
    // audio.src = "/notification/notification.mp3"

    // document.body.addEventListener("mousemove", function () {
    //     audio.play()
    // })

    
    $('#btnPlayNotificationAudio').hide();
    var x = document.getElementById("notificationAudio"); 
    function playNotificationAudio() { 
        x.play(); 
    } 
    
    $('#btnPlaySosAudio').hide();
    var y = document.getElementById("sosAudio"); 
    function playSosAudio() { 
        y.play(); 
    } 
    $('#btnPlayEventAudio').hide();
    var z = document.getElementById("eventAudio"); 
    function playEventAudio() { 
        z.play(); 
    } 
    

    function getNotification(notification_type='all') {
        var url = "{{route('admin.notification.latest',':notification_type') }}".replace(':notification_type',notification_type)
        $.ajax({
            url: url,
            success: function(response){

                var total_notification_count = ( $('#notificationCount').html()) ?  parseInt( $('#notificationCount').html()) : 0;
                var booking_notification_count = ( $('#bookingNotificationCount').html()) ?  parseInt(  $('#bookingNotificationCount').html()) : 0;
                var sos_notification_count = ( $('#sosNotificationCount').html()) ?  parseInt(  $('#sosNotificationCount').html()) : 0;
                var event_notification_count = ( $('#eventNotificationCount').html()) ?   parseInt( $('#eventNotificationCount').html()) : 0;

                // if($('#notificationCount').html())
                // {
                //     console.log('yes:, ',$('#notificationCount').html());
                // }else {console.log('no');}
                // console.log("HTML: ",$('#notificationCount').html());

                if(response.notification_section != null)
                {
                    $('#bookingNotification').html( response.notification_section );
                }

                if(response.sos_section != null)
                {
                    $('#sosNotification').html( response.sos_section );
                }
                    
                if(response.event_section != null)
                {
                    $('#eventNotification').html( response.event_section );
                }
                   

                console.log('NOTIFICATION ALERT: ',response.notification_alert,$('#notificationCount').html());
                console.log('SOS ALERT: ',response.sos_alert);
                console.log('EVENT ALERT: ',response.event_alert);

                var total_notifications = 0;
                if(response.notification_type == "notification")
                {
                    total_notifications =  parseInt(response.notification_alert) +total_notification_count - booking_notification_count;
                    $('#bookingNotificationCount').html( ( response.notification_alert>0) ? response.notification_alert : "");
                }
                else if(response.notification_type == "sos")
                {
                    total_notifications =  parseInt(response.sos_alert) +total_notification_count - sos_notification_count;
                   $('#sosNotificationCount').html(( response.sos_alert>0) ? response.sos_alert : "");
                }
                else if(response.notification_type == "event")
                {
                    total_notifications =  parseInt(response.event_alert) +total_notification_count - event_notification_count;
                    $('#eventNotificationCount').html( (response.event_alert>0)?response.event_alert: "");
                }
                else{
                    total_notifications =   parseInt(response.total_alert);
                    $('#bookingNotificationCount').html( ( response.notification_alert>0) ? response.notification_alert : "");
                    $('#sosNotificationCount').html(( response.sos_alert>0) ? response.sos_alert : "");
                    $('#eventNotificationCount').html( (response.event_alert>0)?response.event_alert: "");
                }


                $('#notificationCount').html( (parseInt(total_notifications)>0) ? total_notifications : "");

      

      
            }
        });
    }
    getNotification();
</script>