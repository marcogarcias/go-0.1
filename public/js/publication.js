let goPublication = {

  hashPublication: null,
  urlSetLike: null,
  gallery: null,
  lngUser: null,
  latUser: null,
  map: null,
  userMarker: null,
  destinationMarker: null,
  mapboxToken: "pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ",

  init: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    goPublication.hashPublication = cfg.hashPublication ? cfg.hashPublication : '';
    goPublication.urlSetLike = cfg.urlSetLike ? cfg.urlSetLike : '';
    goPublication.lng = cfg.lng ? cfg.lng : 0;
    goPublication.lat = cfg.lat ? cfg.lat : 0;
    
    goPublication.initSlider();
    goPublication.ligthbox();
    goPublication.getUserLocation();
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

  // Obtener la ubicación del usuario y trazar la ruta
  getUserLocation: function(callback){
    navigator.geolocation.getCurrentPosition(function(position) {
      $('#btnMapSimple, #btnMapWalking, #btnMapDriving, #btnMapCycling').show();
      goPublication.lngUser = position.coords.longitude;
      goPublication.latUser = position.coords.latitude;
      if(callback && (typeof callback === 'function'))
        callback([goPublication.lngUser, goPublication.latUser]);
    }, function(error) {
      $('#btnMapWalking, #btnMapDriving, #btnMapCycling').hide();
      console.error('Error obteniendo la ubicación:', error);
    });
  },

  setMap: function(lng2, lat2){
    lng2 = lng2 ?? -99.1332;
    lat2 = lat2 ?? 19.4326;
    mapboxgl.accessToken = goPublication.mapboxToken;
    let map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: [lng2, lat2], // Coordenadas iniciales del mapa
      zoom: 14
    });

    goPublication.map = map;

    /*let marker = new mapboxgl.Marker()
      .setLngLat([lng, lat])
      .addTo(map);*/

    let userMarker = null;
    let destinationMarker = null;

    // Agregar marcador de destino
    goPublication.destinationMarker = new mapboxgl.Marker({ color: '#FF0000' })
      .setLngLat([lng2, lat2])
      .addTo(map);
  },

  getAndShowRoute: function(type){
    let lng1 = goPublication.lngUser;
    let lat1 = goPublication.latUser;
    let lng2 = goPublication.lng;
    let lat2 = goPublication.lat;
    let map = goPublication.map;
    if(type == 'walking' || type == 'driving' || type == 'cycling'){
      let url = `https://api.mapbox.com/directions/v5/mapbox/${type}/${lng1},${lat1};${lng2},${lat2}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;

      $.ajax({
        method: 'GET',
        url: url
      }).done(function(data) {
        let route = data.routes[0].geometry.coordinates;
        let geojson = {
          type: 'Feature',
          properties: {},
          geometry: {
            type: 'LineString',
            coordinates: route
          }
        };

        map.addLayer({
          id: 'route',
          type: 'line',
          source: {
            type: 'geojson',
            data: geojson
          },
          layout: {
            'line-join': 'round',
            'line-cap': 'round'
          },
          paint: {
            'line-color': '#3887be',
            'line-width': 5,
            'line-opacity': 0.75
          }
        });
      });
    }else{

    }
  },

  // Función para mostrar la ubicación del usuario y la ruta
  showUserLocationAndRoute: function(type) {
    let destinationLng = goPublication.lng;
    let destinationLat = goPublication.lat;
    goPublication.clearUserMarker();
    goPublication.clearRoute();
    let map = goPublication.map;
    if(type == 'walking' || type == 'driving' || type == 'cycling'){
      goPublication.getUserLocation(function(coords){
        let userLng = coords[0];
        let userLat = coords[1];
        goPublication.userMarker = new mapboxgl.Marker({ color: '#00FF00' })
          .setLngLat([userLng, userLat])
          .addTo(map);

        // Ajustar la vista del mapa para mostrar ambos puntos
        let bounds = new mapboxgl.LngLatBounds()
          .extend([destinationLng, destinationLat])
          .extend([userLng, userLat]);
        map.fitBounds(bounds, { padding: 50 });

        // Obtener y mostrar la ruta
        goPublication.getAndShowRoute(type);
      });
    }else{
      map.flyTo({
        center: [destinationLng, destinationLat],
        zoom: 14, 
        essential: true
    });
    }
  },

  // Función para limpiar la ruta
  clearRoute: function(){
    let map = goPublication.map;

    if(map.getLayer('route')) {
      map.removeLayer('route');
    }
    if (map.getSource('route')) {
      map.removeSource('route');
    }
  },

  // Función para limpiar el marcador del usuario
  clearUserMarker: function() {
    if(goPublication.userMarker) {
      goPublication.userMarker.remove();
      goPublication.userMarker = null;
    }
  },

  events: function(){

    $('#btnMapSimple, #btnMapWalking, #btnMapDriving, #btnMapCycling').on("click", function(e){
      e.preventDefault();
      let type = $(this).attr('data-type');
      goPublication.showUserLocationAndRoute(type);
      console.log('type', type);
    });

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