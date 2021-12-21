require('./bootstrap');

const sos_message = document.getElementById('sos_message');

window.Echo.channel('sos')
.listen('.message', (e) => {
  console.log(e);
  sos_message.innerHTML += '<h3> SOS: '+e.title+' </h3> \
                              <p>'+e.message+'</p>\
                              <p>By: '+e.user_name+' ('+ e.user_type +')</p>';

});