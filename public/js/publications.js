
let goPublications = {
  init: function(cfg){
    goPublications.events();
  },

  events: function(){
    $('#btn-menu-movil').on('click', function(e) {
      e.preventDefault();
      $('#menu-movil').toggleClass('visible');
    });

    $(document).on('click', function(e) {
      if (!$(e.target).closest('#btn-menu-movil, #menu-movil').length) {
        $('#menu-movil').removeClass('visible');
      }
    });
  }
};