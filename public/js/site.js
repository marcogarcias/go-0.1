let go={
  AUTH: false,
  asset: '',
  section: '',
  btnHelp: 0,
  urlAjax:'',
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
    let lat, lng;
    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(function(position){
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        $("#latitud").val(lat);
        $("#longitud").val(lng);
      }, function(msg){
        console.error(msg);
      });
    }else{
      console.log("No se puede obtener las coordenadas");
    }

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
        // quitando datas para que no valide con parsley
        go.setDataByParsley(false);
      }else{
        $('#stablishmentForm').fadeIn();
        span = 'Registrarse como empresa tienes los siguientes beneficios:';
        li = `
        <li>Crear tu espacio para mostrar tus productos y servicios.</li>
        <li>Chat directo con tus posibles clientes.</li>
        <li>Solicitar personal para tus negocios.</li>`;
        // agregando datas para que validar con parsley
        go.setDataByParsley(true);
      }
      $('#userTypeAlert span').html(span);
      $('#userTypeAlert ul').html(li);
      $('#userTypeAlert').show();
    });

    $('#user').trigger('click');

    // evento para desplegar los tags de acuerdo a la sección
    $(document).on('change', '#section', function(){
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

    // validando formulario de registro
    $('#frmRegister').parsley().on('field:validated', function(){
      //var ok = $('.parsley-error').length === 0;
    }).on('form:submit', function(x, y, z){
      //console.log("enviando form...", x, y, z);
    });
  },
  // agrega o quita atributos data para la validación de parsley.js 
  setDataByParsley: (valid)=>{
    if(valid){
      $("#nameStab").attr('required', true);
      $("#nameStab").attr('data-parsley-maxlength', 155);
      $("#descripcion").attr('required', true);
      $("#descripcion").attr('data-parsley-maxlength', 200);
      $("#descripcion2").attr('required', true);
      $("#descripcion2").attr('data-parsley-maxlength', 100);
      $("#direccion").attr('data-parsley-maxlength', 200);
      $("#latitud").attr('data-parsley-maxlength', 20);
      $("#longitud").attr('data-parsley-maxlength', 20);
      $("#telefono").attr('data-parsley-maxlength', 20);
      $("#whatsapp").attr('data-parsley-maxlength', 20);
      $("#facebook").attr('data-parsley-maxlength', 200);
      $("#instagram").attr('data-parsley-maxlength', 200);
      $("#twitter").attr('data-parsley-maxlength', 200);
      $("#youtube").attr('data-parsley-maxlength', 200);
      $("#horario").attr('data-parsley-maxlength', 200);
      $("#section").attr('required', true);
      $("#adminCode").attr('required', true);
      $("#adminCode").attr('data-parsley-pattern', "go123-4");
    }else{
      $("#nameStab").removeAttr('required');
      $("#nameStab").removeAttr('data-parsley-maxlength');
      $("#descripcion").removeAttr('required');
      $("#descripcion").removeAttr('data-parsley-maxlength');
      $("#descripcion2").removeAttr('required');
      $("#descripcion2").removeAttr('data-parsley-maxlength');
      $("#direccion").removeAttr('data-parsley-maxlength');
      $("#latitud").removeAttr('data-parsley-maxlength');
      $("#longitud").removeAttr('data-parsley-maxlength');
      $("#telefono").removeAttr('data-parsley-maxlength');
      $("#whatsapp").removeAttr('data-parsley-maxlength');
      $("#facebook").removeAttr('data-parsley-maxlength');
      $("#instagram").removeAttr('data-parsley-maxlength');
      $("#twitter").removeAttr('data-parsley-maxlength');
      $("#youtube").removeAttr('data-parsley-maxlength');
      $("#horario").removeAttr('data-parsley-maxlength');
      $("#section").removeAttr('required');
      $("#adminCode").removeAttr('required');
      $("#adminCode").removeAttr('data-parsley-pattern');
    }
  },
  loginInit: function(){
    $("#viewEye").on("click", function(){
      let type = $("#password").attr("type");
      $("#password").attr("type", type=="password"?"text":"password")
    });
  },
  // inicializando los anuncios del home
  initAds: function(){
    $('.ads-list-a').on('click', function(){
      let img = $(this).attr('data-img');
      let name = $(this).attr('data-name');
      $('#ads-img').attr('src', img);
      $('#ads-img').attr('title', name);
      $('#ads-img').attr('alt', name);
    });
  },
  initAdHome: function(){
    $('#adHome-modal').modal('show');
  },
  initStablisments: function(cfg, callback){
    cfg = (typeof cfg === 'object') ? cfg : {};
    cfg.menu = (typeof cfg.menu === 'object') ? cfg.menu : {};
    let iAmStab = cfg.iAmStab ? cfg.iAmStab : false;
    let jobsSection = cfg.jobsSection ? cfg.jobsSection : false;
    let haveMenu = parseInt(cfg.menu.haveMenus);
    let cfgMenus = cfg.menu ? cfg.menu : {};
    let cfgStab = cfg.stab ? cfg.stab : {};
    let cfgJobs = cfg.jobs ? cfg.jobs : {};
    let cfgCv = cfg.cv ? cfg.cv : {};
    let cfgGallery = cfg.gallery ? cfg.gallery : {};
    let urlTags = cfg.urlTags ? cfg.urlTags : {};
    let urlEnableDisableStab = cfg.urlEnableDisableStab ? cfg.urlEnableDisableStab : {};
    go.chat = cfg.chat === 'true';
    this.redimentions(cfg.showBtnHelp);
    if(iAmStab && !haveMenu){
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

    $(document).on("click", "#habilitado", function(){
      habilitado = parseInt($(this).attr("data-habilitado"));
      let data = { habilitado: habilitado };
      utils.sendAjax(urlEnableDisableStab, data, function(res){
        let src = `${go.asset}img/site/btn`;
        utils.toastr({'type': res.code, 'message': res.message});
        if(res['success']){
          $("#habilitado").attr("data-habilitado", habilitado?0:1);
          $("#habilitado img").attr("title", habilitado?"Deshabilitado":"Habilitado");
          $("#habilitado img").attr("src", habilitado?`${src}/btn-stab-disable.png`:`${src}/btn-stab-enable.png`);
        }
      });
    });

    $(document).on('change', '#section', function(){
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

    $("#btn-stab, #btn-jobs, #btn-menus, #btn-gallery, #btn-cv").on("click", function(){
      let type = $(this).attr("id").split("-");
      switch(type[1]){
        case "stab":
          stab.init(cfgStab);
          break;
        case "menus":
          menus.init(cfgMenus);
          break;
        case "jobs":
          jobs.init(cfgJobs);
          break;
        case "gallery":
          gallery.init(cfgGallery);
          break;
        case "cv":
          cv.init(cfgCv);
          break;
      }
    });

    $(document).on('click', '.stablish-add, .stablish-del', function(e){
      e.preventDefault();
      let id = $(this).attr('data-id');
      let stab = $(this).attr('data-stab');
      let stabName = $(this).attr('data-name');
      console.log(cfg.urlAjax, {stab: stab, stabName: stabName});
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

    /*$(document).on("click", "#habilitado", function(){
      let enable = $(this).is(":checked");
      go.enableDisableStab(enable);
    });*/

    if(jobsSection)
      go.adminJobs(cfg);

  },
  // evento del check para habilitar/deshabilitar una empresa/negocio
  /*enableDisableStab: function(enable){
    let text;
    if(enable){
      text = "habilitado";
    }else{
      text = "deshabilitando";
    }
    $("#habilitadoLabel").text(text);
  },*/
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
  // iniciando la sección de la publicación de la empresa
  initStablishment: (cfg)=>{
    cfg = (typeof cfg === 'object') ? cfg : {};
    let title = cfg.title ? cfg.title : 'DESCRIPCIÓN';

    let padStr = $(".menuProd").width()/5.5;
    $(".menuDots").text("".padEnd(padStr, "- "));
    console.log("padStr", padStr);

    // eventos
    $(document).on("click", ".menuProdDesc", function(){
      let desc = $(this).attr("data-desc");
      desc = atob(desc);
      $(".modal-title").text(title);
      $(".modal-body").text(desc);
      $("#window-modal").modal('toggle');
    });
    // compartir establecimiento
    $(document).on("click", ".btnShare", function(){
      let title_ = "Compartir", 
        text_ = "Compartir el establecimiento con un amigo.", 
        url_ = window.location;
      if(navigator.share){
        navigator.share({ title: title_, text: text_, url: url_ })
      }
      return false;
    });

    $('#window-modal .modal-title').text(title);
  },
  // eventos para la sección de la administración de "jobs"
  adminJobs: function(cfg) {
    cfg = (typeof cfg === 'object') ? cfg : {};
    let urlAddStab = cfg.urlAddStab ? cfg.urlAddStab : false;
    let urlAddAd = cfg.urlAddAd ? cfg.urlAddAd : false;
    let urlUpdAd = cfg.urlUpdAd ? cfg.urlUpdAd : false;
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

    // Eventos para administrar la sección de anuncios
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

    // Eventos para administrar la sección de anuncios
    /*$('#addAd').on('click', function(e){
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
    });*/

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
        $("#addAd").text("Agregarr");
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
      go.toastr({'type': res.code, 'message':res.message});
      //console.log('ajax: ', res);
    });
  },
  // edita un anuncio
  updAd: function(url, ad, name){
    let data = {ad: ad, name: name};
    let cont={};
    $("#titleAd").trigger("focus");
    go.loadAjaxPost(url, data, function(res){
      if(res['success']){
        cont = res['cont'];
        $("#addAd").text("Actualizar");
        $('#adsFrm')[0].reset();
        $('#ad').val(ad);
        $("#titleAd option").each(function(idx, val){
          let textVal = $(this).text();
          if(textVal == name){
            $(this).attr("selected", true);
            return false;
          }
        });
        $('#descripcionAd').val(cont['description']);
      }
    });
  },
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





