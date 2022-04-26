var $modal;
var $image;
var cropper;
var canvas;

let stab = {
  init: (cfg)=>{
    cfg = (typeof cfg === 'object') ? cfg : {};
    let urlLoadStab = cfg.urlLoadStab ? cfg.urlLoadStab  : '';
    let urlUpdateStablishment = cfg.urlUpdateStablishment ? cfg.urlUpdateStablishment  : '';
    let urlAsset = cfg.urlAsset ? cfg.urlAsset  : "";
    /*let urlLoadProducts = cfg.urlLoadProducts ? cfg.urlLoadProducts : '';
    let urlDelProduct = cfg.urlDelProduct ? cfg.urlDelProduct : '';*/

    stab.modalCreate(urlLoadStab);
    stab.loadStab(urlLoadStab, function(res){
      res.urlAsset = urlAsset;
      stab.setStab(res);
    });

    $modal = $('.imagecrop');
    $image = document.getElementById('image');

    $(document).off("change", "#logotipo");
    $(document).on('change', '#logotipo', function(e){
      //stab.loadPrevLogotipo(this);
      //stab.loadEditLogotipo(e);
      stab.initLoadFile(e);
    });

    $(document).off("shown.bs.modal", ".imagecrop").off("hidden.bs.modal", ".imagecrop");
    $(document).on("shown.bs.modal", '.imagecrop', function(){
      console.log("llamando a initCropper: ");
      stab.initCropper();
    }).on('hidden.bs.modal', '.imagecrop', function(){
      console.log("destruyendo: cropper", cropper);
      cropper && cropper.destroy();
      cropper = null;
    });

    $(document).off("click", '#crop');
    $(document).on('click', '#crop', function(){
      console.log("llamando a cropImage: ");
      stab.cropImage();
    });

    $(document).off("click", "#btn-stab-frm");
    $(document).on('click', '#btn-stab-frm', (e)=>{
      e.preventDefault();
      stab.storeStab(urlUpdateStablishment);
    });
  },
  modalCreate: (url)=>{
    $('#window-modal .modal-title').text('DATOS DE EMPRESA');
    let html = `
      <div class="col-12 col-md-8">
        <form id="stabFrm" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="nombre">Nombre <span class="text-danger font-weight-bolder">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="" placeholder="Nombre del establecimiento">
            <div id="nombre-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción <span  class="text-danger font-weight-bolder">*</span></label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del establecimiento"></textarea>
            <div id="descripcion-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="descripcion2">Descripción 2 (mapa)</label>
            <input type="text" class="form-control" id="descripcion2" name="descripcion2" value="" placeholder="Descripción para el mapa">
            <div id="descripcion2-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="direccion">Dirección <span  class="text-danger font-weight-bolder">*</span></label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="" placeholder="Av. Villanueva, Col. San Juán, no. 55, C.P. 55450">
            <div id="direccion-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="latitud">Latitud</label>
            <input type="text" class="form-control" id="latitud" name="latitud" value="" placeholder="Latitud del establecimiento">
            <div id="latitud-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="longitud">Longitud</label>
            <input type="text" class="form-control" id="longitud" name="longitud" value="" placeholder="Longitud del establecimiento">
            <div id="longitud-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <div class="col-8 col-md-3">
              <div id="prev-logotipo" style="background-image: url(http://i.pravatar.cc/500?img=7) background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
            </div>
          </div>
          <div class="form-group">
            <label for="logotipo">Logotipo (dimenciones entre 90px y 110px de ancho y 55px y 75px de alto. Peso máximo de 200kb.)</label>
            <input type="file"  class="form-control-file" id="logotipo" name="logotipo" accept=".png, .jpg, .jpeg">
            <input type="hidden" id="logotipoBase64" name="logotipoBase64">
            <div id="logotipo-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="telefono">Teléfono (máximo 13 carácteres)</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="" min="0" max="9999999999999" step="1" placeholder="5555555555">
            <div id="telefono-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="whatsapp">Whatsapp (máximo 13 carácteres)</label>
            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="" min="0" max="9999999999999" step="1" placeholder="5555555555">
            <div id="whatsapp-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="facebook">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="facebook" placeholder="Facebook">
            <div id="facebook-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="instagram">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="instagram" placeholder="Instagram">
            <div id="instagram-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" id="twitter" name="twitter" value="twitter" placeholder="Twitter">
            <div id="twitter-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="youtube">Youtube</label>
            <input type="text" class="form-control" id="youtube" name="youtube" value="youtube" placeholder="Youtube">
            <div id="youtube-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="horario">Hoario <span  class="text-danger font-weight-bolder">*</span></label>
            <input type="text" class="form-control" id="horario" name="horario" value="horario" placeholder="08:00 am - 08:00 pm">
            <div id="horario-error" class="error" style="display: block;"></div>
          </div>

          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="oferta" name="oferta">
              <label class="custom-control-label" for="oferta">Oferta</label>
            </div>
          </div>

          <div class="form-group">
            <label for="zona">Zona <span  class="text-danger font-weight-bolder">*</span></label>
            <select class="form-control" id="zona" name="zona">
              <option value="">Seleccione una opción</option>
            </select>
            <div id="zona-error" class="error" style="display: block;"></div>
          </div>

          <div class="form-group">
            <label for="section">Sección <span  class="text-danger font-weight-bolder">*</span></label>
            <select class="form-control" id="section" name="section">
              <option value="">Seleccione una opción</option>
            </select>
            <div id="section-error" class="error" style="display: block;"></div>
          </div>

          <div id="tags" class="form-group"></div>
          <hr>
          <div class="form-group">
            <button id='btn-stab-frm' type="submit" class="btn btn-primary">Aceptar</button>
          </div>
        </form>
      </div>

      <div class="modal imagecrop fade" id="window-modal-croop" tabindex="-1" role="dialog" aria-labelledby="window-modal-croop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Editar imagen</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="img-container">
                <div class="row">
                  <div class="col-md-11">
                    <img id="image" src="" style="display: block; max-width: 100%;">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary crop" id="crop">Recortar imagen</button>
            </div>
          </div>
        </div>
      </div>`;
    $('#window-modal .modal-body').html(html);
  },

  loadStab: (url, callback)=>{
    let data = {};
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
/**
 * Inserta los valores de la empresa en el formulario de empresa
 * @param {json} Datos de la empresa y de los catálogos
 * @return  null
 */
  setStab: (data)=>{
    let stab = data.stab;
    let urlAsset = data.urlAsset;
    let zones = data.zones;
    let sections = data.sections;
    let tags = data.tags;
    let stabTags = data.stab_tags;
    let option;
    let sel;
    let checked;
    $('#nombre').val(stab.name);
    $('#descripcion').val(stab.description);
    $('#descripcion2').val(stab.description2);
    $('#direccion').val(stab.direction);
    $('#latitud').val(stab.lat);
    $('#longitud').val(stab.lng);
    $('#telefono').val(stab.phone);
    $('#whatsapp').val(stab.whatsapp);
    $('#facebook').val(stab.facebook);
    $('#instagram').val(stab.instagram);
    $('#twitter').val(stab.twitter);
    $('#youtube').val(stab.youtube);
    $('#horario').val(stab.hour);

    // 
    let url = `${urlAsset}${stab.image}`;
    console.log('url: ', url);
    $('#prev-logotipo').css("background-image", `url(${url})`);
    console.log(url);

    stab.offer && $('#oferta').prop('checked', 'checked');
    for(let z in zones){
      sel = zones[z].idzone == stab.zone_id ? 'selected' : '';
      option = `<option value="${zones[z].idzone}" ${sel}>${zones[z].name}</option>`;
      $('#zona').append(option);
    }

    for(let s in sections){
      sel = sections[s].idsection == stab.zone_id ? 'selected' : '';
      option = `<option value="${sections[s].idsection}" ${sel}>${sections[s].name}</option>`;
      $('#section').append(option);
    }

    for(let t in tags){
      checked = '';
      for(let st in stabTags){
        if(tags[t].idtag == stabTags[st]){
          checked = 'checked="checked"';
          break;
        }
      }
      check = `
        <div class="custom-control custom-checkbox custom-control-inline">
          <input type="checkbox" id="${tags[t].idtag}" name="tags[]" value="${tags[t].idtag}" ${checked} class="custom-control-input">
          <label for="${tags[t].idtag}" class="custom-control-label">${tags[t].name}</label>
        </div>`;
      $('#tags').append(check);
    }
  },
  initLoadFile: function(e){
    //stab.loadPrevLogotipo(this);
    //stab.loadEditLogotipo(e);
    var files = e.target.files;
    var done = function(url){
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if(files && files.length > 0){
      file = files[0];
      if(URL){
        done(URL.createObjectURL(file));
      }else if(FileReader){
        reader = new FileReader();
        reader.onload = function(e){
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  },
  initCropper: function(){
    // https://github.com/fengyuanchen/cropperjs/blob/main/README.md
    if(!cropper){
      cropper = new Cropper($image, {
        aspectRatio: 1.5,
        viewMode: 3,
        autoCropArea: 0.5,
      });
    }
    console.log("cropper: ", cropper);
  },
  cropImage: function(){
    canvas = cropper.getCroppedCanvas({
      width: 150,
      heigth: 100
    });      
    console.log("canvas, cropper: ", canvas, cropper);
    canvas.toBlob(function(blob){
      url = URL.createObjectURL(blob);
      reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function(){
        var base64data = reader.result;
        $('#logotipoBase64').val(base64data);
        document.getElementById('prev-logotipo').style.backgroundImage = "url("+base64data+")";
        $modal.modal('hide');
      };
    });
  },
/**
  * Construye la previsualización de la imagen del logotipo
  * @return  null
  */
  loadPrevLogotipo: (input)=>{
    //let reader;
    if(input.files && input.files[0]){
      var reader = new FileReader();
      reader.onload = function(e){
        $('#prev-logotipo').css('background-image', 'url('+e.target.result+')');
      };
      reader.readAsDataURL(input.files[0]);
    }
  },
  /**
   * Construye el editor para editar la imagen del logotipo
   * @return  null
   */
  loadEditLogotipo: (e)=>{
    let files;
    let modal;
    let image;
    let done;
    files = e.target.files;
    done = function(url){
      image.url = url;
      modal.modal('show');
    };
  },
  storeStab: (url, callback)=>{
    let formData = new FormData();
    let files = $('#logotipo')[0].files[0];
    formData.append('file',files);
    formData.append('logotipoBase64', $('#logotipoBase64').val());
    formData.append('nombre', $('#nombre').val());
    formData.append('descripcion', $('#descripcion').val());
    formData.append('descripcion2', $('#descripcion2').val());
    formData.append('direccion', $('#direccion').val());
    formData.append('latitud', $('#latitud').val());
    formData.append('longitud', $('#longitud').val());
    formData.append('telefono', $('#telefono').val());
    formData.append('whatsapp', $('#whatsapp').val());
    formData.append('facebook', $('#facebook').val());
    formData.append('instagram', $('#instagram').val());
    formData.append('twitter', $('#twitter').val());
    formData.append('youtube', $('#youtube').val());
    formData.append('horario', $('#horario').val());
    formData.append('oferta', $('#oferta').val());
    formData.append('zona', $('#zona').val());
    formData.append('section', $('#section').val());

    $("#tags input:checkbox:checked").each(function() {
      formData.append('tags[]', $(this).val());
    });

    utils.sendAjaxJQ(url, formData, function(res){
      utils.toastr({'type': res.code, 'message': res.message});
      console.log('res: ', res);
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }else{
        if(res['errors']){
          console.log('res2', res['errors']);
          utils.validateSetErrors(res['errors']);
        }
      }
    });
  },
  /*createSelectMenu: (url)=>{
    menus.loadMenus(url, (res)=>{
      menus.setMenus(res['menus']);
    });
  },
  selectedMenu: (url, hashMenu, callback)=>{
    let data = { 'hash': hashMenu };
    let cfg, menu;
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        menu = res['menus'];
        cfg = {
          'hash': menu.hash ? menu.hash : false,
          'title': menu.name ? menu.name : '',
          'name': menu.name ? menu.name : '',
          'description': menu.description ? menu.description : '',
          'disabled': menu.disabled ? menu.disabled : 0
        };
        menus.createMenuAccordion(cfg);
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
  
  setMenus: (menus)=>{
    let select = `<select id="menuList" class="custom-select">#OPTIONS#</select>`;
    let options = `<option value="${'hash_'+utils.hash1()}" selected>Nuevo Menú</option>`;
    for(let menu in menus){
      options += `
        <option value="${menus[menu].hash}">${menus[menu].name}</option>`;
    }
    select = select.replace('#OPTIONS#', options);
    $('#menuListCont').html(select);
  },
  createMenuAccordion: (menu)=>{
    menu = (typeof menu === 'object') ? menu : {};
    let hash = menu.hash ? menu.hash : 'hash_'+utils.hash1();
    let btnName = (menu.hash ? 'Actualizár' : 'Nuevo')+' menú';
    let title = menu.title ? menu.title : 'Nuevo menú';
    let name = menu.name ? menu.name : '';
    let description = menu.description ? menu.description : '';
    let disabled = menu.disabled ? 'checked' : '';
    let html = `
      <div class="accordion" id="menuAccordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h2 class="mb-0">
              <button id="btnOpenMenu" class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="newMenu">
                ${title}
              </button>
            </h2>
          </div>
          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#menuAccordion">
            <div class="card-body">
              <form id="menuFrm">
                <input type="hidden" class="form-control" id="hashMenu" name="hashMenu" value="${hash}"">

                <div class="row"> <!-- start row de menu -->
                  <div class="col-12 col-md-7">
                    <div class="form-group">
                      <label for="menuName">Nombre <span  class="text-danger font-weight-bolder">*</span></label>
                      <input type="text" class="form-control" id="menuName" name="menuName" value="${name}" placeholder="Nombre del menú">
                      <div id="menuName-error" class="invalid-feedback" style="display: block;"></div>
                    </div>
                    <div class="form-group">
                      <label for="menuDescripcion">Descripción</label>
                      <textarea class="form-control" id="menuDescripcion" name="menuDescripcion" rows="3" placeholder="Descripción del menú">${description}</textarea>
                      <div id="menuDescripcion-error" class="invalid-feedback" style="display: block;"></div>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="menuDisable" name="menuDisable" ${disabled}>
                        <label class="custom-control-label" for="menuDisable">Deshabilitado</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-5 d-flex justify-content-center align-items-center">
                    <a href="#" class="btn btn-purple" id="addMenu">
                      <h1>${btnName}</h1>
                    </a>
                  </div>
                </div> <!-- end row de menu -->

                <div class="row"> <!-- start row de productos -->
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 py-3 rounded-lg" style="background-color: #f2f2f2; border: 2px solid #cecece;;">
                    <h3 class="text-center">PRODUCTOS</h3>
                    <div id="productosDiv"></div>
                    <div class="text-center py-3">
                      <a href="" id="addProduct" class="btn btn-purple" style="font-size: 20px; letter-spacing: 2px;">AGREGAR PRODUCTO</a>
                    </div>
                  </div>
                </div> <!-- ent row de productos -->
              </form>
            </div>
          </div>
        </div>
      </div>`;
    $('#menuAccordionCont').html(html);
  },
  addProduct: (data)=>{
    data = (typeof data === 'object') ? data : {};
    let name = data.name ? data.name : '';
    let price = data.price ? data.price : '';
    let description = data.description ? data.description : '';
    let disabled = data.disabled ? data.disabled : 0;
    let hash = data.hash ? data.hash : 'hash_'+utils.hash1();

    let html = `
      <div id="product-${hash}">
        <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <label for="prod-name-${hash}">Producto <span  class="text-danger font-weight-bolder">*</span></label>
            <input type="text" class="form-control" id="prod-name-${hash}" name="prod-name-${hash}" value="${ name }" placeholder="Nombre del producto">
            <div id="prod-name-${hash}-error" class="invalid-feedback" style="display: block;"></div>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
            <label for="prod-precio-${hash}">Precio <span  class="text-danger font-weight-bolder">*</span></label>
            <input type="number" class="form-control" id="prod-precio-${hash}" name="prod-precio-${hash}" value="${ price }" placeholder="Precio del producto">
            <div id="prod-precio-${hash}-error" class="invalid-feedback" style="display: block;"></div>
          </div>
          <div class="col-xs-4 col-sm-4 col-md-64col-lg-4 col-xl-4">
            <label for="prod-description-${hash}">Descripción</label>
            <input type="text" class="form-control" id="prod-description-${hash}" name="prod-description-${hash}"value="${ description }" placeholder="Descripción del producto">
            <div id="prod-description-${hash}-error" class="invalid-feedback" style="display: block;"></div>
          </div>
          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="display: flex; align-items: center;">
            <a id="delProduct-${ hash }" class="delProduct" href="#" style="color: #f00; font-size: 40px;">
              <i class="far fa-times-circle"></i>
            </a>
          </div>
          <hr>
        </div>
      </div>`;

      $('#productosDiv').append(html);
  },
  delProduct: (url, hashProduct)=>{
    let data = { 'hashProduct': hashProduct };
    if(hashProduct.search('hash')>=0){
      $('#product-'+hashProduct).fadeOut('fast', function(){
        $('#product-'+hashProduct).remove();
      });
    }else{
      utils.sendAjax(url, data, function(res){
        utils.toastr({'type': res.code, 'message': res.message});
        if(res['success']){
          $('#product-'+hashProduct).fadeOut('fast', function(){
            $('#product-'+hashProduct).remove();
          });
          if(callback && (typeof callback === 'function')){
            callback(res);
          }
        }
      });
    }
  },
  addMenu: (url, callback)=>{
    let idFrm = 'menuFrm';
    let data = $(`#${idFrm}`).serializeArray();
    utils.sendAjax(url, data, function(res){
      utils.toastr({'type': res.code, 'message': res.message});
      if(res['success']){
        $(`#${idFrm}`)[0].reset();
        $('#collapseOne').collapse('toggle');
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }else if(res['validate']){
        utils.validateSetErrors(res['validate']);
        console.log('mostrando errores de validacion: ', res);
      }
    });
  },
  loadProducts: (url, hashMenu, callback)=>{
    let data = { 'hashMenu': hashMenu };
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  }*/
}
