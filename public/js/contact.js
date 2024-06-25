
let goContact = {
  init: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    goContact.urlSendContact = cfg.urlSendContact ? cfg.urlSendContact : null;
    goContact.events();
  },

  events: function(){

    $("#btnContactForm").on("click", function(e){
      e.preventDefault();
      return $(this).submit();
    });

    $("#formCont").parsley().on('field:validated', function() {
      console.log('form invalit...');
    }).on('form:success', function(){
      goContact.sendContactForm();
    });
  },

  sendContactForm: async function(_this){
    let url = goContact.urlSendContact ? goContact.urlSendContact : null;
    let res;

    let data = {
      name: $('#name').val(),
      email: $('#email').val(),
      message: $('#message').val(),
    };

    try {
      const resp = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({'data': data}) 
      });
      res = await resp.json();
      console.log('load config', res);
    } catch (error) {
      res = { success: false };
      console.log('Error en la llamada AJAX:', error);
    }
    console.log('res', res);
    return res;
  }
};