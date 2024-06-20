
let goPublications = {
  init: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    goPublications.urlSendContact = cfg.urlSendContact ? cfg.urlSendContact : null;

    goPublications.events();
  },

  events: function(){

    $("#btnContactForm").on("click", function(e){
      e.preventDefault();
      return $(this).submit();
    });

    $("#formCont").parsley().on('field:validated', function() {
      console.log('form invalit...');
    }).on('form:success', function(){
      goPublications.sendContactForm();
  
      /*assignCode({}, function(res){
        console.log('res', res);
        let data = (typeof res["data"] === 'object') ? res["data"] : {};
        if(res["success"]){
          $("#ime-code").text(data["code"]);
          $("#ime-loadingCont").fadeOut("fast", function(){
            $("#ime-assignedCodeCont").fadeIn();
          });
          //toastr[details['type']](res['message'], {preventDuplicates: true});
        }else{
          setTimeout(function () {
            console.log('mostrando...');
            $("#ime-loadingCont").hide();
            $("#ime-formAlertText").text(res["message"]);
            $("#ime-formInputsCont, #ime-formAlert").show();
            $("#ime-formAlert").fadeIn();
            setTimeout(function () {
              $("#ime-formAlert").fadeOut();
            }, 8000);
          }, 2000);
          //toastr[details['type']](res['message'], lang._lo('ATENCIÓN'), {preventDuplicates: true});
        }
      });*/
    });

    $('#btn-menu-movil').on('click', function(e) {
      e.preventDefault();
      $('#menu-movil').toggleClass('visible');
    });

    $(document).on('click', function(e) {
      if (!$(e.target).closest('#btn-menu-movil, #menu-movil').length) {
        $('#menu-movil').removeClass('visible');
      }
    });
  },

  sendContactForm: async function(_this){
    let url = goPublications.urlSendContact ? goPublications.urlSendContact : null;
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