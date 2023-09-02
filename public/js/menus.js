
let menus = {
  init: (cfg)=>{
    cfg = (typeof cfg === 'object') ? cfg : {};
    let urlAddMenu = cfg.urlAddMenu ? cfg.urlAddMenu  : '';
    let urlLoadMenus = cfg.urlLoadMenus ? cfg.urlLoadMenus  : '';
    let urlLoadProducts = cfg.urlLoadProducts ? cfg.urlLoadProducts : '';
    let urlDelProduct = cfg.urlDelProduct ? cfg.urlDelProduct : '';
    let urlAddMenuObj = cfg.urlAddMenuObj ? cfg.urlAddMenuObj : '';
    let haveMenu = cfg.haveMenus ? parseInt(cfg.haveMenus) : 0;

    menus.modalCreate(urlLoadMenus);
    /*$('#btnMenus').on('click', (e)=>{
      menus.modalCreate(urlLoadMenus);
    });*/
    $(document).off("change", "#menuList");
    $(document).on('change', '#menuList', function(){
      menus.selectedMenu(urlLoadMenus, $(this).val());
    });

    $(document).off("shown.bs.collapse", "#collapseOne");
    $(document).on('shown.bs.collapse', '#collapseOne', function () {
      $('#productosDiv').empty();
      let hash = $('#hashMenu').val();
      if(hash.search('hash')>=0){
        // agregando productos vacios
        for(let i=0; i<5; i++){
          menus.addProduct();
        }
      }else{
        // agregando productos desde la base de datos
        menus.loadProducts(urlLoadProducts, hash, (res)=>{
          let products = res['products'];
          for(let product in products){
            menus.addProduct(products[product]);
          }
        });
      }
    });

    $(document).off("hidden.bs.collapse", "#collapseOne");
    $(document).on('hidden.bs.collapse', '#collapseOne', function () {
      $('#productosDiv').empty();
    });

    $(document).on("change", "#menuDisable", function(){
      let txt;
      if($(this).is(':checked')){
        txt = "Habilitado";
      }else{
        txt = "Deshabilitado";
      }
      $("#menuDisableLabel").text(txt);
      console.log("change", txt);
    });

    $(document).off("click", "#addProduct");
    $(document).on('click', '#addProduct', function(e){
      e.preventDefault();
      menus.addProduct();
    });

    $(document).off("click", ".delProduct");
    $(document).on('click', '.delProduct', function(e){
      e.preventDefault();
      let hashProduct = $(this).attr('id').split('-');
      menus.delProduct(urlDelProduct, hashProduct[1]);
    });

    $(document).off("click", "#addMenu");
    $(document).on('click', '#addMenu', (e)=>{
      e.preventDefault();
      menus.addMenu(urlAddMenu, (res)=>{
        //menus.createSelectMenu(urlLoadMenus);
        menus.modalCreate(urlLoadMenus);
        if(!haveMenu)
          location.reload();
      });
    });

    $(document).off("click", "#menuUpload");
    $(document).on('click', '#menuUpload', (e)=>{
      e.preventDefault();
      menus.addMenuObj({url: urlAddMenuObj, selector: "#menuPdf"}, function(res){
        console.log("enviando menuObj 4", res);
      });
    });
  },
  modalCreate: (url)=>{
    $('#window-modal').modal({
      backdrop: true,
      keyboard: false
    });
    $('#window-modal .modal-title').text('Menú');
    let html = `
      <div class="row mb-3">
        <div class="col-12">
          <div id="menuListCont"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div id="menuAccordionCont"></div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-12 col-md-3">
          <input type="file" id="menuPdf" class="form-control-file" name="menuPdf" accept=".png, .jpg, .jpeg, .pdf">
        </div>
        <div class="col-12 col-md-2">
          <a id="menuUpload" class="btn btn-secondary" href="#">Subir menú</a>
        </div>
      </div>`;
    $('#window-modal .modal-body').html(html);
    menus.createSelectMenu(url);
    menus.createMenuAccordion({'title': 'Crear Menú'});
  },
  createSelectMenu: (url)=>{
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
          'disabled': Number(menu.disabled) ? Number(menu.disabled) : 0
        };
        menus.createMenuAccordion(cfg);
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
  loadMenus: (url, callback)=>{
    let data = {};
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
  setMenus: (menus)=>{
    let select = `<select id="menuList" class="custom-select">#OPTIONS#</select>`;
    let options = `<option value="${'hash_'+utils.hash1()}" selected>Lista de menus</option>`;
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
    let btnName = (menu.hash ? 'Actualizár' : 'Subir')+' menú';
    let title = menu.title ? menu.title : 'Nuevo menú';
    let name = menu.name ? menu.name : '';
    let description = menu.description ? menu.description : '';
    let disabled = Number(menu.disabled) ? '' : 'checked';
    let disabledLabel = Number(menu.disabled) ? "Deshabilitado" : "Habilitado";
    
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
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="menuDisable" name="menuDisable" ${disabled}>
                        <label id="menuDisableLabel" class="custom-control-label" for="menuDisable">${disabledLabel}</label>
                      </div>
                    </div>
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
                  </div>
                  <div class="col-12 col-md-5 d-flex justify-content-center align-items-center"></div>
                </div> <!-- end row de menu -->

                <div class="row"> <!-- start row de productos -->
                  <div class="col-12 py-3 rounded-lg" style="background-color: #f2f2f2; border: 2px solid #cecece;;">
                    <h3 class="text-center">PRODUCTOS</h3>
                    <div id="productosDiv"></div>
                    <div class="text-center py-3">
                      <a href="" id="addProduct" class="btn btn-purple" style="font-size: 20px; letter-spacing: 2px;">AGREGAR PRODUCTO</a>
                    </div>
                  </div>
                  <div class="col-12 d-flex justify-content-center align-items-center mt-4">
                    <a href="#" class="btn btn-purple" id="addMenu">
                      <h1>${btnName}</h1>
                    </a>
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
          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 d-flex justify-content-center justify-items-center align-items-center text-center">
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
  },
  addMenuObj: function(cfg, callback){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let url = cfg.url ? cfg.url : false;
    let selector = cfg.selector ? cfg.selector : false;
    let file = $(selector).prop("files")[0];
    let formData = new FormData();
    formData.append("menuObj", file);
    console.log("enviando menuObj 1", file);

    utils.sendAjaxJQ(url, formData, function(res){
      console.log("enviando menuObj 2", res);
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          console.log("enviando menuObj 3");
          callback(res);
        }
      }
    }, {enctype: "multipart/form-data"});
  }
}
