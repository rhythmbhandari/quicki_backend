require('./bootstrap');


// const sos_message = document.getElementById('sos_message');
const msg_card_body_el = document.querySelector('.msg_card_body');

window.Echo.channel('sos')
.listen('.event_created', (e) => {
  console.log(e, "sos event");
  var sos_id = SOS_ID;
  Swal.fire({
    icon: 'warning',
    title: e.title,
    html: '<div >'+e.message+'</div>',
    showConfirmButton: false,
    position: 'bottom-end',
    width: 400,
    padding: '2em',
    showCloseButton: true,
    color: 'black',
    toast: true,
    timer: 3000,
    timerProgressBar: true,
    // background: '#89D1A3',
  })

  if(e.sos_id == sos_id)
  {
    msg_card_body_el.innerHTML += ' <div class="d-flex flex-wrap justify-content-start mb-4"> \
    <div class="img_cont_msg"> \
    <img src="http://127.0.0.1:8000/assets/media/user_placeholder.png" class="rounded-circle user_img_msg"> \
    </div>\
    <div class="msg_cotainer"> \
        \
        '+e.message+' \
        \
    </div> \
    <div style="flex-basis: 100%; height: 0"></div> \
    <div class="msg_info mt-2">2021-12-22 13:21:35 by '+e.user_name+' ('+ e.user_type +')</div> \
</div> ' 
  }

  // event_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
  //                             <p>'+e.message+'</p>\
  //                             <p>By: '+e.user_name+' ('+ e.user_type +')</p>';

})
.listen('.sos_created', (ev) => {
  console.log(ev);
  // sos_message.innerHTML += '<h3> SOS: '+ev.title+' </h3> \
  //                             <p>'+ev.message+'</p>\
  //                             <p>By: '+ev.user_name+' ('+ ev.user_type +')</p>';

});