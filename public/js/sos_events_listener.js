/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/sos_events_listener.js ***!
  \*********************************************/
// require('./bootstrap');
// // const sos_message = document.getElementById('sos_message');
// const msg_card_body_el = document.querySelector('.msg_card_body');
// window.Echo.channel('sos')
// .listen('.event_created', (e) => {
//   console.log(e, "sos event");
//   var sos_id = SOS_ID;
//   document.getElementById('btnPlayEventAudio').click();
//   getNotification('event');
//   Swal.fire({
//     icon: 'warning',
//     title: e.title,
//     html: '<div >'+e.message+' by '+ e.user_name +' ('+e.user_type+')!</div>',
//     showConfirmButton: false,
//     position: 'bottom-end',
//     width: 400,
//     padding: '2em',
//     showCloseButton: true,
//     color: 'black',
//     toast: true,
//     timer: 3000,
//     timerProgressBar: true,
//     // background: '#89D1A3',
//   });
//   if(e.sos_id == sos_id)
//   {
//     //http://127.0.0.1:8000/assets/media/user_placeholder.png
//     msg_card_body_el.innerHTML += ' <div class="d-flex flex-wrap justify-content-start mb-4"> \
//     <div class="img_cont_msg"> \
//     <img src="/'+e.user_thumbnail_path+'" class="rounded-circle user_img_msg"> \
//     </div>\
//     <div class="msg_cotainer"> \
//         \
//         '+e.message+' \
//         \
//     </div> \
//     <div style="flex-basis: 100%; height: 0"></div> \
//     <div class="msg_info mt-2">2021-12-22 13:21:35 by '+e.user_name+' ('+ e.user_type +')!</div> \
// </div> ' 
//   }
//   // event_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
//   //                             <p>'+e.message+'</p>\
//   //                             <p>By: '+e.user_name+' ('+ e.user_type +')</p>';
// })
// .listen('.sos_created', (ev) => {
//   console.log(ev);
//   document.getElementById('btnPlaySosAudio').click();
//   getNotification('sos');
//   Swal.fire({
//     icon: 'warning',
//     title: ev.title,
//     html: '<div >'+ev.message+' by '+ ev.user_name +' ('+ev.user_type+')</div>',
//     showConfirmButton: false,
//     position: 'bottom-end',
//     // imageUrl: '/'+ev.user_thumbnail_path,
//     // imageSize:'150x150',
//     width: 400,
//     padding: '2em',
//     showCloseButton: true,
//     color: 'black',
//     toast: true,
//     timer: 3000,
//     timerProgressBar: true,
//     // background: '#89D1A3',
//   })
// });
// window.Echo.channel('booking')
// .listen('.booking_timed_out', (e) => {
//   console.log(e, "booking timed out");
//   document.getElementById('btnPlayNotificationAudio').click();
//   getNotification('notification');
//   Swal.fire({
//     icon: 'warning',
//     title: e.title,
//     html: '<div >'+e.message+'</div>',
//     showConfirmButton: true,
//     position: 'bottom-end',
//     width: 400,
//     padding: '2em',
//     showCloseButton: true,
//     color: 'black',
//     toast: true,
//     timer: 10000,
//     timerProgressBar: true,
//     // background: '#89D1A3',
// }).then((result) => {
//   if (result.isConfirmed) {
//     console.log('adsas');
//       window.location = "/admin/heatmap/booking?booking_id="+e.booking_id;
//   }
// })
// });
/******/ })()
;