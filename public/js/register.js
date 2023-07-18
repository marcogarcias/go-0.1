
let register = {
	// inicializando el registro de usuarios/empresas  
  init: function(cfg){
    register.events(cfg);
  },
  events: function(cfg){
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
        register.setDataByParsley(false);
      }else{
        $('#stablishmentForm').fadeIn();
        span = 'Registrarse como empresa tienes los siguientes beneficios:';
        li = `
        <li>Crear tu espacio para mostrar tus productos y servicios.</li>
        <li>Chat directo con tus posibles clientes.</li>
        <li>Solicitar personal para tus negocios.</li>`;
        // agregando datas para que validar con parsley
        register.setDataByParsley(true);
      }
      console.log("type", userType);
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

    /*$('#frmRegister').parsley().on('field:validated', function(){
      //var ok = $('.parsley-error').length === 0;
      console.log("1...");
    }).on('form:success', function() {
      console.log("2... todo bien");
    }).on('form:error', function(err){
      console.log('3 Mostrando errores', err);
    }).on("form:submit", function(){
      return false;
    });*/
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
      /*$("#facebook").attr('data-parsley-maxlength', 200);
      $("#instagram").attr('data-parsley-maxlength', 200);
      $("#twitter").attr('data-parsley-maxlength', 200);
      $("#youtube").attr('data-parsley-maxlength', 200);
      $("#horario").attr('data-parsley-maxlength', 200);*/
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
      /*$("#facebook").removeAttr('data-parsley-maxlength');
      $("#instagram").removeAttr('data-parsley-maxlength');
      $("#twitter").removeAttr('data-parsley-maxlength');
      $("#youtube").removeAttr('data-parsley-maxlength');
      $("#horario").removeAttr('data-parsley-maxlength');*/
      $("#section").removeAttr('required');
      $("#adminCode").removeAttr('required');
      $("#adminCode").removeAttr('data-parsley-pattern');
    }
  },
};