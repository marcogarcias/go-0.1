var $modal;
var $image;
var cropper;
var canvas;
let urlAsset;

let gallery = {
  noImageNow: 0,
  noImage: 1,
  maxImage: 4,
  initStabGallery: ()=>{
    // inicializando los anuncios del home
    $('.gallery-list-a').on('click', function(){
      let img = $(this).attr('data-img');
      let name = $(this).attr('data-name');
      $('#gallery-img').attr('src', img);
      $('#gallery-img').attr('title', name);
      $('#gallery-img').attr('alt', name);
    });
  },
  init: (cfg)=>{
    cfg = (typeof cfg === 'object') ? cfg : {};
    let urlLoadGallery = cfg.urlLoadGallery ? cfg.urlLoadGallery : '';
    let urlStoreGallery = cfg.urlStoreGallery ? cfg.urlStoreGallery : '';
    urlAsset = cfg.urlAsset ? cfg.urlAsset  : "";
    $modal="", $image="", cropper="", canvas="";
    gallery.modalCreate(urlLoadGallery);
    gallery.modalCreateCrop();

    $modal = $('.imagecrop');
    $image = document.getElementById('image');

    $(document).off("change", "input[id*='galleryImg']");
    $(document).on('change', "input[id*='galleryImg']", function(e){
      //let noImageNow_ = 
      let idA = $(this).attr("id").split("-");
      gallery.noImageNow = idA[1];
      gallery.initLoadFile(e);
    });

    $(document).off("shown.bs.modal", ".imagecrop").off("hidden.bs.modal", ".imagecrop");
    $(document).on("shown.bs.modal", '.imagecrop', function(){
      console.log("llamando a initCropper: ");
      gallery.initCropper();
    }).on('hidden.bs.modal', '.imagecrop', function(){
      console.log("destruyendo: cropper", cropper);
      cropper && cropper.destroy();
      cropper = null;
    });

    $(document).off("click", '#crop');
    $(document).on('click', '#crop', function(){
      console.log("llamando a cropImage: ");
      gallery.cropImage(gallery.noImageNow);
    });

    $(document).off("click", "#btn-gallery-frm");
    $(document).on('click', '#btn-gallery-frm', (e)=>{
      e.preventDefault();
      gallery.storeGallery(urlStoreGallery);
    });

    $(document).on('hidden.bs.modal', '#window-modal', function(){
      gallery.noImage = 1;
    });
  },
  modalCreate: (url)=>{
    $('#window-modal').modal({
      backdrop: true,
      keyboard: false
    });
    $('#window-modal .modal-title').text('Galería');
    let html = `
      <div class="col-12">
        <form id="galleryFrm" action="" enctype="multipart/form-data"></form>
        <hr>
        <div class="form-group">
          <button id='btn-gallery-frm' type="submit" class="btn btn-primary">Aceptar</button>
        </div>
      </div>`;
    $('#window-modal .modal-body').html(html);
    gallery.loadImages(url, (images)=>{
      gallery.setImages(images);

      let no = gallery.noImage;
      for(let x=0; x<4; x++)
        gallery.newImageInput("#galleryFrm");
    });

  },
  modalCreateCrop: ()=>{
    let html = `
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
              <button type="button" class="btn btn-primary crop" id="crop" data-noImage="0">Recortar imagen</button>
            </div>
          </div>
        </div>
      </div>`;
    $('#modalCrop').html(html);
  },
  loadImages: (url, callback)=>{
    let data = {};
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res.data);
        }
      }
    });
  },
  setImages: (images)=>{
    let url, image;
    const d = new Date();
    let ms;
    images.forEach(function(itm, idx){
      //console.log("2 setImages", itm, idx);
      ms = d.getMilliseconds();
      url = `${urlAsset}${itm.path}/${itm.image}?${ms}`;
      //console.log('3 setImages: ', url, itm);
      //$('#prev-logotipo').css("background-image", `url(${url})`);
      gallery.newImageInput("#galleryFrm", itm.hashGallery, url);
    });
  },
  newImageInput: (id, imageHash, image)=>{
    id = id ? id : "#galleryFrm";
    image = image ? image : '';
    imageHash = imageHash ? imageHash : "hash"+utils.hash1();;
    let num = gallery.noImage++;
    if(num <= gallery.maxImage){
      let html = `
      <div class="form-group">
        <div class="col-8 col-md-3">
          <div id="prev-imageBase64-${num}" class="prev-image" style="background-repeat: no-repeat; background-position: center center; background-image: url(${image}); background-size: cover;" data-hash="${imageHash}"></div>
        </div>
      </div>
      <div class="form-group">
        <label for="imageBase64-${num}">Imagen</label>
        <input type="file"  class="form-control-file" id="galleryImg-${num}" name="galleryImg-${num}" accept=".png, .jpg, .jpeg" data-hash="${imageHash}">
        <input type="hidden" id="imageBase64-${num}" name="imageBase64-${num}" data-hash="${imageHash}">
        <div id="imageBase64-error-${num}" class="error" style="display: block;"></div>
      </div>`;
      $(`${id}`).append(html);
    }
  },
  initLoadFile: function(e){
    var files = e.target.files;
    var done = function(url){
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
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
  cropImage: function(num){
    //console.log("cropImage: num", num);
    canvas = cropper.getCroppedCanvas({
      width: 900,
      heigth: 500
    });      
    console.log("canvas, cropper: ", canvas, cropper);
    canvas.toBlob(function(blob){
      url = URL.createObjectURL(blob);
      reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function(){
        var base64data = reader.result;
        $(`#imageBase64-${num}`).val(base64data);
        console.log(`cropImage: prev-imageBase64-${num}`);
        document.getElementById(`prev-imageBase64-${num}`).style.backgroundImage = "url("+base64data+")";
        $modal.modal('hide');
      };
    });
  },
  storeGallery: (url, callback)=>{
    let formData = new FormData();
    //let files = $('#imageBase64-')[0].files[0];
    //formData.append('file',files);
    let hash;
    $("input[id*='imageBase64']").each(function(x, y, z){
      if($(this).val()){
        hash = $(this).attr("data-hash");
        formData.append('imageBase64[]', $(this).val()+'|HASH|'+hash);
        //console.log("1 storeGallery: ", hash, formData);
      }
    });
    // validando que se haya cargado aunque sea una imagen
    if(hash){
      console.log("url", url);
      utils.sendAjaxJQ(url, formData, function(res){
        utils.toastr({'type': res.code, 'message': res.message});
        console.log('res: ', res);
        if(res['success']){
          //console.log("1 storeGallery: ", res);
          $('#window-modal').modal('toggle');
          if(callback && (typeof callback === 'function')){
            callback(res);
          }
        }else{
          if(res['errors']){
            //console.log('res2', res['errors']);
            utils.validateSetErrors(res['errors']);
          }
        }
      });
    }else{
      utils.toastr({"type": "warning", "message": "No se ha adjuntado ninguna imagen."});
    }
  },
}
