require('./bootstrap');



window.Echo.channel('booking')
.listen('.booking_timed_out', (e) => {
  console.log(e, "booking timed out");
  
  Swal.fire({
    icon: 'warning',
    title: e.title,
    html: '<div >'+e.message+'</div>',
    showConfirmButton: true,
    position: 'bottom-end',
    width: 400,
    padding: '2em',
    showCloseButton: true,
    color: 'black',
    toast: true,
    timer: 10000,
    timerProgressBar: true,
    
    // background: '#89D1A3',
}).then((result) => {
  if (result.isConfirmed) {
    console.log('adsas');
      window.location = "/admin/heatmap/booking?booking_id="+e.booking_id;
  }
})

});