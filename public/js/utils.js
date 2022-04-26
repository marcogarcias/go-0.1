let utils = {
  hash1: ()=>{
    let dateTime = Date.now();
    let time = Math.floor(dateTime / 1000);
    let hash1 = Math.round(Math.random() * time)+''+Math.round(Math.random() * time);
    return hash1;
  },
  /**
   * Inserta los errores de validación de un formulario
   * @param Json $errors Contiene los campos que no pasaron una validación y el mensaje a mostrar
   * @return void
   */
  validateSetErrors: (errors)=>{
    for(let err in errors){
      $(`#${err}-error`).text(errors[err]);
      console.log(`#${err}-error`, errors[err]);
    }
  },
  axios: function(url, data, callback){
    if(data !== null && (data instanceof Object)){
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
    }
  },
  sendAjax: function(url, data, callback){
    utils.axios(url, data, callback);
  },

  ajaxJQ: function(url, data, callback, cfg){
    cfg = (typeof cfg === 'object') ? cfg : {};
    let type = cfg.type ? cfg.type : 'post';
    let dataType = cfg.dataType ? cfg.dataType : 'json';
    let resType = cfg.responseType ? cfg.responseType : 'json';
    let conType = cfg.contentType ? cfg.contentType : false;
    let proData = cfg.processData ? cfg.processData : false;
    let cache = cfg.cache ? cfg.cache : false;
    let beforeSend = cfg.beforeSend ? cfg.beforeSend : false;
    let error = cfg.error ? cfg.error : false;
    let complete = cfg.complete ? cfg.complete : false;

    if(data !== null && (data instanceof Object)){
      $.ajax({
        url: url,
        type: type,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        responseType: resType,
        contentType: conType,
        processData: proData,
        cache: cache,
        beforeSend: (xhr)=>{
          if(beforeSend && (typeof beforeSend === 'function')){
            beforeSend(xhr);
          }
        },
        success: (res)=>{
          if(callback && (typeof callback === 'function')){
            callback(res);
          }
        },
        error: (x, y)=>{
          if(error && (typeof error === 'function')){
            error(x, y);
          }
        },
        complete: (x)=>{
          if(complete && (typeof complete === 'function')){
            complete(x);
          }
        }
      });
    }
  },

  sendAjaxJQ: function(url, data, callback){
    utils.ajaxJQ(url, data, callback);
  },

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
}