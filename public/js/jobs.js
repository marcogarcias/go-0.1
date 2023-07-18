
let jobs = {
  // SECCIÓN DE GESTION DE EMPLEOS DESDE EL ADMIN
  init: (cfg)=>{
    cfg = (typeof cfg === "object") ? cfg : {};
    let urlGetJobTypes = cfg.urlGetJobTypes ? cfg.urlGetJobTypes : false;
    let urlGetJobSubTypes = cfg.urlGetJobSubTypes ? cfg.urlGetJobSubTypes : false;
    let urlAddJob = cfg.urlAddJob ? cfg.urlAddJob : false;
    let urlUpdJob = cfg.urlUpdJob ? cfg.urlUpdJob : false;
    let urlDelJob = cfg.urlDelJob ? cfg.urlDelJob : false;
    let urlMyDatas = cfg.urlMyDatas ? cfg.urlMyDatas : false;
    let stab = $("#stab").val();

    // carga los tipos de empleo al mostrar la ventana modal
    $("#addVacant-modal").off("show.bs.modal");
    $("#addVacant-modal").on("show.bs.modal", function (event) {
      jobs.loadJobTypes(urlGetJobTypes, function(res){
        jobs.setJobTypes(res);
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
          $("#subTypes").empty().append(html);
        }
      });
    });

    // guarda una vacante
    $("#addJob").off("click");
    $("#addJob").on("click", function(e){
      e.preventDefault();
      jobs.storeJob(urlAddJob, function(res){
        jobs.myDataTable(urlMyDatas, stab, 'jobs', function(ty, dataTable){
          $('#'+ty+'Table tbody').empty().html(dataTable);
        });
      });
    });

    // actualiza una vacante
    $(document).off("click", "#updJob");
    $(document).on('click', '#updJob', function(e){
      e.preventDefault();
      jobs.updJob(urlUpdJob, $(this).attr('data-job'), $(this).attr('data-name'));
    });

    // elimina una vacante
    $(document).off("click", "#delJob");
    $(document).on('click', '#delJob', function(e){
      e.preventDefault();
      jobs.delData(urlDelJob, $(this).attr('data-job'), $(this).attr('data-name'), function(){
        jobs.myDataTable(urlMyDatas, stab, 'jobs', function(ty, dataTable){
          $('#'+ty+'Table tbody').empty().html(dataTable);
        });
      });
    });

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
  storeJob: (url, callback)=>{
    let data = {
      vacante: $("#vacante").val(),
      descripcion: $("#descripcion").val(),
      jobType: $("#jobType").val(),
      doc: $("input:radio[name=doc]:checked").val(),
      stab: $("#stab").val(),
      job: $("#job").val(),
      subTypes: []
    };

    $("#subTypes input:checkbox:checked").each(function() {
      data["subTypes"].push($(this).val());
    });

    utils.sendAjax(url, data, function(res){
      utils.toastr({'type': res.code, 'message': res.message});
      if(res['success']){
        jobs.resetFrm("#jobsFrm");
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }else{
        if(res['errors']){
          utils.validateSetErrors(res['errors']);
        }
      }
    });
  },
  // edita una vacante
  updJob: function(url, job, name){
    let data = {job: job, name: name};
    let cont={};
    $("#vacante").trigger("focus");
    go.loadAjaxPost(url, data, function(res){
      console.log("res", res);
      if(res["success"]){
        cont = res["cont"];
        $("#addJob").text("Actualizar");
        $("#jobsFrm")[0].reset();
        $("#job").val(job);
        $("#vacante").val(cont["name"]);
        $("#descripcion").val(cont["description"]);
        $("#jobType").val(cont["hashJobType"]);
        console.log("222", cont["hashJobType"]);

        if(cont["documentation"]!="cv")
          $("#solicitud").prop("checked", true);
        else
          $("#cv").prop("checked", true);
      }
    });
  },
  // elimina una vacante
  delData: function(url, id, name, callback){
    go.loadAjaxPost(url, {id: id, name: name}, function(res){
      if(res['success']){
        if(callback && (typeof callback === 'function')){
          callback(res);
        }
      }
      go.toastr({'type': res.code, 'message':res.message});
      //console.log('ajax: ', res, job, idDel);
    });
  },
  // Obtiene un listado de las vacantes de una empresa en una tabla
  myDataTable: function(url, stab, ty, callback){
    let data = {stab: stab, ty: ty};
    let cont={};
    if(stab){
      go.loadAjaxPost(url, data, function(res){
        if(res['data']){
          if(callback && (typeof callback === 'function')){
            callback(ty, res['data']);
          }
        }
      });
    }
  },
  resetFrm: function(frm){
    $(frm)[0].reset();
    $(frm+' .reset').val('');
  },

  // SECCION DE EMPLEOS DEL USUARIO COMÚN
  initSection: function(cfg){
    cfg = (typeof cfg === "object") ? cfg : {};
    let jobsArr = cfg.jobsArr ? cfg.jobsArr : [];
    jobs.setJobs(jobsArr);
  },
  // crea las cajas con los empleos
  setJobs: function(jobsArr){
    jobsArr = (typeof jobsArr === "object") ? jobsArr : [];
    html = ``;
    if(jobsArr.length){
      for(let j in jobsArr){
        let job = jobsArr[j];
        let style = `background: url(${job["imgPath"]}); background-position: center; background-repeat: no-repeat; background-size: cover;`;
        html += `
          <div class="col-xs-12 col-sm-12 col-md-5 jobContainer">
            <div class="jobImgCont" style="${style}"></div>
            <div class="jobDesc">
              <h5>Negocio: ${job["stabName"]}</h5>
              <h5>Solicita: ${job["jobName"]}</h5>
            </div>
            <div class="jobLink">
              <a href="stablishment/${job["hashStab"]}" class="btn btn-black">Ir al sitio >></a>
            </div>
          </div>`;
      }
    }
    $("#jobsCont").html(html);
  },
}
