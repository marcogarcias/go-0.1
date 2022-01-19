let chatClient = {
  cfg: {},
  img: '',
  logo: '',
  type: '',
  userStablishment: 0,
  receivedMsg: 0,
  intervalLoadMsg: 0,
  intervalLoadNewMsgContact: 0,
  intervalLodNewMsgGeneral: 0,
  urlSaveMessage: '',
  urlLoadAllMessages: '',
  urlLoadAllUsers: '',
  urlLoadNewMsgGeneral: '',
  init: (cfg)=>{
    console.log('iniciando chat');
    let messagesNew = '';
    chatClient.setProps(cfg);
    chatClient.addEvents();
    /*chatClient.type == 'clientToStablishment' ?
      chatClient.client() : chatClient.stablishment();*/
  },
  setProps: (cfg)=>{
    cfg = (typeof cfg === 'object') ? cfg : {};
    chatClient.cfg = cfg;
    chatClient.urlLoadAllMessages = cfg.urlLoadAllMessages ? cfg.urlLoadAllMessages : '';
    chatClient.urlLoadMessages = cfg.urlLoadMessages ? cfg.urlLoadMessages : '';
    chatClient.urlSaveMessage = cfg.urlSaveMessage ? cfg.urlSaveMessage : '';
    chatClient.urlLoadAllUsers = cfg.urlLoadAllUsers ? cfg.urlLoadAllUsers : '';
    chatClient.urlLoadNewMsgGeneral = cfg.urlLoadNewMsgGeneral ? cfg.urlLoadNewMsgGeneral : '';
    chatClient.type = cfg.type ? cfg.type : '';
    chatClient.img = cfg.img ? cfg.img : '';
    chatClient.logo = cfg.logo ? cfg.logo : '';
    chatClient.userStablishment = cfg.userStablishment ? cfg.userStablishment : '';
  },
  addEvents: ()=>{
    chatClient.checkNewMsg();

    $('#btn-openChat').on('click', ()=>{
      chatClient.open();
      chatClient.type == 'clientToStablishment' ?
        chatClient.client() : chatClient.stablishment();
    });

    $("#sendmessage input").focus(function(){
      if($(this).val() == "Escribir mensage"){
        $(this).val("");
      }
    });

    $("#sendmessage input").focusout(function(){
      if($(this).val() == ""){
        $(this).val("Escribir mensage");
      }
    });

    $('#chatbox-client #close, #chatbox-stablish #close').on('click', function(){
      chatClient.close();
    });

    $('#chatbox-client #closeCont, #chatbox-stablish #closeCont').on('click', function(){
      chatClient.close();
    });

    $('#chatbox-client #send, #chatbox-stablish #send').on('click', function(){
      chatClient.sendMessage();
    });

    $('#sendmessage input, #chatbox-stablish input').on('keyup',function(e){
      if(e.which == 13)
        $('#chatbox-client #send, #chatbox-stablish #send').trigger('click');
    });

    // Al hacer click a un contacto ir al chat
    $(document).on('click', '.friend', function(){
      chatClient.contactToChat(this);
      chatClient.loadAllMessages((res)=>{
        let messages = res['result'];
        chatClient.setAllMessages(messages);
        chatClient.clearIntervals();
        chatClient.intervalLoadMsg = setInterval(()=>{
          console.log('interval: intervalLoadMsg');
          chatClient.loadMessages((res)=>{
            messagesNew = res['result'];
            chatClient.setAllMessages(messagesNew);
            console.log('Mensajes nuevos:', res);
            console.log('Mensaje:', chatClient.receivedMsg);
          });
        }, 3000);
      });
    });
  },
  checkNewMsg: ()=>{
    let newMsg;
    let url = chatClient.urlLoadNewMsgGeneral;
    let userClient = $('#btn-openChat').attr('data-userClient');
    let userStablishment = $('#btn-openChat').attr('data-userStablishment');
    let cfg = { 
      type: chatClient.type,
      userStablishment: userStablishment
    };
    if(chatClient.type == 'clientToStablishment'){
      cfg['userClient'] = userClient;
    }

    go.loadAjaxPost(url, cfg, function(res){
      newMsg = Number(res['result']);
      if(newMsg){
        $('#btn-openChat').css('border', '2px solid #f00');
        $('.newMsgGeneralIcon').css('display', 'flex');
      }else{
        $('#btn-openChat').css('border', '0px');
        $('.newMsgGeneralIcon').css('display', 'none');
      }
      console.log('newMsg', newMsg);
    });

    chatClient.clearIntervals();
    chatClient.intervalLodNewMsgGeneral = setInterval(()=>{
      console.log('interval: intervalLoadNewMsgGeneral');
      go.loadAjaxPost(url, cfg, function(res){
        newMsg = res['result'];
        if(newMsg){
          $('#btn-openChat').css('border', '2px solid #f00');
          $('.newMsgGeneralIcon').css('display', 'flex');
          console.log('nuevo mensaje');
        }else{
          $('#btn-openChat').css('border', '0px');
          $('.newMsgGeneralIcon').css('display', 'none');
        }
        console.log('newMsg', newMsg);
        //newMsg ? $('#btn-openChat').;
      });
    }, 3000);
  },
  client: ()=>{
    chatClient.receivedMsg = 0;
    $('#chat-messages').empty();
    chatClient.loadAllMessages((res)=>{
      let messages = res['result'];
      chatClient.setAllMessages(messages);
      chatClient.clearIntervals();
      chatClient.intervalLoadMsg = setInterval(()=>{
        console.log('interval: intervalLoadMsg');
        chatClient.loadMessages((res)=>{
          messagesNew = res['result'];
          chatClient.setAllMessages(messagesNew);
          console.log('Mensajes nuevos:', res);
          console.log('Mensaje:', chatClient.receivedMsg);
        });
      }, 3000);
    });
  },
  open: ()=>{
    $('#chatbox-client, #chatbox-stablish').show();
    chatClient.openClose('1%', '2%');
    $('#btn-openChat').fadeOut('slow', ()=>{});
  },
  close: ()=>{
    let height = $('#chatbox-client, #chatbox-stablish').height() * -1;
    let width = $('#chatbox-client, #chatbox-stablish').width() * -1;
    $('#btn-openChat').fadeIn('slow');
    $('#chatbox-client, #chatbox-stablish').hide();
    chatClient.checkNewMsg();
    chatClient.openClose(width, height, ()=>{});
  },
  openClose: (x, y, callback)=>{
    $('#chatbox-client, #chatbox-stablish').animate({
      'right': x,
      'bottom': y,
    }, 200, function(){
      if(callback && (typeof callback === 'function')){
        callback();
      }
    });
  },
  sendMessage: ()=>{
    let message = $('#sendmessage input').val();
    let userClient = $('#btn-openChat').attr('data-userClient');
    let userStablishment = $('#btn-openChat').attr('data-userStablishment');
    let img = chatClient.img ? chatClient.img : '';
    let logo = chatClient.logo ? chatClient.logo : '';
    let url = '';
    $('#sendmessage input').val('');
    chatClient.saveMessage(message, userClient, userStablishment, (res)=>{
      url = chatClient.type=='clientToStablishment' ? 'site/btn/icon-user-01.png' : `site/stablishments/logos/${logo}`;
      let urlImage = `${img}/${url}`;
      html = chatClient.templateMessage(urlImage, message, 'sended');
      chatClient.setMessage(html);
    });
  },
  loadAllMessages: (callback)=>{
    let url = chatClient.urlLoadAllMessages;
    //let userClient = $('#sendmessage input').attr('data-userClient');
    let userClient = $('#btn-openChat').attr('data-userClient');
    let userStablishment = $('#btn-openChat').attr('data-userStablishment');
    let cfg = { 
      type: chatClient.type, 
      userClient: userClient,
      userStablishment: userStablishment 
    };
    go.loadAjaxPost(url, cfg, function(res){
      if(callback && (typeof callback === 'function'))
        callback(res);
    });
    // leer la base de datos y traer todos los mensajes del establecimiento
  },
  loadMessages: (callback)=>{
    /*// leer la base de datos y traer todos los mensajes no leidos del establecimiento
    let urlImage = '{{ asset('img/site/stablishments/logos/'.$stablish->image) }}';
    for(let msg in messages){
      html = chatClient.templateMessage(urlImage, messages[msg].message, 'received');
      chatClient.setMessage(html);
    }*/
    let url = chatClient.urlLoadMessages;
    let userClient = $('#btn-openChat').attr('data-userClient');
    let userStablishment = $('#btn-openChat').attr('data-userStablishment');
    let cfg = { 
      type: chatClient.type,
      receivedMsg: chatClient.receivedMsg,
      userClient: userClient,
      userStablishment: userStablishment
    };
    go.loadAjaxPost(url, cfg, function(res){
      if(callback && (typeof callback === 'function'))
        callback(res);
    });
  },
  templateMessage: (image, message, type)=>{
    type = type ? type : 'sended';
    let class_ = type == 'sended' ? ' right' : '';
    return html = `
      <div class="message ${class_}">
        <img src="${image}">
        <div class="bubble">
          ${message}
          <div class="corner"></div>
          <span>Now</span>
        </div>
      </div>`;
  },
  saveMessage: (message, userClient, userStablishment, callback)=>{
    let from = chatClient.type=='clientToStablishment' ? 'client' : 'stablishment';
    let url = chatClient.urlSaveMessage;
    let cfg = { message: message, userClient: userClient, userStablishment: userStablishment, from: from };
    go.loadAjaxPost(url, cfg, function(res){
      if(callback && (typeof callback === 'function'))
        callback(res);
    });
  },
  setAllMessages: (messages)=>{
    let urlImage = '';
    let type = '';
    let html = '';
    let img = chatClient.img ? chatClient.img : '';
    let logo = chatClient.logo ? chatClient.logo : '';
    if(messages.length){
      for(let msg in messages){
        if(messages[msg].from=='client'){
          urlImage = `${img}/site/btn/icon-user-01.png`;
          type = chatClient.type=='clientToStablishment' ? 'sended' : 'received';
          chatClient.type=='stablishmentToClient' && chatClient.receivedMsg++;
        }else{
          urlImage = `${img}/site/stablishments/logos/${logo}`;
          type = chatClient.type=='clientToStablishment' ? 'received' : 'sended';
          chatClient.type=='clientToStablishment' && chatClient.receivedMsg++;
        }
        html = chatClient.templateMessage(urlImage, messages[msg].message, type);
        chatClient.setMessage(html);
      }
      $("#chat-messages").stop().animate({
        scrollTop: $("#chat-messages")[0].scrollHeight
      }, 1000);
    }
  },
  setMessage: (html)=>{
    $('#chat-messages').append(html);
    $("#chat-messages").stop().animate({
      scrollTop: $("#chat-messages")[0].scrollHeight
    }, 1000);
  },
  // METODOS PARA EL ESTABLECIMIENTO
  stablishment: ()=>{
    chatClient.loadAllUsersWithMessages((res)=>{
      let users = res['result'];
      chatClient.setAllUsers(users);
    });
    chatClient.clearIntervals();
    chatClient.intervalLoadNewMsgContact = setInterval(()=>{
      console.log('interval: intervalLoadNewMsgContact');
      chatClient.loadAllUsersWithMessages((res)=>{
        let users = res['result'];
        chatClient.setAllUsers(users);
      });
    }, 3000);
  },
  setAllUsers: (users)=>{
    let type = '';
    let html = '';
    let img = chatClient.img ? chatClient.img : '';
    let urlImage = `${img}/site/btn/icon-user-01.png`;
    let logo = chatClient.logo ? chatClient.logo : '';
    $('#friends').empty();
    if(users.length){
      for(let usr in users){
        /*if(users[usr].from=='client'){
          urlImage = `${img}/site/btn/icon-user-01.png`;
          type = 'sended';
        }else{
          urlImage = `${img}/site/stablishments/logos/${logo}`;
          type = 'received';
          chatClient.receivedMsg++;
        }*/
        html = chatClient.templateUser(urlImage, users[usr]);
        chatClient.setContacts(html);
      }
    }
  },
  templateUser: (image, userObj)=>{
    userObj = (typeof userObj === 'object') ? userObj : {};
    let name = userObj.name ? userObj.name : '';
    let email = userObj.email ? userObj.email : '';
    let userClient = userObj.userClient ? userObj.userClient : '';
    let userStablishment = userObj.userStablishment ? userObj.userStablishment : '';
    let msgNew = userObj.msgNew ? userObj.msgNew : 0;

    return html = `
      <div class="friend" data-userClient="${userClient}">
        <div class="cont">
          <div class="">
            <img src="${image}">
          </div>
          <div class="data">
            <strong title="${name}">${name}</strong>
            <span title="${email}">${email}</span>
          </div>
          <div class="msgNew">`+
            (msgNew ? '<i class="fa fa-envelope" aria-hidden="true" title="Nuevo mensaje"></i>' : '')+
          `</div>
        </div>
      </div>`;
  },
  setContacts: (html)=>{
    $('#friends').append(html);
  },
  loadAllUsersWithMessages: (callback)=>{
    let url = chatClient.urlLoadAllUsers;
    let userStablishment = chatClient.userStablishment ? chatClient.userStablishment : '';
    let cfg = {
      userStablishment: userStablishment
    };
    go.loadAjaxPost(url, cfg, function(res){
      if(callback && (typeof callback === 'function'))
        callback(res);
    });
  },
  contactToChat: (this_)=>{
    var childOffset = $(this_).offset();
    var parentOffset = $(this_).parent().parent().offset();
    var childTop = childOffset.top - parentOffset.top;
    var clone = $(this_).find('img').eq(0).clone();
    var top = childTop+12+"px";
          
    $(clone).css({'top': top}).addClass("floatingImg").appendTo("#chatbox");                  

    setTimeout(function(){$("#profile p").addClass("animate");$("#profile").addClass("animate");}, 100);
    setTimeout(function(){
      $("#chat-messages").addClass("animate");
      $('.cx, .cy').addClass('s1');
      setTimeout(function(){$('.cx, .cy').addClass('s2');}, 100);
      setTimeout(function(){$('.cx, .cy').addClass('s3');}, 200);     
    }, 150);                            
          
    $('.floatingImg').animate({
      'width': "68px",
      'left':'108px',
      'top':'20px'
    }, 200);

    var name = $(this_).find(".data strong").html();
    var email = $(this_).find(".data span").html();
    console.log(name, email);
    $("#profile p").html(name);
    $("#profile span").html(email);     

    $(".message").not(".right").find("img").attr("src", $(clone).attr("src"));                  
    $('#friendslist').fadeOut();
    $('#chatview').fadeIn();

    $('#close').unbind("click").click(function(){
      //chatClient.clearIntervals();
      //clearInterval(chatClient.intervalLoadMsg);
      chatClient.chatToContact();
    });

    chatClient.receivedMsg = 0;
    $('#chat-messages').empty(html);
    //$('#sendmessage input').attr('data-userclient', $(this_).attr('data-userclient'));
    $('#btn-openChat').attr('data-userclient', $(this_).attr('data-userclient'));
  },
  chatToContact: ()=>{
    $("#chat-messages, #profile, #profile p").removeClass("animate");
    $('.cx, .cy').removeClass("s1 s2 s3");
    $('.floatingImg').animate({
      'width': "40px",
      'top':top,
      'left': '12px'
    }, 200, function(){$('.floatingImg').remove()});        

    setTimeout(function(){
      $('#chatview').fadeOut();
      $('#friendslist').fadeIn();
    }, 50);

    chatClient.stablishment();
  },
  clearIntervals: ()=>{
    clearInterval(chatClient.intervalLoadMsg);
    clearInterval(chatClient.intervalLoadNewMsgContact);
    clearInterval(chatClient.intervalLodNewMsgGeneral);
  },
};