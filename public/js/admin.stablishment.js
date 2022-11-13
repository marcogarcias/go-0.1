let admin = {
  stablishment: {
    // METODOS PARA LIST (index)
    view: {
      init: function(cfg){
        $('[data-toggle="tooltip"]').tooltip();
        this.addEvents(cfg);
      },
      // Agregando eventos a los elementos
      addEvents: function(cfg){
        cfg = (typeof cfg === 'object') ? cfg : {};
        let url = cfg.delurl;
        let urlAddVisitsAll = cfg.urlAddVisitsAll;
        let urlEnabledGlobalStab = cfg.urlEnabledGlobalStab;
        let regs=[];

        $('#checkAll').on('click', function(){
          if(!$(this).val()){
            $(this).val('all');
            $('input[name=check]').prop("checked", true);
          }else{
            $(this).val('');
            $('input[name=check]').prop("checked", '');
          }
        });

        $('.enabled-check').on('change', function(e){
          let cfg = {
            hashStab: $(this).attr("data-hashStab"),
            enabled: $(this).prop("checked")
          };
          admin.stablishment.view.enabledGlobalStab(urlEnabledGlobalStab, cfg, res => {
            utils.toastr({'type': res.ty, 'message':res.msg, 'positionClass': 'toast-top-right'});
          });
        });

        $('#eliminarAll').on('click', function(e){
          e.preventDefault();
          
          $("input[name=check]").each(function () {
            if($(this).is(':checked')){
              regs.push($(this).val());
            }
          });
          axios.post(url, {
            regs: regs,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            responseType: 'json'
          }).then(function(res){
            if(res.status==200){
              window.location='/admin/stablishments';
            }
          }).catch(function(err){
            console.log('err: ', err);
          }).then(function(){ });
        });

        // agrega una visita a todos los establecimientos
        $('#addVisitsAll').on('click', function(e){
          e.preventDefault();
          admin.stablishment.view.addVisitsAll(urlAddVisitsAll, res => {
            utils.toastr({'type': res.ty, 'message':res.msg, 'positionClass': 'toast-top-right'});
          });
        });
      },
      addVisitsAll: (url, callback) => {
        utils.axios(url, {}, function(res){
          if(callback && (typeof callback === 'function')){
            callback(res);
          }
        });
      },
      enabledGlobalStab: (url, cfg, callback) =>{
        utils.axios(url, cfg, function(res){
          if(callback && (typeof callback === 'function')){
            callback(res);
          }
        });
      },
    },
    // METODOS PARA CREATE
    create: {
      init: function(cfg){
        this.addEvents(cfg);
      },

      // Agregando eventos a los elementos
      addEvents: function(cfg){
        let tagsurl = cfg.tagsurl;
        let sec, html;

        $('#expiracion').datepicker({
          dateFormat: 'yy-mm-dd'
        });

        $('#section').on('change', function(){
          sec = $(this).val();
          utils.axios(tagsurl, {sec: sec}, function(res){
            html='';
            if(res.success && res.tags){
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
      },
    },
    // METODOS PARA EDIT
    edit:{
      init: function(cfg){
        this.addEvents(cfg);
      },
      // Agregando eventos a los elementos
      addEvents: function(cfg){
        let tagsurl = cfg.tagsurl;
        let stab = cfg.stab;
        let sec, idT, nameT, checkT, html;

        $('#expiracion').datepicker({
          dateFormat: 'yy-mm-dd'
        });

        $('#section').on('change', function(){
          sec = $(this).val();
          utils.axios(tagsurl, {sec: sec, stab: stab}, function(res){
            html='';
            if(res.success && res.tags){
              for(let tag in res.tags){
                idT = res.tags[tag].idtag;
                nameT = res.tags[tag].name;
                checkT = res.stab_tags.indexOf(res.tags[tag].idtag);
                html += ''+
                  '<div class="custom-control custom-checkbox custom-control-inline">'+
                    '<input type="checkbox" class="custom-control-input" id="'+idT+'" name="tags[]" value="'+idT+'" '+(checkT>=0?'checked':'')+'>'+
                    '<label class="custom-control-label" for="'+idT+'">'+nameT+'</label>'+
                  '</div>';
              }
              $('#tags').empty().append(html);
            }
          });
        });
      },
    },
    list:{

    }
  }
};