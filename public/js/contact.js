
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
      $('#contactFormCont').fadeOut('fast', function(){
        $('#contactLoadingCont').fadeIn();
      });
      goContact.sendContactForm(function(res){
        utils.toastr({'type': res.type, 'message': res.message});
        $('#contactLoadingCont').fadeOut('fast', function(){
          $(res['success']?'#contactSendCont':'#contactFormCont').fadeIn();
        });
      });
    });
  },

  sendContactForm: async function(callback){
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
      console.log('mensaje enviado', res);
    } catch (error) {
      res = { success: false };
      console.log('Error en la llamada AJAX:', error);
    }
    console.log('res', res);
    if(callback && (typeof callback === 'function')){
      return callback(res);
    }
    return res;
  }
};