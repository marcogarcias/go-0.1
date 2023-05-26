
let map = {
  mapboxToken: "pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ",
  goMap: null,
  toGo: null,
  zoom: null,
  asset: null,
  stablish: null,
  lat: null,
  lng: null,
  goMapCircle: null,
  // Funciones para el mapa y localización
  init: function(cfg){
    go.redimentions(cfg.showBtnHelp);
    cfg = (typeof cfg === 'object') ? cfg : {};
    let url = cfg.url ? cfg.url : '';
    map.toGo = cfg.toGo;
    map.zoom = cfg.zoom ? cfg.zoom : 14;
    map.asset = cfg.asset ? cfg.asset : '/';
    map.stablish = cfg.stablish ? cfg.stablish : null;
    //map.lat = 19.69448;
    //map.lng = -99.00208;
    if(navigator.geolocation){
      //navigator.geolocation.getCurrentPosition(function(position){
      navigator.geolocation.getCurrentPosition(function(position){
        map.lat = position.coords.latitude;
        map.lng = position.coords.longitude;
        // se obtienen los establecimientos cercanos a X metros a la redonda
        let data = {'lat': map.lat, 'lng': map.lng};
        //let data2 = {'lat': map.stablish.lat, 'lng': map.stablish.lng};
        go.loadAjaxPost(url, data, function(res){
          //map.drawMap(res['stablish']);
          map.drawMapBox(res['stablish']);
        });
        //map.drawTwoPoints(data, data2);
      }, function(msg){
        $('#go-mapa').empty().append('<img style="width:100%" src="'+map.asset+'img/site/gps-off.png">');
        console.error( msg );
      });
    }else{
      $('#go-mapa').empty().append('<img style="width:100%" src="'+map.asset+'img/site/gps-off.png">');
      alert('ERROR: No se puede obtener tu ubicación, no se mostrará el mapa. Intenta más tarde, gracias.');
    }

    $('.map-around').on('input change', function(e){
      let mts = $(this).val();
      console.log("mts", mts);
      map.updateCircle({mts: mts, lat: map.lat, lng: map.lng});
    });
  },
  // inserta los punteros de varios establecimientos
  drawMap: function(stablish){
    let marker;
    let coor;
    coor = map.stablish ? [map.stablish.lat, map.stablish.lng] : [map.lat, map.lng];
    map.goMap = L.map('go-mapa').setView(coor, map.zoom);
    //let token='pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    let token='pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ';

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+token, {
      maxZoom: 18,
      attribution: 'Datos del mapa de &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, ' + '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imágenes © <a href="https://www.mapbox.com/">Mapbox</a>', 
      //id: 'mapbox/satellite-streets-v11'
      id: 'mapbox/streets-v11'
    }).addTo(map.goMap);

    map.goMapCircle = L.circle([map.lat, map.lng], {
      color: 'blue',
      fillColor: '#0f0',
      fillOpacity: 0.1,
      radius: 2000
    }).addTo(map.goMap);

    marker = L.marker([map.lat, map.lng]).addTo(map.goMap);
    for(let stab in stablish){
      map.setStablishInMap(stablish[stab]);
    }
  },
  setStablishInMap: (stablish)=>{
    console.log(stablish, map.stablish);
    let marker;
    let toGo = map.toGo+'/';
    //let mark = map.asset+'img/site/btn/'+stablish.secImage;
    let mark;
    mark = Number(map.stablish.idstablishment) == Number(stablish.idstablishment) ?
      map.asset+'../'+stablish.image : 
      map.asset+'img/site/btn/'+stablish.secImage;
    var marcador = L.icon({
      iconUrl: mark,
      iconSize: [50, 50]
    });
    marker = L.marker([stablish.lat, stablish.lng], {icon: marcador}).on('click', function(e){
      toGo = toGo.replace('#ID#', e.target.idStab);
      window.location = toGo;
    }).addTo(map.goMap)
    .bindTooltip(stablish.description2, {direction: 'top', offset: [0,-20], permanent: true})
    .openTooltip();
    //console.log('img: ', map.asset+'img/site/btn/'+stablish[stab].image);
    marker['idStab']=stablish.idstablishment;
    /*L.marker([stablish[stab].lat, stablish[stab].lng], {icon: marcador}).
      addTo(map.goMap).
      bindTooltip("my tooltip text").openTooltip();*/
    //marker.bindPopup('<b>'+stablish[stab].name+'</b><br><a href="'+toGo+'">Solicita más información</a>');
  },
  setCircle: function(mts){
    mts = Number(mts*100);
    if(mts>=1000 && mts<=3000){
      map.goMapCircle.setRadius(mts);
    }
  },
  loadAjaxPost: function(url, data, callback){
    // validar data y callback
    if(url && data !== null && (data instanceof Object)){
      axios.post(url, {
        data: data,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        responseType: 'json'
      }).then(function(res){
        if(res.status==200){
          if(callback && (typeof callback === 'function')){
            callback(res.data);
          }
        }
      }).catch(function(err){
        console.log('err: ', err);
      }).then(function(){ });
    }else console.log('err:  Datos no válidos. url: '+url+', data: '+data+')');
  },
  // **************************************
  // *** Dibuja un mapa con puro MAPBOX ***
  // **************************************
  drawMapBox: function(stablish){
    let marker, startPoint, endPoint;
    let token = this.mapboxToken;
    let lat = map.stablish ? map.stablish.lat : map.lat;
    let lng = map.stablish ? map.stablish.lng : map.lng;
    //lat = 19.69441500321867;
    //lng = -99.00197175769489;
    let coor = [lng, lat];
    // Inicializar el mapa
    mapboxgl.accessToken = token;
    map.goMap = new mapboxgl.Map({
      container: 'go-mapa',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: coor,
      zoom: map.zoom
    });

    // insertando el marcador de mi ubicación
    startPoint = this.setMarker({ lat: map.lat, lng: map.lng});

    if(map.stablish){
      endPoint = map.setMarker({ lat: map.stablish.lat, lng: map.stablish.lng, imagePath: map.stablish.image, stablish: map.stablish });
      this.setRouteBetweenTwoPoints({ startPoint:startPoint, endPoint: endPoint });
    }else{
      this.drawCircle({ lat: map.lat, lng: map.lng});

      for(let stab in stablish){
        map.setMarker({ lat: stablish[stab].lat, lng: stablish[stab].lng, imagePath: stablish[stab].image, stablish: stablish[stab] });
      }
    }
  },
  drawCircle: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let lat = cfg.lat ? cfg.lat : 0;
    let lng = cfg.lng ? cfg.lng : 0;
    let coordinates = [ lng, lat ];

    let circle = turf.circle(coordinates, 1500, {
      steps: 64,
      units: 'meters'
    });
    
    let circleGeoJSON = circle.geometry;

    map.goMap.on('load', function() {
      map.goMap.addSource('circle', {
        'type': 'geojson',
        'data': circle
      });

      map.goMap.addLayer({
        id: 'circle-fill',
        type: 'fill',
        source: 'circle',
        layout: {},
        paint: {
          'fill-color': '#007bff',
          'fill-opacity': 0.5,
        }
      });
    });
  },
  updateCircle: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let mts = cfg.mts ? cfg.mts : 0;
    let lat = cfg.lat ? cfg.lat : 0;
    let lng = cfg.lng ? cfg.lng : 0;
    let coordinates = [ lng, lat ];
    let newCircle;

    mts = Number(mts*100);
    if(mts>=1000 && mts<=3000){
      newCircle = turf.circle(coordinates, mts, {
        units: 'meters'
      });
      map.goMap.getSource('circle').setData(newCircle);
    }
  },
  // inserta un marcador al mapa
  setMarker: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let lat = cfg.lat ? cfg.lat : 0;
    let lng = cfg.lng ? cfg.lng : 0;
    let title = cfg.title ? cfg.title : 0;
    let stablish = cfg.stablish ? cfg.stablish : {};
    let name = stablish.name ? stablish.name : null;
    let desc = stablish.description2 ? stablish.description2 : null;
    let imagePath = cfg.imagePath ? cfg.imagePath : null;
    let img = imagePath ? map.asset + imagePath : null

    let customMarker = document.createElement('div');
    customMarker.style.backgroundImage = `url("${img}")`;
    customMarker.style.backgroundSize = 'cover';
    customMarker.style.backgroundPosition = 'center';
    customMarker.style.backgroundRepeat = 'no-repeat';
    customMarker.style.width = '65px';
    customMarker.style.height = '65px';
    customMarker.style.borderRadius = '50%';
    customMarker.style.border = '2px solid black';
    customMarker.style.boxShadow = '5px 5px 5px rgba(0, 0, 0, 0.50)';

    // Agregar el marcador a la capa de marcadores
    let marker = new mapboxgl.Marker({
      element: img ? customMarker : null,
      color: "#007bff",
      draggable: false,
    })
    .setLngLat([lng, lat])
    .setPopup(new mapboxgl.Popup({ offset: 35 }).setHTML(`<p>${desc}</p>`))
    .addTo(map.goMap);

    map.goMap.on('load', function(){
      desc && marker.togglePopup();
    });
    return marker;
  },
  // aplicar la ruta entre dos puntos
  setRouteBetweenTwoPoints: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let startPoint = cfg.startPoint ? cfg.startPoint : null;
    let endPoint = cfg.endPoint ? cfg.endPoint : null;
    let directions;
    if(startPoint && endPoint){
      // Obtener la ruta entre dos puntos
      directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/walking',
        interactive: false,
        controls: { instructions: true },
        language: 'es',
      });

      map.goMap.addControl(directions, 'top-left');
      map.goMap.on('load', function(){
        console.log("coordenadas", [startPoint._lngLat.lng, startPoint._lngLat.lat], [endPoint._lngLat.lng, endPoint._lngLat.lat]);
        directions.setOrigin([startPoint._lngLat.lng, startPoint._lngLat.lat]);
        directions.setDestination([endPoint._lngLat.lng, endPoint._lngLat.lat]);
      });
    }
  },
};