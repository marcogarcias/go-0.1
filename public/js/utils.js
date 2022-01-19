let utils = {
  hash1: ()=>{
    let dateTime = Date.now();
    let time = Math.floor(dateTime / 1000);
    let hash1 = Math.round(Math.random() * time)+''+Math.round(Math.random() * time);
    return hash1;
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