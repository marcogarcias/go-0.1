let go={
  AUTH: false,
  asset: '',
  section: '',
  btnHelp: 0,
  urlAjax:'',
  goMap: '',
  goMapCircle: '',
  lat: 0.0,
  lng: 0.0,
  toGo: '',
  chat: true,
  init: function(cfg){
    this.loadCfg(cfg);
    this.loadBtnHelp();

    $('#help-modal .close-modal').on('click', function(e){
      e.preventDefault();
      $('#help-modal').modal('toggle');
    });
  },
  loadCfg: function(cfg){
    this.AUTH = cfg && cfg.auth ? cfg.auth : 0;
    this.asset = cfg && cfg.asset ? cfg.asset : '';
  },
  loadBtnHelp: function(){
    $('.btn-help').on('click', function(){
      $('#help-modal').modal('toggle');
    });
  },
  redimentions: function(showBtnHelp){
    if($(document).width() <= 750){
      $('.btn-help-movil').css('display', showBtnHelp?'inline-block':'none');
      $('.btn-back-movil').css('display', showBtnHelp?'none':'inline-block');
    }else{
      $('.btn-help-movil').css('display', 'none');
      $('.btn-back-movil').css('display', 'none');
    }

    $(window).resize(function(){
      if($(document).width() <= 750){
        $('.btn-help-movil').css('display', showBtnHelp?'inline-block':'none');
        $('.btn-back-movil').css('display', showBtnHelp?'none':'inline-block');
      }else{
        $('.btn-help-movil').css('display', 'none');
        $('.btn-back-movil').css('display', 'none');
      }
    });
  },
  // inicializando el registro de usuarios/empresas  
  registerInit: function(cfg){
    go.registerEvents(cfg);
  },
  registerEvents: function(cfg){
    let sec, html;
    $('.userType').on('change', function(){
      let userType = $(this).val();
      let span = '';
      let li = '';
      if(userType=='user'){
        $('#stablishmentForm').fadeOut();
        span = 'Registrarse como usuario tienes los siguientes beneficios:';
        li = `
        <li>Tener chat directo con los negocios y otros usuarios de nuestra red.</li>
        <li>Acceso a bolsas de trabajo y ofertas de los negocios.</li>
        <li>Almacenamiento de tus lugares preferidos.</li>`;
      }else{
        $('#stablishmentForm').fadeIn();
        span = 'Registrarse como empresa tienes los siguientes beneficios:';
        li = `
        <li>Crear tu espacio para mostrar tus productos y servicios.</li>
        <li>Chat directo con tus posibles clientes.</li>
        <li>Solicitar personal para tus negocios.</li>`;
      }
      $('#userTypeAlert span').html(span);
      $('#userTypeAlert ul').html(li);
      $('#userTypeAlert').show();
    });

    $('#user').trigger('click');

    // evento para desplegar los tags de acuerdo a la sección
    $(document).on('change', '#section', function(){
      console.log('secciones...');
      sec = $(this).val();
      go.loadAjaxPost(cfg.tagsurl, {sec: sec}, function(res){
        if(res.success && res.tags){
          html='';
          for(let tag in res.tags){
            html += ''+
              '<div class="custom-control custom-checkbox custom-control-inline">'+
                '<input type="checkbox" class="custom-control-input" id="'+res.tags[tag].idtag+'" name="tags[]" value="'+res.tags[tag].idtag+'">'+
                '<label class="custom-control-label" for="'+res.tags[tag].idtag+'">'+res.tags[tag].name+'</label>'+
              '</div>';
          }
          $('#tags').empty().append(html);
        }
      });
    });

    // eventos para la librería Cropper
    $modal = $('.imagecrop');
    $image = document.getElementById('image');
    cropper;

    $(document).off("change", "#logotipo");
    $(document).on('change', '#logotipo', function(e){
      //stab.loadPrevLogotipo(this);
      //stab.loadEditLogotipo(e);
      stab.initLoadFile(e);
    });

    $(document).on('shown.bs.modal', '.imagecrop', function(){
      stab.initCropper();
    }).on('hidden.bs.modal', '.imagecrop', function(){
      cropper.destroy();
      cropper = null;
    });

    $(document).on('click', '#crop', function(){
      stab.cropImage();
    });
  },
  // inicializando los anuncios
  initAds: function(){
    $('.ads-list-a').on('click', function(){
      let img = $(this).attr('data-img');
      let name = $(this).attr('data-name');
      $('#ads-img').attr('src', img);
      $('#ads-img').attr('title', name);
      $('#ads-img').attr('alt', name);
    });
  },
  initStablisments: function(cfg, callback){
    cfg = (typeof cfg === 'object') ? cfg : {};
    cfg.menu = (typeof cfg.menu === 'object') ? cfg.menu : {};
    let jobsSection = cfg.jobsSection ? cfg.jobsSection : false;
    let haveMenu = parseInt(cfg.menu.haveMenus);
    let cfgMenus = cfg.menu ? cfg.menu : {};
    let cfgStab = cfg.stab ? cfg.stab : {};
    let urlTags = cfg.urlTags ? cfg.urlTags : {};
    go.chat = cfg.chat === 'true';

    this.redimentions(cfg.showBtnHelp);
    if(!haveMenu){
      $('#window-modal').modal({
        backdrop: "static",
        keyboard: false
      });
      $('#window-modal').modal('show');
      setTimeout(function(){
        typeof menus !== 'undefined' && menus.init(cfgMenus);
      }, 1000);
    }
    // eventos
    $('#filtersOpen').on('click', function(){
      $('#filters').slideToggle(1000);
    });

    /*$('#buttons-seccions .btn-menu').on('click', function(){
      let type = $(this).attr('id').split('-');;
      switch(type[1]){
        case 'stab':
          stab.init(cfgStab);
          break;
        case 'menus':
          menus.init(cfgMenus);
          break;
      }
    });*/

    $(document).on('change', '#section', function(){
      console.log('secciones...');
      sec = $(this).val();
      go.loadAjaxPost(urlTags, {sec: sec}, function(res){
        if(res.success && res.tags){
          html='';
          for(let tag in res.tags){
            html += ''+
              '<div class="custom-control custom-checkbox custom-control-inline">'+
                '<input type="checkbox" class="custom-control-input" id="'+res.tags[tag].idtag+'" name="tags[]" value="'+res.tags[tag].idtag+'">'+
                '<label class="custom-control-label" for="'+res.tags[tag].idtag+'">'+res.tags[tag].name+'</label>'+
              '</div>';
          }
          $('#tags').empty().append(html);
        }
      });
    });

    $('#btn-stab, #btn-menus').on('click', function(){
      let type = $(this).attr('id').split('-');;
      switch(type[1]){
        case 'stab':
          stab.init(cfgStab);
          break;
        case 'menus':
          menus.init(cfgMenus);
          break;
      }
    });

    $('.stablish-cont').on('click', '.stablish-add, .stablish-del', function(e){
      e.preventDefault();
      let id = $(this).attr('data-id');
      let stab = $(this).attr('data-stab');
      let stabName = $(this).attr('data-name');
      go.loadAjaxPost(cfg.urlAjax, {stab: stab, stabName: stabName}, function(res){
        if(res.action=='del'){
          /*$('#'+id+'-row1, #'+id+'-row2, #'+id+'-row3').fadeOut( "slow", function() {
            $('#'+id+'-row1, #'+id+'-row2, #'+id+'-row3').remove();
          });*/
          $('#'+id+'-cont').fadeOut( "slow", function() {
            $('#'+id+'-cont').remove();
          });
        }
        go.toastr({'type': res.code, 'message':res.message});
      });
    });

    $('#nameFil').keyup(function(){
      go.setFilter(cfg, callback);
    });

    $('#zoneFil, #tagFil').on('change', function(){
      go.setFilter(cfg, callback);
    });

    $('#btnChat').on('click', ()=>{
      let url = cfg.urlEnableChat ? cfg.urlEnableChat : '';
      let stab = $('#btnChat').attr('data-stab');
      go.enableChat(url, stab, !go.chat);
    });

    if(jobsSection)
      go.adminJobs(cfg);

  },
  setFilter: function(cfg, callback){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let auth = cfg.auth ? cfg.auth : 0; 
    let asset = cfg.asset ? cfg.asset : this.asset; 
    let url = cfg.urlSetFilter ? cfg.urlSetFilter : '';
    let sec = cfg.section ? cfg.section : '';
    let route_stablishment = cfg.route_stablishment ? cfg.route_stablishment : '';

    let filters = {section: sec};
    let html='';
    $('.filter').each(function(){
      $(this).val() && (filters[$(this).attr('name')] = $(this).val());
    });

    go.loadAjaxPost(url, filters, function(stablish){
      if(callback && (typeof callback === 'function'))
        callback(stablish);
    });
  },
  // eventos para la sección de la administración de "jobs"
  adminJobs: function(cfg) {
    cfg = (typeof cfg === 'object') ? cfg : {};
    let urlAddStab = cfg.urlAddStab ? cfg.urlAddStab : false;

    let urlAddJob = cfg.urlAddJob ? cfg.urlAddJob : false;
    let urlUpdJob = cfg.urlUpdJob ? cfg.urlUpdJob : false; 
    let urlDelJob = cfg.urlDelJob ? cfg.urlDelJob : false;
    //let urlMyJobs = cfg.urlMyJobs ? cfg.urlMyJobs : false;

    let urlAddAd = cfg.urlAddAd ? cfg.urlAddAd : false;
    //let urlUpdAd = cfg.urlUpdAd ? cfg.urlUpdAd : false;
    let urlDelAd = cfg.urlDelAd ? cfg.urlDelAd : false;
    let urlMyDatas = cfg.urlMyDatas ? cfg.urlMyDatas : false;
    let stab = $('#stab').val();

    $(':reset').on('click', function(){
      let frm = $(this).attr('data-frm');
      go.resetFrm('#'+frm);
    });

    let setDataTable = function(ty, dataTable){
      $('#'+ty+'Table tbody').empty().html(dataTable);
    };

    // eventos para administrar la sección de empleos (jobs)
    $('#addJob').on('click', function(e){
      e.preventDefault();
      go.addData(urlAddJob, 'jobsFrm', function(res){
        go.myDataTable(urlMyDatas, stab, 'jobs', setDataTable);
      });
    });

    $(document).on('click', '#updJob', function(e){
      e.preventDefault();
      go.updJob(urlUpdJob, $(this).attr('data-job'), $(this).attr('data-name'));
    });

    $(document).on('click', '#delJob', function(e){
      e.preventDefault();
      go.delData(urlDelJob, $(this).attr('data-job'), $(this).attr('data-name'), function(){
        go.myDataTable(urlMyDatas, stab, 'jobs', setDataTable);
      });
    });
/*
    $('#addAd').on('click', function(e){
      e.preventDefault();
      go.addData(urlAddAd, 'adsFrm', function(res){
        go.myDataTable(urlMyDatas, stab, 'ads', setDataTable);
      });
    });

    $(document).on('click', '#updAd', function(e){
      e.preventDefault();
      go.updAd(urlUpdAd, $(this).attr('data-ad'), $(this).attr('data-name'));
    });

    $(document).on('click', '#delAd', function(e){
      e.preventDefault();
      go.delData(urlDelAd, $(this).attr('data-ad'), $(this).attr('data-name'), function(){
        go.myDataTable(urlMyDatas, stab, 'ads', setDataTable);
      });
    });
*/
    // Eventos para administrar la sección de anuncios
    $('#addAd').on('click', function(e){
      e.preventDefault();
      go.addData(urlAddAd, 'adsFrm', function(reg){
        $('#ad').val(reg['ad']);
      }, true);
    });

    $('#delAd').on('click', function(e){
      e.preventDefault();
      go.delData(urlDelAd, $('#ad').val(), '', function(){
        $('#adsFrm')[0].reset();
        $('#descripcionAd').val('');
        $('#adsFrm .reset').val('');
      });
    });

    // Eventos para administrar la sección de empresa
    $('#addStab').on('click', function(e){
      e.preventDefault();
      go.addData(urlAddStab, 'stabFrm', function(reg){
        // agregar mensaje
        console.log('res: ', res);
      }, true);
    });    
  },
  resetFrm: function(frm){
    $(frm)[0].reset();
    $(frm+' .reset').val('');
  },
  // agrega un dato
  addData: function(url, idFrm, callback, noReset){
    noReset = noReset ? noReset :  false;
    let data = $('#'+idFrm).serializeArray();
    go.loadAjaxPost(url, data, function(res){
      if(res['success']){
        noReset || go.resetFrm('#'+idFrm);
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
      go.toastr({'type': res.code, 'message':res.message});
      //console.log('ajax: ', res);
    });
  },
  // edita una vacante
  updJob: function(url, job, name){
    let data = {job: job, name: name};
    let cont={};
    go.loadAjaxPost(url, data, function(res){
      //console.log('ajax: ', res);
      if(res['success']){
        cont = res['cont'];
        $('#jobsFrm')[0].reset();
        $('#job').val(job);
        $('#vacante').val(cont['name']);
        $('#descripcion').val(cont['description']);

        if(cont['documentation']!='cv')
          $('#solicitud').prop('checked', true);
        else
          $('#cv').prop('checked', true);
      }
    });
  },
  // edita un anuncio
  /*updAd: function(url, ad, name){
    let data = {ad: ad, name: name};
    let cont={};
    go.loadAjaxPost(url, data, function(res){
      //console.log('ajax: ', res);
      if(res['success']){
        cont = res['cont'];
        $('#adsFrm')[0].reset();
        $('#ad').val(ad);
        $('#descripcionAd').val(cont['description']);
      }
    });
  },*/
  // elimina una vacante/anuncio
  delData: function(url, id, name, callback){
    go.loadAjaxPost(url, {id: id, name: name}, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
      go.toastr({'type': res.code, 'message':res.message});
      //console.log('ajax: ', res, job, idDel);
    });
  },
  // Obtiene un listado de las vacantes de una empresa en una tabla
  myDataTable: function(url, stab, ty, callback){
    let data = {stab: stab, ty: ty};
    let cont={};

    if(stab){
      go.loadAjaxPost(url, data, function(res){
        if(res['data']){
          if(callback && (typeof callback === 'function')){
            callback(ty, res['data']);
          }
        }
      });
    }
  },
  // Habilita/deshabilita el chat de una empresa
  enableChat: (url, stab, enable=true)=>{
    let txt;
    let data = {enableChat: enable, stab: stab};
    go.loadAjaxPost(url, data, function(res){
      //console.log('ajax: ', res);
      if(res['success']){
        go.chat = enable;
        txt = enable ? 'Desactivar chat' : 'Activar chat';
        $('#btnChat').text(txt);
        enable ? $('#btn-openChat').fadeIn('slow') : $('#btn-openChat').fadeOut('slow');
        console.log(enable ? 'habilitando' : 'deshabilitando');
      }
    });
  },
  // Funciones para el mapa y localización
  initGeo: function(cfg){
    this.redimentions(cfg.showBtnHelp);
    cfg = (typeof cfg === 'object') ? cfg : {};
    let url = cfg.url ? cfg.url : '';
    go.toGo = cfg.toGo;
    go.zoom = cfg.zoom ? cfg.zoom : 14;
    go.asset = cfg.asset ? cfg.asset : '/';
    go.stablish = cfg.stablish ? cfg.stablish : false;
    //go.lat = 19.69448;
    //go.lng = -99.00208;

    if(navigator.geolocation){
      //navigator.geolocation.getCurrentPosition(function(position){
      navigator.geolocation.watchPosition(function(position){
        go.lat = position.coords.latitude;
        go.lng = position.coords.longitude;
        //console.log(go.lat, go.lng);

        let data = {'lat': go.lat, 'lng': go.lng};
        go.loadAjaxPost(url, data, function(res){
          go.drawMap(res['stablish']);
          //console.log('ajax: ',go.asset,res);
        });
      }, function(msg){
        $('#go-mapa').empty().append('<img style="width:100%" src="'+go.asset+'img/site/gps-off.png">');
        console.error( msg );
      });
    }else{
      $('#go-mapa').empty().append('<img style="width:100%" src="'+go.asset+'img/site/gps-off.png">');
      alert('ERROR: No se puede obtener tu ubicación, no se mostrará el mapa. Intenta más tarde, gracias.');
    }

    $('.map-around').on('input change', function(e){
      let mts = $(this).val();
      go.setCircle(mts);
    });
  },
  drawMap: function(stablish){
    let marker;
    let coor;
    coor = go.stablish ? [go.stablish.lat, go.stablish.lng] : [go.lat, go.lng];
    go.goMap = L.map('go-mapa').setView(coor, go.zoom);
    //let token='pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    let token='pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ';

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+token, {
      maxZoom: 18,
      attribution: 'Datos del mapa de &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, ' + '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imágenes © <a href="https://www.mapbox.com/">Mapbox</a>', 
      //id: 'mapbox/satellite-streets-v11'
      id: 'mapbox/streets-v11'
    }).addTo(go.goMap);

    go.goMapCircle = L.circle([go.lat, go.lng], {
      color: 'blue',
      fillColor: '#0f0',
      fillOpacity: 0.1,
      radius: 2000
    }).addTo(go.goMap);

    marker = L.marker([go.lat, go.lng]).addTo(go.goMap);
    for(let stab in stablish){
      go.setStablishInMap(stablish[stab]);
    }
  },
  setStablishInMap: (stablish)=>{
    console.log(stablish, go.stablish);
    let marker;
    let toGo = go.toGo+'/';
    let mark = go.asset+'img/site/btn/'+stablish.secImage;
    mark = Number(go.stablish.idstablishment) == Number(stablish.idstablishment) ?
      go.asset+'img/site/stablishments/logos/'+stablish.image : 
      go.asset+'img/site/btn/'+stablish.secImage;;
    var marcador = L.icon({
      iconUrl: mark,
      iconSize: [50, 50]
    });
    marker = L.marker([stablish.lat, stablish.lng], {icon: marcador}).on('click', function(e){
      toGo = toGo.replace('#ID#', e.target.idStab);
      window.location = toGo;
    }).addTo(go.goMap)
    .bindTooltip(stablish.description2, {direction: 'top', offset: [0,-20], permanent: true})
    .openTooltip();
    //console.log('img: ', go.asset+'img/site/btn/'+stablish[stab].image);
    marker['idStab']=stablish.idstablishment;
    /*L.marker([stablish[stab].lat, stablish[stab].lng], {icon: marcador}).
      addTo(go.goMap).
      bindTooltip("my tooltip text").openTooltip();*/
    //marker.bindPopup('<b>'+stablish[stab].name+'</b><br><a href="'+toGo+'">Solicita más información</a>');
  },
  setCircle: function(mts){
    mts = Number(mts*100);
    if(mts>=1000 && mts<=3000){
      go.goMapCircle.setRadius(mts);
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
  // funciones para el TOASTR
  // type: success|info|warning|error
  toastr: function(cfg){
    if(typeof cfg !== 'object') return false;
    let type = cfg.hasOwnProperty('type') ? cfg.type : 'success';
    let message = cfg.hasOwnProperty('message') ? cfg.message : 'La operación fue un éxito.';
    let closeButton = cfg.hasOwnProperty('closeButton') ? cfg.closeButton : true;
    let progressBar = cfg.hasOwnProperty('progressBar') ? cfg.progressBar : true;
    let positionClass = cfg.hasOwnProperty('positionClass') ? cfg.positionClass : 'toast-top-center';
    toastr.options = {
      "closeButton": closeButton,
      "debug": false,
      "newestOnTop": false,
      "progressBar": progressBar,
      "positionClass": positionClass,
      "preventDuplicates": false,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    // toastr["success"]("Inconceivable!")
    toastr[type](message);
  }
};





