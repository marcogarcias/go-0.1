var $modal;
var $image;
var cropper;
var canvas;

let cv = {
  init: (cfg)=>{
    cfg = (typeof cfg === "object") ? cfg : {};
    let urlGetJobTypes = cfg.urlGetJobTypes ? cfg.urlGetJobTypes : false;
    let urlGetJobSubTypes = cfg.urlGetJobSubTypes ? cfg.urlGetJobSubTypes : false;
    let urlLoadCv = cfg.urlLoadCv ? cfg.urlLoadCv  : "";
    let urlAddCv = cfg.urlAddCv ? cfg.urlAddCv : false;
    let urlUpdateCv = cfg.urlUpdateCv ? cfg.urlUpdateCv  : "";
    let urlAsset = cfg.urlAsset ? cfg.urlAsset  : "";

    cv.modalCreate();
    cv.loadJobTypes(urlGetJobTypes, function(res){
      cv.setJobTypes(res);
      cv.loadCv(urlLoadCv, function(res){
        res.urlAsset = urlAsset;
        cv.setCv(res);
      });
    });

    $(document).off("change", "#jobType");
    $(document).on("change", "#jobType", function (event) {
      let hashJobType = $(this).val();
      go.loadAjaxPost(urlGetJobSubTypes, {hashJobType: hashJobType}, function(res){
        if(res.success && res.data){
          html="";
          for(let sub in res.data){
            html += ""+
              "<div class='custom-control custom-checkbox custom-control-inline'>"+
                "<input type='checkbox' class='custom-control-input' id='"+res.data[sub].hashJobSubType+"' name='tags[]' value='"+res.data[sub].hashJobSubType+"'>"+
                "<label class='custom-control-label' for='"+res.data[sub].hashJobSubType+"'>"+res.data[sub].name+"</label>"+
              "</div>";
          }
          $("#subTypes").html(html);
        }
      });
    });

    $modal = $('.imagecrop');
    $image = document.getElementById('image');

    $(document).off("change", "#logotipo");
    $(document).on('change', '#logotipo', function(e){
      cv.initLoadFile(e);
    });

    /*$(document).off("shown.bs.modal", ".imagecrop").off("hidden.bs.modal", ".imagecrop");
    $(document).on("shown.bs.modal", '.imagecrop', function(){
      console.log("llamando a initCropper: ");
      stab.initCropper();
    }).on('hidden.bs.modal', '.imagecrop', function(){
      console.log("destruyendo: cropper", cropper);
      cropper && cropper.destroy();
      cropper = null;
    });*/

    /*$(document).off("click", '#crop');
    $(document).on('click', '#crop', function(){
      console.log("llamando a cropImage: ");
      stab.cropImage();
    });*/
    $(document).off("click", "#btn-cv-frm");
    $(document).on('click', '#btn-cv-frm', (e)=>{
      e.preventDefault();
      cv.storeCv(urlAddCv);
    });
  },
  modalCreate: ()=>{
    $('#window-modal').modal({
      backdrop: true,
      keyboard: false
    });
    $("#window-modal .modal-title").text("CURRICULUM VITAE");
    let html = `
      <div class="col-12">
        <form id="cvFrm" action="" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 col-md-6 form-group">
              <label for="name">Nombre <span class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="name" name="name" value="" placeholder="Nombre de usuario">
              <div id="name-error" class="error" style="display: block;"></div>
            </div>
            <div class="col-12 col-md-6 form-group">
              <label for="nextName">Segundo nombre</label>
              <input type="text" class="form-control" id="nextName" name="nextName" value="" placeholder="Segundo nombre del usuario">
              <div id="nextName-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 form-group">
              <label for="ap">Apellido paterno <span class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="ap" name="ap" value="" placeholder="Apellido paterno">
              <div id="ap-error" class="error" style="display: block;"></div>
            </div>
            <div class="col-12 col-md-6 form-group">
              <label for="am">Apellido materno</label>
              <input type="text" class="form-control" id="am" name="am" value="" placeholder="Apellido materno">
              <div id="am-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 form-group">
              <label for="email">Correo electrónico <span class="text-danger font-weight-bolder">*</span></label>
              <input type="email" class="form-control" id="email" name="email" value="" placeholder="Correo electrónico">
              <div id="email-error" class="error" style="display: block;"></div>
            </div>
            <div class="col-12 col-md-6 form-group">
              <label for="cellphone">Celular <span class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="cellphone" name="cellphone" value="" placeholder="55-0000-0000">
              <div id="cellphone-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 form-group">
              <label for="age">Edad <span class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="age" name="age" value="" placeholder="00">
              <div id="age-error" class="error" style="display: block;"></div>
            </div>
            <div class="col-12 col-md-6 form-group">
              <label for="gender">Género <span class="text-danger font-weight-bolder">*</span></label>
              <select id="gender" name="gender" class="custom-select">
                <option selected>Selecciona una opción</option>
                <option value="h">Hombre</option>
                <option value="m">Mujer</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-12 form-group">
              <label for="description">Descripción <span class="text-danger font-weight-bolder">*</span></label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="Descríbete un poco"></textarea>
              <div id="description-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 form-group">
              <label for="cadademicH">Historial académico <span class="text-danger font-weight-bolder">*</span></label>
              <textarea class="form-control" id="cadademicH" name="cadademicH" rows="6" placeholder="Historial académico"></textarea>
              <div id="cadademicH-error" class="error" style="display: block;"></div>
            </div>
            <div class="col-12 col-md-6 form-group">
              <label for="jobH">Historial laboral <span class="text-danger font-weight-bolder">*</span></label>
              <textarea class="form-control" id="jobH" name="jobH" rows="6" placeholder="Historial laboral"></textarea>
              <div id="jobH-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <!-- USAR LA LIBRERÍA: https://blueimp.github.io/JavaScript-Load-Image/ -->
          <!-- 
          <div class="form-group">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="photo">
              <input type="hidden" id="photoB64" name="photoB64">
              <label class="custom-file-label" for="photo">Cargar foto</label>
            </div>
          </div>
          <div class="form-group">
            <div class="col-8 col-md-3">
              <div id="prev-logotipo" style="background-image: url(http://i.pravatar.cc/500?img=7) background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
            </div>
          </div> -->
          <div class="row">
            <div class="col-12 form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="disabled" name="disabled" checked>
                <label class="custom-control-label" for="disabled">Habilitar</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 form-group">
              <label for="jobType">Tipo de empleo <span class="text-danger font-weight-bolder">*</span></label>
              <select name="jobType" id="jobType" class="custom-select"></select>
              <div id="jobType-error" class="error" style="display: block;"></div>
            </div>
          </div>
          <div class="row">
            <div id="subTypes" class="col-12 form-group"></div>
          </div>
          <hr>
          <div class="form-group">
            <button id='btn-cv-frm' type="submit" class="btn btn-primary">Aceptar</button>
          </div>
        </form>
      </div>`;
    $('#window-modal .modal-body').html(html);
  },
  // carga los tipos de empleos del catalogo
  loadJobTypes: (url, callback)=>{
    let data = {};
    go.loadAjaxPost(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }else{
        if(callback && (typeof callback === 'function')){
          callback(false);
        }
      }
    });
  },
  // crea un elemento select con los tipos de empleos
  setJobTypes: (jobs, callback)=>{
    jobs = jobs.data;
    let html = "<option value=''>Elige una opción</option>";
    let job;
    if(jobs.length){
      for(let j in jobs){
        job = jobs[j];
        html += `
          <option value="${job["hashJobType"]}">${job["name"]}</option>`;
      }
      $("#jobType").html(html);
    }
  },
  loadCv: (url, callback)=>{
    let data = {};
    utils.sendAjax(url, data, function(res){
      console.log("2");
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
/**
 * Inserta los valores del cv en el formulario de cv
 * @param {json} Datos del cv
 * @return  null
 */
  setCv: (data)=>{
    return true;
    let cv = data.cv;
    let urlAsset = data.urlAsset;
    let option;
    let sel;
    let checked;
    $("#name").val(cv.name);
    $("#nextName").val(cv.nextName);
    $("#ap").val(cv.ap);
    $("#am").val(cv.am);
    $("#email").val(cv.email);
    $("#cellphone").val(cv.cellphone);
    $("#age").val(cv.age);
    $("#gender").val(cv.gender);
    $("#description").val(cv.description);
    $("#academicH").val(cv.academicH);
    $("#jobH").val(cv.jobH);
    $("#photo").val(cv.photo);

    let url = `${urlAsset}${cv.image}`;
    $('#prev-cv').css("background-image", `url(${url})`);

    cv.offer && $('#oferta').prop('checked', 'checked');
    cv.disabled || $('#habilitado').prop('checked', 'checked');
    for(let z in zones){
      sel = zones[z].idzone == cv.zone_id ? 'selected' : '';
      option = `<option value="${zones[z].idzone}" ${sel}>${zones[z].name}</option>`;
      $('#zona').append(option);
    }

    for(let s in sections){
      sel = sections[s].idsection == cv.section_id ? 'selected' : '';
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
  storeCv: (url, callback)=>{
    let formData = new FormData();
    //let files = $('#logotipo')[0].files[0];
    //formData.append('file',files);
    //formData.append('logotipoBase64', $('#logotipoBase64').val());
    formData.append('name', $('#name').val());
    formData.append('nextName', $('#nextName').val());
    formData.append('ap', $('#ap').val());
    formData.append('am', $('#am').val());
    formData.append('email', $('#email').val());
    formData.append('cellphone', $('#cellphone').val());
    formData.append('age', $('#age').val());
    formData.append('gender', $('#gender').val());
    formData.append('description', $('#description').val());
    formData.append('cadademicH', $('#cadademicH').val());
    formData.append('jobH', $('#jobH').val());
    formData.append('disabled', $('#disabled').val());
    formData.append('jobType', $('#jobType').val());

    $("#subTypes input:checkbox:checked").each(function() {
      formData.append('subTypes[]', $(this).val());
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
};