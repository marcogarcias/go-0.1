let social = {
  initStabSocial: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    $(document).on("click", "#btn-social", (e)=>{
      e.preventDefault();
      social.setSocialStab(cfg);
    });
  },
  /**
   * Inserta las redes sociales en la ventana modal de la sección del establecimiento
   * @param object $cfg Objecto que contiene la información necesaria de las redes sociales
   */
  setSocialStab: function(cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};

    let redesJson = {};
    let assets = cfg.assets ? cfg.assets : "/";
    cfg.facebook && (redesJson["facebook"] = cfg.facebook);
    cfg.instagram && (redesJson["instagram"] = cfg.instagram);
    cfg.twitter && (redesJson["twitter"] = cfg.twitter);
    cfg.youtube && (redesJson["youtube"] = cfg.youtube);
    cfg.web && (redesJson["web"] = cfg.web);

    console.log("assets", `${assets}/site/btn`);

    let redesHtml = "";
    let redesHtml_ = "";
    let block = `
      <div class="col-4 mt-4">
        <a href='#SOCIALHREF#' target='_blanck'>
          <img src="#SOCIALSRC#" title="#SOCIALALT#">
        </a>
      </div>`;

    for(let social in redesJson){
      redesHtml_ = block.replace("#SOCIALHREF#", "https://"+redesJson[social]);
      redesHtml_ = redesHtml_.replace("#SOCIALSRC#", `${assets}/site/btn/icon-${social}-01.png`);
      redesHtml_ = redesHtml_.replace("#SOCIALALT#", social.toUpperCase());
      redesHtml += redesHtml_;
    }

    $("#window-modal .modal-title").text("REDES SOCIALES");
    $("#window-modal .modal-body").html(`<div class="row mt-4 text-left social">${redesHtml}</div>`);
  },
  init: (cfg)=>{
    cfg = (typeof cfg === "object") ? cfg : {};
    let urlLoadSocial = cfg.urlLoadSocial ? cfg.urlLoadSocial  : "";
    let urlStoreSocial = cfg.urlStoreSocial ? cfg.urlStoreSocial  : "";
    let urlAsset = cfg.urlAsset ? cfg.urlAsset  : "";
    let urlDelSocial = cfg.urlDelSocial ? cfg.urlDelSocial : "";

    social.modalCreate(urlLoadSocial);
    social.loadSocial(urlLoadSocial, function(res){
      let data = res.data;
      social.setSocial(data);
    });

    $(document).off("click", "#btn-social-frm");
    $(document).on("click", "#btn-social-frm", (e)=>{
      e.preventDefault();
      social.storeSocial(urlStoreSocial);
    });
  },
  modalCreate: (url)=>{
    $('#window-modal').modal({
      backdrop: true,
      keyboard: false
    });
    $('#window-modal .modal-title').text('REDES SOCIALES');
    let html = `
      <div class="col-12 col-md-8">
        <form id="socialFrm" action="">
          <div class="form-group">
            <label for="facebook">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="" placeholder="Facebook">
            <div id="facebook-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="instagram">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="" placeholder="Instagram">
            <div id="instagram-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" id="twitter" name="twitter" value="" placeholder="Twitter">
            <div id="twitter-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="youtube">Youtube</label>
            <input type="text" class="form-control" id="youtube" name="youtube" value="" placeholder="Youtube">
            <div id="youtube-error" class="error" style="display: block;"></div>
          </div>
          <div class="form-group">
            <label for="web">Web oficial</label>
            <input type="text" class="form-control" id="web" name="web" value="" placeholder="wwww.miweb.com">
            <div id="web-error" class="error" style="display: block;"></div>
          </div>
          <hr>
          <div class="form-group">
            <button id='btn-social-frm' type="submit" class="btn btn-primary">Aceptar</button>
          </div>
        </form>
      </div>`;
    $('#window-modal .modal-body').html(html);
  },

  loadSocial: (url, callback)=>{
    let data = {
      "hashStab": $("#btn-socialmedia").attr("data-hashstab"),
    };
    utils.sendAjax(url, data, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
/**
 * Inserta los valores de las redes sociales en el formulario
 * @param {json} Datos de las redes spciales
 * @return  null
 */
  setSocial: (data)=>{
    let facebook = data.facebook ? data.facebook : "";
    let instagram = data.instagram ? data.instagram : "";
    let twitter = data.twitter ? data.twitter : "";
    let youtube = data.youtube ? data.youtube : "";
    let web = data.web ? data.web : "";

    $('#facebook').val(facebook);
    $('#instagram').val(instagram);
    $('#twitter').val(twitter);
    $('#youtube').val(youtube);
    $('#web').val(web);
  },

  storeSocial: (url, callback)=>{
    let data = {
      "hashStab": $("#btn-socialmedia").attr("data-hashstab"),
      "facebook": $("#facebook").val(),
      "instagram": $("#instagram").val(),
      "twitter": $("#twitter").val(),
      "youtube": $("#youtube").val(),
      "web": $("#web").val()
    };
    utils.sendAjax(url, data, function(res){
      utils.toastr({'type': res.code, 'message': res.message});
      if(res['success']){
        $('#window-modal').modal('hide');
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
    });
  },
}
