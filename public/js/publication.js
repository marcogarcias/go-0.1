let goPublication = {

  hashPublication: null,
  urlSetLike: null,
  gallery: null,
  mapboxToken: "pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ",

  init: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    goPublication.hashPublication = cfg.hashPublication ? cfg.hashPublication : '';
    goPublication.urlSetLike = cfg.urlSetLike ? cfg.urlSetLike : '';
    goPublication.lng = cfg.lng ? cfg.lng : 0;
    goPublication.lat = cfg.lat ? cfg.lat : 0;
    
    goPublication.initSlider();
    goPublication.ligthbox();
    goPublication.setMap(goPublication.lng, goPublication.lat);
    goPublication.events();
  },

  // https://splidejs.com/
  initSlider: function(){
    goPublication.gallery = new Splide('.splide', {
      arrows: true,
      autoplay: true,
      //cover: true,
      //fixedHeight: 400,
      //heightRatio: 0.5,
      height: 400,
      interval: 2000,
      pagination: true,
      type: 'loop',
      //rewind: true,
    });
    goPublication.gallery.mount();
  },
  
  // https://lokeshdhakar.com/projects/lightbox2/
  ligthbox: function(){
    lightbox.option({
      showImageNumberLabel: false,
      alwaysShowNavOnTouchDevices: true,
      wrapAround: true,
    });
  },

  setMap: function(lng, lat){
    lng = lng ? lng : -99.1332;
    lat = lat ? lat : 19.4326;
    mapboxgl.accessToken = goPublication.mapboxToken;
    let map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: [lng, lat], // Coordenadas iniciales del mapa
      zoom: 14
    });

    let marker = new mapboxgl.Marker()
      .setLngLat([lng, lat])
      .addTo(map);

    console.log(lng, lat);
  },

  events: function(){
    /*console.log('okokok');
    $('#btn-menu-movil').on('click', function(e) {
      e.preventDefault();
      console.log('menu...');
      $('#menu-movil').toggleClass('visible');
    });

    $(document).on('click', function(e) {
      console.log('menu 2...');
      if (!$(e.target).closest('#btn-menu-movil, #menu-movil').length) {
        console.log('menu...');
        $('#menu-movil').removeClass('visible');
      }
    });*/

    $(document).on("click", ".interactions .btn-share", function(e){
      let title_ = "Compartir", 
        text_ = "Compartir publicación con un amigo.", 
        url_ = window.location;
      if(navigator.share){
        navigator.share({ title: title_, text: text_, url: url_ })
      }
      return false;
    });

    $(document).on("click", ".interactions .btn-visits", function(e){
      return false;
    });

    $(document).on("click", ".interactions .btn-like", function(e){
      e.preventDefault();
      goPublication.setLike();
    });
  },

  setLike: function(){
    let url = goPublication.urlSetLike ? goPublication.urlSetLike : '';
    utils.sendAjax(url, { hashPublication: goPublication.hashPublication }, function(res){
      if(res.success && res.data && res.data.likes){
        let state = $('.btn-like').attr('data-state');
        if(state == 'off'){
          $('.btn-like #likesIcon').removeClass('bi bi-heart').addClass('bi bi-heart-fill');
          $('.btn-like #likesNo').text(res.data.likes);
          $('.btn-like').attr('data-state', 'on');
        }else{
          //$('.btn-like #likesIcon').removeClass('bi bi-heart-fill').addClass('bi bi-heart');
          //$('.btn-like').attr('data-state', 'off');
        }
      }
    });
  }
};