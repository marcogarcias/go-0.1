let admin = {
  
  publications: {
    
    urlAdd: null,
    urlGetEstados: null,
    estados: [],
    municipio: [],
    urlGetPublication: null,
    urlGetSections: [],
    urlGetTags: [],
    publication: null,
    mapboxToken: "pk.eyJ1Ijoic29tb3NnbzkiLCJhIjoiY2tsdjducTIwMG54ZDJwbzQ5dHB4ZTFkMSJ9.TiWIu_R1l-SBxICm0ueqqQ",
    marker: null,
    map: null,
    galleryDel: [],
    fileMaxSize: 1.5 * 1024 * 1024,

    init: function(cfg){
      cfg = (typeof cfg === 'object') ? cfg : {};
      admin.publications.asset = cfg.asset ? `${cfg.asset}storage/` : null;
      admin.publications.urlAdd = cfg.urlAdd ? cfg.urlAdd : null;
      admin.publications.urlGetPublication = cfg.urlGetPublication ? cfg.urlGetPublication : null;
      admin.publications.urlGetEstados = cfg.urlGetEstados ? cfg.urlGetEstados : null;
      admin.publications.urlGetMunicipios = cfg.urlGetMunicipios ? cfg.urlGetMunicipios : null;
      admin.publications.urlGetSections = cfg.urlGetSections ? cfg.urlGetSections : null;
      admin.publications.urlGetTags = cfg.urlGetTags ? cfg.urlGetTags : null;
      admin.publications.addEvents();
    },

    addEvents: function(){
      $('#btn-addPublication').on('click', function(e){
        e.preventDefault();
        admin.publications.openForm();
      });

      $('.btn-editar').on('click', function(e){
        e.preventDefault();
        let hashPublication = $(this).attr('data-hashPublication');
        admin.publications.getPublication({ hashPublication: hashPublication }, function(res){
          let publication = res['data']['data'][0] ? res['data']['data'][0] : {};
          admin.publications.publication = publication;
          admin.publications.openForm(publication);
        });
      });

      $(document).on('change', '#portada', function(){
        admin.publications.preLoadImgFront(this);
      });

      $(document).on('change', '#gallery', function(){
        admin.publications.preLoadGallery();
      });

      $(document).on('click', '.gallery-preview-delete', function(e){
        let hashGallery = $(this).attr('data-hashgallery');
        if(hashGallery){
          admin.publications.galleryDel.push(hashGallery);
        }
        $(this).parent().remove();
      });

      $(document).on('change', '#estado', function(e){
        e.preventDefault();
        admin.publications.getMunicipios({hashEstado: $(this).val()});
      });

      // evento para desplegar los tags (subsecciones) de acuerdo a la sección
      $(document).on('change', '#section', function(){
        let tagsPub = admin.publications.publication?.tags || [];
        let check = false;
        sec = $(this).val();
        utils.sendAjax(admin.publications.urlGetTags, {sec: sec}, function(res){
          if(res.success && res.data && res.data){
            html='';
            for(let tag in res.data){
              for(let tagP in tagsPub){
                check = tagsPub[tagP].md5Tag == res.data[tag].md5Tag;
              }
              html += `
                <div class="custom-control custom-checkbox custom-control-inline">
                  <input type="checkbox" class="custom-control-input" id="${res.data[tag].hashTag}" name="tags[]" value="${res.data[tag].hashTag}" ${check?'checked':''}>
                  <label class="custom-control-label" for="${res.data[tag].hashTag}">${res.data[tag].name}</label>
                </div>`;
            }
            $('#tags').empty().append(html);
          }
        });
      });

      $('#btn-savePublication').on('click', function(e){
        e.preventDefault();
        $('#frm-publication').fadeOut('fast', function(){
          $('.loadingCont').fadeIn('fast');
        });
        admin.publications.savePublication(function(res){
          utils.toastr({'type': res.type, 'message': res.message});
          if(res['success']){
            window.location.reload();
            $("#modalGral").modal("toggle");
          }
        });
      });
    },

    getPublication: function(args, callback){
      args = (typeof args === 'object') ? args : {};
      let hashPublication = args.hashPublication ? args.hashPublication : null;
      let url = admin.publications.urlGetPublication;
      utils.sendAjax(url, { hashPublication: hashPublication }, function(res){
        if(res['success']){
          if(callback && (typeof callback === 'function')){
            return callback(res);
          }
        }
      });
    },

    openForm: function(publication){
      publication = (typeof publication === 'object') ? publication : {};
      admin.publications.setForm(publication);
      admin.publications.preLoadGalleryStored(publication.gallery);
      admin.publications.getEstados(publication);
      admin.publications.getSections(publication);
      setTimeout(function(){
        admin.publications.setMap(publication.lng, publication.lat);
        /*if(Object.values(publication).length && publication.municipio){
          admin.publications.chooseEstado(publication.municipio.estado);
        }*/
      }, 500);
      $("#modalGral").modal("show");
    },

    preLoadImgFront: function(this_){
      const file = this_.files[0];
      const maxSize = admin.publications.fileMaxSize;
      const fileSize = (file.size / (1024 * 1024)).toFixed(2);
      if(file){
        if(file.size > maxSize){
          $('#portadaCont span').css('color', '#f00');
          utils.toastr({'type': 'warning', 'message': `La imagen es demasiado pesada (${fileSize}). Máximo 1.5MB`});
        }else{
          $('#portadaCont span').css('color', '#000');
          const reader = new FileReader();
          reader.onload = function() {
            $('.img-portada-thumbnail img').attr('src', reader.result);
          }
          reader.readAsDataURL(file);
        }
      }
    },

    preLoadGallery: function(){
      const maxSize = admin.publications.fileMaxSize;
      $.each($('#gallery')[0].files, function(index, file) {
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        if(file.size > maxSize){
          utils.toastr({'type': 'warning', 'message': `Alguna imagen no ha sido cargada porque es demasiado pesada (${fileSize}). Máximo 1.5MB`});
        }else{
          const reader = new FileReader();

          reader.onload = function(e) {
            const listItem = $(`<div class="gallery-preview-item"><div class="gallery-preview-delete">X</div></div>`);
            listItem.css('background-image', `url(${e.target.result})`);
            $('#gallery-preview').append(listItem);
          }
          reader.readAsDataURL(file);
        }
      });
    },

    preLoadGalleryStored: function(gallery){
      for(let gal in gallery){
        let hashGallery = gallery[gal].hashGallery;
        let urlGallery = `${admin.publications.asset}${gallery[gal].path}/${gallery[gal].image}`;
        const listItem = $(`<div class="gallery-preview-item"><div class="gallery-preview-delete" data-hashgallery="${hashGallery}">X</div></div>`);
          listItem.css('background-image', `url(${urlGallery})`);
          $('#gallery-preview').append(listItem);
      }
    },

    getEstados: function(cfg, callback){
      admin.publications.loadEstados(cfg, function(res){
        admin.publications.estados = res["data"] ? res["data"] : {};
        admin.publications.setEstadosSelect(cfg);
        if(callback && (typeof callback === 'function')){
          return callback(res);
        }
      });
    },

    loadEstados: function (args, callback){
      args = (typeof args === 'object') ? args : {};
      let hashEstado = args.hashEstado ? args.hashEstado : null;
      let url = admin.publications.urlGetEstados;
      utils.sendAjax(url, { hashEstado: hashEstado }, function(res){
        if(res['success']){
          if(callback && (typeof callback === 'function')){
            return callback(res);
          }
        }
      });
    },

    setEstadosSelect: function(args){
      args = (typeof args === 'object') ? args : {};
      let md5Estado = args?.municipio?.estado?.md5Estado;
      $("#estado").empty();
      let options = admin.publications.setEstadosOptions({optionDefault: "Seleccionar una opción", md5Estado: md5Estado});
      $("#estado").append(options);
      if(md5Estado){
        $('#estado').trigger('change');
      }
    },

    setEstadosOptions: function(args){
      args = (typeof args === 'object') ? args : {};
      let optionDefault = args.optionDefault ? args.optionDefault : "Seleccione una opción";
      let md5Estado = args?.md5Estado || "";

      let options = `<option value="">${optionDefault}</option>`;
      let estados = admin.publications.estados;
      let estado;
      for(let e in estados){
        estado = estados[e];
        options += `<option value="${estado.hashEstado}" ${estado.md5Estado==md5Estado?"selected":""}>${estado.name}</option>`;
      }
      return options;
    },

    chooseEstado: function(estado){
      estado = (typeof estado === 'object') ? estado : {};
      let md5Estado = estado.md5Estado;
      //console.log('estado: ', md5Estado);
    },

    getMunicipios: function(cfg, callback){
      admin.publications.loadMunicipios(cfg, function(res){
        admin.publications.municipios = res["data"] ? res["data"] : {};
        admin.publications.setMunicipiosSelect(admin.publications.municipios);
        if(callback && (typeof callback === 'function')){
          return callback(res);
        }
      });
    },

    loadMunicipios: function (args, callback){
      args = (typeof args === 'object') ? args : {};
      let hashEstado = args.hashEstado ? args.hashEstado : null;
      let url = admin.publications.urlGetMunicipios;
      utils.sendAjax(url, { hashEstado: hashEstado }, function(res){
        if(res['success']){
          if(callback && (typeof callback === 'function')){
            return callback(res);
          }
        }
      });
    },

    setMunicipiosSelect: function(countries){
      $("#municipio").empty();
      let options = admin.publications.setMunicipiosOptions({optionDefault: "Seleccionar una opción"});
      $("#municipio").append(options);
    },

    setMunicipiosOptions: function(args){
      args = (typeof args === 'object') ? args : {};
      let optionDefault = args.optionDefault ? args.optionDefault : "Seleccione una opción";
      let hashMunicipio = args.hashMunicipio ? args.hashMunicipio : "";
      let md5Municipio = admin.publications.publication?.municipio?.md5Municipio || null;

      let options = `<option value="">${optionDefault}</option>`;
      let municipios = admin.publications.municipios;
      let municipio;
      for(let e in municipios){
        municipio = municipios[e];
        options += `<option value="${municipio.hashMunicipio}" ${municipio.md5Municipio==md5Municipio?"selected":""}>${municipio.name}</option>`;
      }
      return options;
    },

    getSections: function(cfg, callback){
      admin.publications.loadSections(cfg, function(res){
        admin.publications.sections = res["data"] ? res["data"] : {};
        admin.publications.setSectionsSelect(cfg);
        if(callback && (typeof callback === 'function')){
          return callback(res);
        }
      });
    },

    loadSections: function (args, callback){
      args = (typeof args === 'object') ? args : {};
      let hashSection = args.hashSection ? args.hashSection : null;
      let url = admin.publications.urlGetSections;
      utils.sendAjax(url, { hashSection: hashSection }, function(res){
        if(res['success']){
          if(callback && (typeof callback === 'function')){
            return callback(res);
          }
        }
      });
    },

    setSectionsSelect: function(args){
      args = (typeof args === 'object') ? args : {};
      let md5Section = args?.md5Section || '';

      $("#section").empty();
      let options = admin.publications.setSectionsOptions({optionDefault: "Seleccionar una opción"});
      $("#section").append(options);
      if(md5Section){
        $('#section').trigger('change');
      }
    },

    setSectionsOptions: function(args){
      args = (typeof args === 'object') ? args : {};
      let optionDefault = args.optionDefault ? args.optionDefault : "Seleccione una opción";
      let hashSection = args.hashSection ? args.hashSection : "";
      let md5Section = admin.publications.publication?.md5Section || null;

      let options = `<option value="">${optionDefault}</option>`;
      let sections = admin.publications.sections;
      let section;
      for(let s in sections){
        section = sections[s];
        options += `<option value="${section.hashSection}" ${section.md5Section==md5Section?"selected":""}>${section.name}</option>`;
      }
      return options;
    },

    setMap: function(lng, lat){
      lng = lng ? lng : -99.1332;
      lat = lat ? lat : 19.4326;
      mapboxgl.accessToken = admin.publications.mapboxToken;
      admin.publications.map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [lng, lat], // Coordenadas iniciales del mapa
        zoom: 14
      });

      const mapCanvas = admin.publications.map.getCanvas();
      mapCanvas.style.maxWidth = '100%';

      const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
      });
      admin.publications.map.addControl(geocoder);
      admin.publications.addMarker({ center: [ lng, lat ] });

      /*$('#address').on('keydown', function(e) {
        if (e.key === 'Enter') {
          geocoder.query($(this).val());
        }
      });*/

      geocoder.on('result', e => {
        console.log('result: ', e, e.result);
        admin.publications.addMarker(e.result);
      });

      admin.publications.map.on('click', admin.publications.addMarker);
    },

    addMarker: function(e) {
      //console.log('market: ', e);

      const lng = e.lngLat ? e.lngLat.lng : e.center[0];
      const lat = e.lngLat ? e.lngLat.lat : e.center[1];
      admin.publications.setCoordinate(lng, lat);

      // Eliminar el marcador anterior si existe
      if(admin.publications.marker) {
        admin.publications.marker.remove();
      }

      // Agregar un nuevo marcador
      admin.publications.marker = new mapboxgl.Marker()
        .setLngLat([lng, lat])
        .addTo(admin.publications.map);
    },

    setCoordinate: function(lng, lat){
      $('#lng').val(lng);
      $('#lat').val(lat);
    },

    setForm: function(publication){
      publication = (typeof publication === 'object') ? publication : {};
      console.log('setForm, publication', publication);

      let html = ''; 
      $('#modalGral-title').text('CREAR NUEVA PUBLICACIÓN');
      html = `
        <div class="loadingCont">
          <div class="spinner-border text-secondary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <h2>Guardando...</h2>
        </div>
        <form id="frm-publication" enctype="multipart/form-data">
            
          <div class="form-group">
            <label for="title">Título <span  class="text-danger font-weight-bolder">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="${ publication.title || '' }" placeholder="Título">
          </div>
          <div class="form-group">
            <label for="subtitle">Subtítulo</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="${ publication.subtitle || '' }" placeholder="Subtítulo">
          </div>
          <div class="form-group">
            <label for="pseudonym">Pseudónimo</label>
            <input type="text" class="form-control" id="pseudonym" name="pseudonym" value="${ publication.pseudonym || '' }" placeholder="Pseudónimo">
          </div>
          <div class="form-group">
            <label for="datetime">Hora y fecha</label>
            <input type="datetime-local" class="form-control" id="datetime" name="datetime" value="${ publication.datetime || '' }" placeholder="dd/mm/aaaa" pattern="\d{2}/\d{2}/\d{4}">
          </div>
          <div class="form-group">
            <label for="synopsis">Sinopsis</label>
            <textarea class="form-control" id="synopsis" name="synopsis" rows="3" placeholder="Sinopsis de la publicación">${ publication.synopsis || '' }</textarea>
          </div>
          <div class="form-group">
            <label for="description">Descripción <span  class="text-danger font-weight-bolder">*</span></label>
            <textarea class="form-control" id="description" name="description" rows="6" placeholder="Descripción de la publicación">${ publication.description || '' }</textarea>
          </div>
          <div class="form-group">
            <label for="price">Precio</label>
            <input type="number" class="form-control" id="price" name="price" value="${ publication.price || 0.0 }" min="0" step="0.01" placeholder="0.0">
          </div>
          <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" class="form-control" id="address" name="address" value="${ publication.address || '' }" placeholder="Dirección de la publicación">
          </div>
          <div class="form-group">
            <label for="lng">Longitud</label>
            <input type="text" class="form-control" id="lng" name="lng" value="${ publication.lng || '' }" placeholder="Longitud del lugar">
          </div>
          <div class="form-group">
            <label for="lat">Latitud</label>
            <input type="text" class="form-control" id="lat" name="lat" value="${ publication.lat || '' }" placeholder="Latitud de la publicación">
          </div>

          <div id="map"></div>

          <div id="portadaCont" class="form-group">
            <label for="portada">Imagen de portada (header)</label>
            <input type="file"  class="form-control-file" id="portada" name="portada" accept="image/jpeg, image/png, image/webp">
            <span>Peso máximo de 1.5MB.)</span>
            <div class="col-12 col-md-6 img-portada-thumbnail"><img src="${ publication.image ? (admin.publications.asset+publication.image) : '' }" class="img-thumbnail" /></div>
          </div>

          <div id="galleryCont" class="form-group">
            <label for="gallery">Galería</label>
            <input type="file" class="form-control-file" id="gallery" name="gallery" multiple accept="image/jpeg, image/png, image/webp">
            <span>Peso máximo de 1.5MB)</span>
            <div id="gallery-preview"></div>
          </div>

          <div class="form-group">
            <label for="facebook">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="${ publication.facebook || '' }" placeholder="Facebook">
          </div>
          <div class="form-group">
            <label for="instagram">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="${ publication.instagram || '' }" placeholder="Instagram">
          </div>
          <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" id="twitter" name="twitter" value="${ publication.twitter || '' }" placeholder="Twitter">
          </div>
          <div class="form-group">
            <label for="youtube">Youtube</label>
            <input type="text" class="form-control" id="youtube" name="youtube" value="${ publication.youtube || '' }" placeholder="Youtube">
          </div>
          <div class="form-group">
            <label for="web">Web oficial</label>
            <input type="text" class="form-control" id="web" name="web" value="${ publication.web || '' }" placeholder="web">
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="disabled" name="disabled">
              <label class="custom-control-label" for="disabled">Deshabilitado</label>
            </div>
          </div>
          <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado">
              <option value="">Seleccione una opción</option>
            </select>
          </div>
          <div class="form-group">
            <label for="municipio">Municipio</label>
            <select class="form-control" id="municipio" name="municipio">
              <option value="">Seleccione una opción</option>
            </select>
          </div>
          <div class="form-group">
            <label for="section">Sección<span  class="text-danger font-weight-bolder">*</span></label>
            <select class="form-control" id="section" name="section">
              <option value="">Seleccione una opción</option>
            </select>
          </div>
          <div id="tags" class="form-group"></div>
          <div class="form-group">
            <label for="visits">Visitas</label>
            <input type="number" class="form-control" id="visits" name="visits" value="${ publication.visits || 0 }" placeholder="0">
          </div>
          <div class="form-group">
            <label for="likes">Likes</label>
            <input type="number" class="form-control" id="likes" name="likes" value="${ publication.likes || 0 }" placeholder="0">
          </div>
        </form>`;
      $('#modalGral-body').html(html);
    },

    savePublication: function(callback){
      let url = admin.publications.urlAdd;
      let hashPublication = admin.publications.publication?.hashPublication || 0;
      let formData = new FormData($('#frm-publication')[0]);
      //formData.append('title', $('#title').val());

      formData.append('hashPublication', hashPublication);
      
      $.each($('#gallery')[0].files, function(index, file) {
        formData.append('gallery[]', file);
      });

      $.each(admin.publications.galleryDel, function(index, item) {
        formData.append('galleryDel[]', item);
      });

      utils.sendAjaxJQ(url, formData, function(res){
        if(res['success']){
          admin.publications.cleanData();
          if(callback && (typeof callback === 'function')){
            return callback(res);
          }
        }
      });
    },

    cleanData: function(){
      galleryDel = [];
    }
  }
};