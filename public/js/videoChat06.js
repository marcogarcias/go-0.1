

let users = {};
const cfg = {};

const configuration = {
  iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
};

let localStream;
let isBroadcaster = false;
const peerConnections = {};
const videoElements = {};
let roomId;
let userType = 'viewer';
let nick;
let ip;

//const socket = io('http://localhost:3001');
//const socket = io('https://webrtc01.onrender.com');
const socket = io('https://webrtc04.onrender.com');

//const videoGrid = document.getElementById('videoGrid');
//const statusDiv = document.getElementById('status');
  
// Chat elements
const messageInput = document.getElementById('messageInput');
const sendButton = document.getElementById('sendButton');
const chatMessages = document.getElementById('chatMessages');


function initVideoCam(cfg){
  cfg = (typeof cfg === 'object') ? cfg : {};
  userType = cfg.userType ? cfg.userType : 'viewer';
  ip = cfg.ip ? cfg.ip : '127.0.0.1';
  nick = prompt("Ingresa un nickname:");

  initEvents();
  initButtons();
}

function initButtons(){
  userType == 'kukurygirl' && $('#roomId').show();
  (userType == 'kukurygirl' || userType == 'guest') && $('#startButton').show();
  userType == 'viewer' && $('#joinButton').show();
}

function initEvents(){
  $(document).on('click', '#startButton', async function(){
    roomId = document.getElementById('roomId').value;
    if(userType == 'kukurygirl' && !roomId){
      return  alert('Ingresa una sala.');
    }

    try{
      $('#roomId').fadeOut(300);
      $('#startButton').fadeOut(300, ()=>{
        $('#buttonsCont .loading').fadeIn(100);
      });
      localStream = await navigator.mediaDevices.getUserMedia({ 
        video: true, 
        audio: true 
      });
      $('#buttonsCont .loading').fadeOut(300, ()=>{
        $('#joinButton').fadeIn(300);
      });
    }catch(error) {
      console.error('Error accediendo a la cámara:', error);
      //statusDiv.textContent = 'Error al acceder a la cámara';
    }
  });

  $(document).on('click', '#joinButton', async function(){
    roomId = document.getElementById('roomId').value;
    console.log('roomId: ', roomId);
    if(userType == 'kukurygirl' && !roomId){
      return  alert('Ingresa una sala.');
    }
    socket.emit('join', { roomId, userType, nick, ip });
    $('#joinButton').fadeOut(300);
  });

  $(document).on('click', '#kukurygirl .btn-video', async function(){
    if($(this).parent().parent().attr('id') != userType) return false;

    const type = $(this).attr('data-type');
    const container = $(this).closest('.buttonsVideoCont').parent().attr('id');
    // Identificar qué stream debemos modificar
    let streamId = null;
    for (const id in videoElements) {
      if(videoElements[id].container && videoElements[id].container.id === container) {
        streamId = id;
        break;
      }
    }
  
    if(!streamId) return;
    console.log('type: ', type, streamId);

    switch(type) {
      case 'video':
        enableDisableVideo(streamId, this);
        break;
      case 'audio':
        enableDisableAudio(streamId, this);
        break;
      case 'exit':
        roomExit(streamId, this);
        break;
    }
  });

  // Handle chat message sending
  sendButton.addEventListener('click', () => {
    const message = messageInput.value.trim();
    if (message && roomId) {
      socket.emit('chat-message', {
        roomId: roomId,
        message: message
      });
      messageInput.value = '';
    }
  });

  // Allow sending with Enter in chat
  messageInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      sendButton.click();
    }
  });
}

function enableDisableVideo(streamId, this_){
  if(!streamId) return;

  const videoTrack = videoElements[streamId].video.srcObject.getVideoTracks()[0];
  if(videoTrack){
    const newState = !videoTrack.enabled;
    videoTrack.enabled = newState;
        
    $(this_).css('opacity', newState?1:0.5);
    
    // Notificar al servidor sobre el cambio
    socket.emit('media-control', {
      roomId: roomId,
      mediaType: 'video',
      enabled: newState,
      targetStream: streamId
    });
  }
}

function enableDisableAudio(streamId, this_){
  if(!streamId) return;

  const audioTrack = videoElements[streamId].video.srcObject.getAudioTracks()[0];
  if (audioTrack) {
    const newState = !audioTrack.enabled;
    audioTrack.enabled = newState;
        
    $(this_).css('opacity', newState?1:0.5);
        
    // Notificar al servidor sobre el cambio
    socket.emit('media-control', {
      roomId: roomId,
      mediaType: 'audio',
      enabled: newState,
      targetStream: streamId
    });
  }
}

