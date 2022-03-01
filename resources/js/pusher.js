require('./bootstrap');

const sos_message = document.getElementById('sos_message');
const event_message = document.getElementById('event_message');

// var window_echo = window.Echo;

window.Echo.channel('sos')
.listen('.event_created', (e) => {
  console.log(e);
  event_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
                              <p>'+e.message+'</p>\
                              <p>By: '+e.user_name+' ('+ e.user_type +')</p>';

})
.listen('.sos_created', (ev) => {
  console.log(ev);
  sos_message.innerHTML += '<h3> SOS: '+ev.title+' </h3> \
                              <p>'+ev.message+'</p>\
                              <p>By: '+ev.user_name+' ('+ ev.user_type +')</p>';

});

// window.Echo.channel('event')
// .listen('.message', (e) => {
//   console.log(e);
//   event_message.innerHTML += '<h3> EVENT: '+e.title+' </h3> \
//                               <p>'+e.message+'</p>\
//                               <p>By: '+e.user_name+' ('+ e.user_type +')</p>';

// });


// const sos_channel = window.Echo.channel('sos')

  
// sos_channel.listen('.event_created_message', e => {
//   console.log(e);
//   event_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
//                           <p>'+e.message+'</p>\
//                           <p>By: '+e.user_name+' ('+ e.user_type +')</p>';
// });


// window.Echo.channel('sos').listen('.message', e => {
//       console.log(e);
//   sos_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
//                               <p>'+e.message+'</p>\
//                               <p>By: '+e.user_name+' ('+ e.user_type +')</p>';
// });





// const eventsTolisten = [
//   '.sos_created_message',
//   '.event_create_message',
// ]

// eventsTolisten.forEach(event => {
//   sos_channel.listen(event, e => {
//     (socket_body = {
//       name: event,
//       data: e.data
//     }) => {
//       event_message.innerHTML += '<h3> EVENT: '+e.title+' </h3> \
//                                    <p>'+e.message+'</p>\
//                                    <p>By: '+e.user_name+' ('+ e.user_type +')</p>';
//     }
//   })
// })



// handleSocketEvents(socket_body=null)
// {
//   if(socket_body != null)
//   {
//     console.log(e);
//     event_message.innerHTML += '<h3> EVENT: '+e.title+' </h3> \
//                               <p>'+e.message+'</p>\
//                               <p>By: '+e.user_name+' ('+ e.user_type +')</p>';
//   }
// }