function roomExit(streamId, this_){
  // Salir de la sala
  // Cerrar todas las conexiones
  for(const id in peerConnections){
    if (peerConnections[id]){
      peerConnections[id].close();
      delete peerConnections[id];
    }
  }
      
  // Detener la transmisión local si existe
  if(localStream){
    localStream.getTracks().forEach(track => track.stop());
  }    
  // Notificar al servidor
  socket.emit('leave-room', {
    roomId: roomId
  });
      
  // Limpiar interfaz
  $('#kukurygirl, #broadcaster').empty().append('<i class="fas fa-user"></i>');
  $('#roomId').show();
  $('#startButton').show();
  $('#joinButton').hide();
      
  // Resetear variables
  roomId = null;
}

function createVideoElement(idBroadcaster, type) {
  //const typeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].type : '';
  const userTypeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].userType : '';
  const nick = users[idBroadcaster] ? users[idBroadcaster].nick : '';
  const maxChar = 13;
  const buttonsHtml = `
    <div class="buttonsVideoCont">
      <div class="btn-video" data-type="video">
        <i class="fas fa-camera"></i>
      </div>
      <div class="btn-video" data-type="audio">
        <i class="fas fa-microphone"></i>
      </div>
      <div class="btn-video" data-type="exit">
        <i class="fas fa-window-close"></i>
      </div>
    </div>`;
  
  console.log(`1 createVideoElement ${idBroadcaster}, ${type}, ${userTypeBroadcaster}`, users);
  
  let videoBox;
  const video = document.createElement('video');
  video.id = `video-${idBroadcaster}`;
  video.autoplay = true;
  video.playsinline = true;

  // Display logic based on user types
  if((userType == 'kukurygirl' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('kukurygirl');
    video.height = $('#kukurygirl').height();
    video.width = $('#kukurygirl').width();

    $('#kukurygirl').empty();
    $('#kukurygirl').append(video);
    $('#kukurygirl').append(buttonsHtml);
    $('#kukurygirl').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('2.1 createVideoElement: kukurygirl viendo a kukurygirl en el video 1');
  }else if((userType == 'kukurygirl' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    $('#broadcaster').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('2.2 createVideoElement: kukurygirl viendo a guest en el video 2');
  }

  if((userType == 'guest' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('kukurygirl');
    video.height = $('#kukurygirl').height();
    video.width = $('#kukurygirl').width();
    $('#kukurygirl').empty();
    $('#kukurygirl').append(video);
    $('#kukurygirl').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('3.1 createVideoElement: guest viendo a guest en el video 1');
  }else if((userType == 'guest' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    $('#broadcaster').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('3.2 createVideoElement: guest viendo a kukurygirl en el video 2');
  }

  if((userType == 'viewer' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('kukurygirl');
    video.height = $('#kukurygirl').height();
    video.width = $('#kukurygirl').width();
    $('#kukurygirl').empty();
    $('#kukurygirl').append(video);
    $('#kukurygirl').append(buttonsHtml);
    $('#kukurygirl').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('4.1 createVideoElement: viewer viendo a kukurygirl en el video 1');
  }else if((userType == 'viewer' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    $('#broadcaster').siblings('.nickLabel').text(nick.substring(0, maxChar));
    console.log('4.2 createVideoElement: viewer viendo a guest en el video 2');
  }

  return { video, container: videoBox };
}

async function createPeerConnection(userIdBroadcaster, isInitiator = false, idUser) {
  console.log(`Creando conexión peer con ${userIdBroadcaster}. Iniciador: ${isInitiator}`, idUser, users[idUser], users);
  
  // Close existing connection if any
  if (peerConnections[userIdBroadcaster]) {
    console.log(`Cerrando conexión peer existente con ${userIdBroadcaster}`);
    peerConnections[userIdBroadcaster].close();
    delete peerConnections[userIdBroadcaster];
  }

  const pc = new RTCPeerConnection(configuration);
  peerConnections[userIdBroadcaster] = pc;

  // If we're broadcaster, add our local stream
  if(isBroadcaster && localStream) {
    console.log('Agregando tracks locales a la conexión');
    localStream.getTracks().forEach(track => {
      pc.addTrack(track, localStream);
    });
  }

  // Configure handling of incoming streams
  pc.ontrack = (event) => {
    console.log(`Stream recibido de ${userIdBroadcaster}`);
    // Handle reconnection - remove existing video if present
    if (videoElements[userIdBroadcaster] && videoElements[userIdBroadcaster].video) {
      console.log(`Removiendo video existente para ${userIdBroadcaster}`);
      videoElements[userIdBroadcaster].video.srcObject = null;
    }

    //const userTypeForLabel = users[userIdBroadcaster] ? users[userIdBroadcaster].userType : 'Broadcaster';
    const videoElement = createVideoElement(userIdBroadcaster, users[idUser].type);
    videoElement.video.srcObject = event.streams[0];
    videoElements[userIdBroadcaster] = videoElement;
  };

  // Handle ICE candidates
  pc.onicecandidate = (event) => {
    if(event.candidate) {
      socket.emit('ice-candidate', {
        targetId: userIdBroadcaster,
        candidate: event.candidate
      });
    }
  };

  // Handle connection state changes for debugging
  pc.onconnectionstatechange = () => {
    console.log(`Connection state change for ${userIdBroadcaster}: ${pc.connectionState}`);
    if (pc.connectionState === 'failed' || pc.connectionState === 'disconnected') {
      console.log(`Connection with ${userIdBroadcaster} failed or disconnected`);
    }
  };

  // If we're initiator and broadcaster, create and send offer
  if(isInitiator && isBroadcaster) {
    try{
      const offer = await pc.createOffer();
      await pc.setLocalDescription(offer);
      socket.emit('offer', {
        targetId: userIdBroadcaster,
        offer: offer
      });
    }catch(e){
      console.error('Error creando oferta:', e);
    }
  }

  return pc;
}

// Function to create a message element
function createMessageElement(messageData) {
  const messageDiv = document.createElement('div');
  messageDiv.className = 'message';
  
  const time = new Date(messageData.timestamp).toLocaleTimeString();
  
  messageDiv.innerHTML = `
    <span class="user">${messageData.nick}</span>
    <span class="time">${time}</span>
    <div class="text">${messageData.message}</div>`;
  
  return messageDiv;
}

// agregando la sala
socket.on('set-room', (data) => {
  if(!roomId && data.roomId && (data.userType == 'guest' || data.userType == 'viewer')){
    roomId = data.roomId;
  }
});

// Listen for new messages
socket.on('chat-message', (messageData) => {
  console.log('chat-message', messageData);
  const messageElement = createMessageElement(messageData);
  chatMessages.appendChild(messageElement);
    
  // Auto-scroll to bottom
  chatMessages.scrollTop = chatMessages.scrollHeight;
});

// Socket events
socket.on('broadcaster-status', async (status) => {
  console.log('Estado de broadcaster recibido:', status);
  let idUser = status.idUser;
  isBroadcaster = status.isBroadcaster;
  users = status.users;
    
  if(isBroadcaster) {
    // Show local video for broadcasters
    const videoElement = createVideoElement(idUser, users[idUser].type);
    videoElement.video.srcObject = localStream;
    videoElement.video.muted = true;
    videoElements['local'] = videoElement;
  } else {
    // Configure connections for viewers
    //statusDiv.textContent = 'Eres un espectador';
        
    // Create connections with existing broadcasters
    const broadcasters = status.broadcasters || [];
    for(const broadcasterId of broadcasters) {
      await createPeerConnection(broadcasterId, true, idUser);
    }
  }
});

// NEW EVENT HANDLER: For broadcaster joining/rejoining
socket.on('broadcaster-joined', async (broadcasterId, users_) => {
  console.log('Broadcaster joined/rejoined:', broadcasterId, users_);
  users = users_;
  
  // Clean up any existing connections for this broadcaster
  if (peerConnections[broadcasterId]) {
    console.log(`Cerrando conexión peer existente con ${broadcasterId}`);
    peerConnections[broadcasterId].close();
    delete peerConnections[broadcasterId];
  }
  
  // Viewers need to create a new peer connection with the rejoining broadcaster
  if (!isBroadcaster) {
    console.log(`Viewer creating new connection with rejoined broadcaster ${broadcasterId}`);
    await createPeerConnection(broadcasterId, true, broadcasterId);
  }
});

socket.on('initiate-peer-connection', async (peerId, users_) => {
  users = users_;
  console.log('Iniciando conexión peer con:', peerId, users);
  await createPeerConnection(peerId, true, peerId);
});

socket.on('viewer-joined', async (viewerId) => {
  if (isBroadcaster) {
    console.log('Nuevo espectador unido:', viewerId);
    await createPeerConnection(viewerId, true, viewerId);
  }
});

socket.on('offer', async ({ offer, offerId }) => {
  console.log('Oferta recibida de:', offerId);
  let pc = peerConnections[offerId];
  if (!pc) {
    pc = await createPeerConnection(offerId, false, offerId);
  }
  await pc.setRemoteDescription(offer);
  const answer = await pc.createAnswer();
  await pc.setLocalDescription(answer);
  socket.emit('answer', {
    targetId: offerId,
    answer: answer
  });
});

socket.on('answer', async ({ answer, answerId }) => {
  console.log('Respuesta recibida de:', answerId);
  const pc = peerConnections[answerId];
  if (pc) {
    await pc.setRemoteDescription(answer);
  }
});

socket.on('ice-candidate', async ({ candidate, candidateId }) => {
  const pc = peerConnections[candidateId];
  if (pc) {
    await pc.addIceCandidate(candidate);
  }
});

socket.on('broadcaster-disconnected', (broadcasterId) => {
  const element = videoElements[broadcasterId];
  if (element) {
    console.log('Broadcaster desconectado:', broadcasterId, element, element.container);
    element.video.remove();
    $(element.container).html('<i class="fas fa-user"></i>');
    delete videoElements[broadcasterId];
  }
  
  const pc = peerConnections[broadcasterId];
  if(pc){
    pc.close();
    delete peerConnections[broadcasterId];
  }
});

// Escuchar actualizaciones de control de medios
socket.on('media-control', (data) => {  
  // Buscar el elemento de video correspondiente
  const videoElement = videoElements[userType=='kukurygirl'?data.targetStream:data.userId];
  if (!videoElement || !videoElement.video || !videoElement.video.srcObject) return;
  // Aplicar el cambio según el tipo de media
  if (data.mediaType === 'video') {
    const videoTrack = videoElement.video.srcObject.getVideoTracks()[0];
    if (videoTrack) {
      videoTrack.enabled = data.enabled;
      
      // Actualizar UI para reflejar el estado
      const container = $(videoElement.container);
      const button = container.find(`.btn-video[data-type="video"]`);
      $(button).css('opacity', data.enabled?1:0.5); 
    }
  } else if (data.mediaType === 'audio') {
    const audioTrack = videoElement.video.srcObject.getAudioTracks()[0];
    if (audioTrack) {
      audioTrack.enabled = data.enabled;
      
      // Actualizar UI para reflejar el estado
      const container = $(videoElement.container);
      const button = container.find(`.btn-video[data-type="audio"]`);
      $(button).css('opacity', data.enabled?1:0.5); 
    }
  }
});

// Escuchar cuando alguien abandona la sala voluntariamente
socket.on('user-left', (userId) => {
  console.log('User left:', userId);
  
  // Si es un broadcaster, eliminar su video
  const element = videoElements[userId];
  if (element) {
    element.video.srcObject = null;
    element.video.remove();
    $(element.container).html('<i class="fas fa-user"></i>');
    delete videoElements[userId];
  }
  
  // Cerrar la conexión peer
  const pc = peerConnections[userId];
  if (pc) {
    pc.close();
    delete peerConnections[userId];
  }
  
  // Eliminar al usuario del objeto users
  if (users[userId]) {
    delete users[userId];
  }
});

socket.on('room-info', (info) => {
  const roomId = info.roomId;
  const broadcasters = info.broadcasters;
  const viewerCount = info.viewerCount;
  const users_ = info.users;
  users = users_;
  //statusDiv.textContent = ` | Broadcasters: ${broadcasters.length}/2 | Espectadores: ${viewerCount}`;
  $("#usersNo").text(Object.keys(users).length);
  console.log('usersNum', Object.keys(users).length, users);
});


socket.on('socketErrores', function(data){
  const message = data.message ? data.message : '';
  const type = data.type ? data.type : '';
  const usrTtpe = data.userType ? data.userType : '';
  alert(message);
  switch(type){
    case 'canceledJoin':
      if(usrTtpe=='guest'){
        $('#startButton').show();
      }else if(usrTtpe=='viewer'){
        $('#joinButton').show();
      }
      break;
  }
  console.log(data);
});