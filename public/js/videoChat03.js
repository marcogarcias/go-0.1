

let users = {};
const cfg = {};

const configuration = {
  iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
};

  let localStream;
  let isBroadcaster = false;
  const peerConnections = new Map();
  const videoElements = new Map();
  let roomId;
  let userType = 'viewer';

  //const socket = io('http://localhost:3001');
  //const socket = io('https://webrtc01.onrender.com');
  const socket = io('https://webrtc04.onrender.com');

  const videoGrid = document.getElementById('videoGrid');
  //const startButton = document.getElementById('startButton');
  //const joinButton = document.getElementById('joinButton');
  const statusDiv = document.getElementById('status');

  // Elementos del chat
  const messageInput = document.getElementById('messageInput');
  const sendButton = document.getElementById('sendButton');
  const chatMessages = document.getElementById('chatMessages');


function initVideoCam(cfg){
  cfg = (typeof cfg === 'object') ? cfg : {};
  userType = cfg.userType ? cfg.userType : 'viewer';

  initEvents();
  initButtons();
}

function initButtons(){
  $('#startButton').show();
}

function initEvents(){
  //startButton.addEventListener('click', async () => {
  $(document).on('click', '#startButton', async function(){
    try{
      localStream = await navigator.mediaDevices.getUserMedia({ 
        video: true, 
        audio: true 
      });
      //startButton.disabled = true;
      //joinButton.disabled = false;
      $('#startButton').hide();
      $('#joinButton').show();
    }catch(error) {
      console.error('Error accediendo a la cámara:', error);
      statusDiv.textContent = 'Error al acceder a la cámara';
    }
  });

  //joinButton.addEventListener('click', () => {
  $(document).on('click', '#joinButton', async function(){
    roomId = document.getElementById('roomId').value;
    if(roomId){
      socket.emit('join', roomId, userType);
      //joinButton.disabled = true;
      $('#joinButton').hide();
    }
  });

  // Manejar envío de mensajes del chat
  sendButton.addEventListener('click', () => {
    const message = messageInput.value.trim();
    console.log('1111', roomId);
    if (message && roomId) {
      console.log('2222');

        socket.emit('chat-message', {
            roomId: roomId,
            message: message
        });
        messageInput.value = '';
    }
  });

  // Permitir enviar con Enter en el chat
  messageInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          sendButton.click();
      }
  });
}

function createVideoElement(idBroadcaster, label = '', type) {
  const typeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].type : '';
  const userTypeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].userType : '';
  console.log(`1 createVideoElement ${idBroadcaster}, ${label}, ${type}, ${typeBroadcaster}, ${userTypeBroadcaster}`, users);
  //const type = users[userId]=='local' ? 'admin' : users[userId]['type'];
  let videoBox;
  
  //const videoBox = document.createElement('div');
  //videoBox.className = 'video-box';
    
  const video = document.createElement('video');
  video.id = `video-${idBroadcaster}`;
  video.autoplay = true;
  video.playsinline = true;
    
  const userLabel = document.createElement('div');
  userLabel.className = 'user-label';
  userLabel.textContent = label;
    
  /*videoBox.appendChild(video);
  videoBox.appendChild(userLabel);
  videoGrid.appendChild(videoBox);*/

  if((userType == 'kukurygirl' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('2.1 createVideoElement: kukurygirl viendo a kukurygirl en el video 1');
  }else if((userType == 'kukurygirl' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    //$('#local').append(userLabel);
    console.log('2.2 createVideoElement: kukurygirl viendo a guest en el video 2');
  }

  if((userType == 'guest' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('3.1 createVideoElement: guest viendo a guest en el video 1');
  }else if((userType == 'guest' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    //$('#local').append(userLabel);
    console.log('3.2 createVideoElement: guest viendo a kukurygirl en el video 2');
  }

  if((userType == 'viewer' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('4.1 createVideoElement: viewer viendo a kukurygirl en el video 1');
  }else if((userType == 'viewer' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    //$('#local').append(userLabel);
    console.log('4.2 createVideoElement: viewer viendo a guest en el video 2');
  }


  /*
  // Cuando es el kukurygirl
  if((userType == 'kukurygirl' && type == 'admin' && typeBroadcaster == '')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('2.1 createVideoElement: kukurygirl viendo a admin en el video 1');
  }else if((userType == 'kukurygirl' && type == 'guest' && typeBroadcaster == '')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('2.2 createVideoElement: kukurygirl viendo a guest en el video 1');
  }else if(userType == 'kukurygirl' && type == 'guest' && typeBroadcaster == 'guest'){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    //$('#local').append(userLabel);
    console.log('2.3 createVideoElement: kukurygirl viendo a guest en el video 2');
  }

  // Cuando es el invitado
  if(userType == 'guest' && type == 'guest' && typeBroadcaster == ''){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    console.log('3.1 createVideoElement: guest viendo a admin en el video 2');
  }else if(userType == 'guest' && type == 'admin' && typeBroadcaster == 'admin'){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('3.2 createVideoElement: guest viendo a guest en el video 1');
  }else if(userType == 'guest' && type == 'guest' && typeBroadcaster == 'guest'){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('3.3 createVideoElement: guest viendo a guest en el video 2');
  }else if(userType == 'guest' && typeBroadcaster == '' && userTypeBroadcaster == ''){
    $('#local').empty();
    $('#local').append(video);
    console.log('3.4 createVideoElement: guest viendo a guest en el video 1');
  }

  // Cuando es el espectador
  if(userType == 'viewer' && typeBroadcaster == 'admin' && userTypeBroadcaster == 'kukurygirl'){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('4.1 createVideoElement: viewer viendo a admin en el video 1');
  }else if(userType == 'viewer' && typeBroadcaster == 'guest' && userTypeBroadcaster == 'guest'){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('4.2 createVideoElement: viewer viendo a guest en el video 2');
  }else if(userType == 'viewer' && typeBroadcaster == 'guest' && userTypeBroadcaster == 'kukurygirl'){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('4.3 createVideoElement: viewer viendo a admin en el video 1');
  }
  */
  
  /*if(idBroadcaster != 'local' && typeBroadcaster == 'admin' && type == 'viewer'){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    //$('#local').append(userLabel);
    console.log('1 createVideoElement: local');
  }else if(idBroadcaster != 'local' && typeBroadcaster == 'guest' && type == 'viewer'){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('2 createVideoElement: broadcaster', userType);
  }else if((idBroadcaster == 'local' && type == 'admin') || (idBroadcaster == 'local' && type == 'guest')){
    videoBox = document.getElementById('local');
    $('#local').empty();
    $('#local').append(video);
    console.log('3 createVideoElement: local');
    //$('#local').append(userLabel);
  }else if((idBroadcaster != 'local' && type == 'admin') || (idBroadcaster != 'local' && type == 'guest')){
    videoBox = document.getElementById('broadcaster');
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('4 createVideoElement: broadcaster');
  }*/

  return { video, container: videoBox };
}

async function createPeerConnection(userIdBroadcaster, isInitiator = false, idUser) {
  console.log(`Creando conexión peer con ${userIdBroadcaster}. Iniciador: ${isInitiator}`, idUser, users[idUser], users);
    
  const pc = new RTCPeerConnection(configuration);
  peerConnections.set(userIdBroadcaster, pc);

  // Si somos broadcaster, agregamos nuestro stream
  if(isBroadcaster && localStream) {
    console.log('Agregando tracks locales a la conexión');
    localStream.getTracks().forEach(track => {
      pc.addTrack(track, localStream);
    });
  }

  // Configurar el manejo de streams entrantes
  pc.ontrack = (event) => {
    console.log(`Stream recibido de ${userIdBroadcaster}`);
    const videoElement = videoElements.get(userIdBroadcaster) || 
      createVideoElement(userIdBroadcaster, 'Broadcaster', users[idUser].type);
    videoElement.video.srcObject = event.streams[0];
    videoElements.set(userIdBroadcaster, videoElement);
  };

  // Manejar candidatos ICE
  pc.onicecandidate = (event) => {
    if(event.candidate) {
      socket.emit('ice-candidate', {
        targetId: userIdBroadcaster,
        candidate: event.candidate
      });
    }
  };

  // Si somos iniciador y broadcaster, crear y enviar oferta
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

// Función para crear un elemento de mensaje
function createMessageElement(messageData) {
  const messageDiv = document.createElement('div');
  messageDiv.className = 'message';
  
  const time = new Date(messageData.timestamp).toLocaleTimeString();
  
  messageDiv.innerHTML = `
    <span class="user">${messageData.userType} ${messageData.userId}</span>
    <span class="time">${time}</span>
    <div class="text">${messageData.message}</div>`;
  
  return messageDiv;
}

// Escuchar mensajes nuevos
socket.on('chat-message', (messageData) => {
  console.log('chat-message', messageData);
  const messageElement = createMessageElement(messageData);
  chatMessages.appendChild(messageElement);
    
  // Scroll automático hacia abajo
  chatMessages.scrollTop = chatMessages.scrollHeight;
});

// Eventos del socket
socket.on('broadcaster-status', async (status) => {
  console.log('Estado de broadcaster recibido:', status);
  let idUser = status.idUser;
  isBroadcaster = status.isBroadcaster;
  users = status.users;
    
  if(isBroadcaster) {
    // Mostrar video local para broadcasters
    //const videoElement = createVideoElement('local', 'Tú', users[idUser].type);
    const videoElement = createVideoElement(idUser, 'Tú', users[idUser].type);
    videoElement.video.srcObject = localStream;
    videoElement.video.muted = true;
    videoElements.set('local', videoElement);
    //statusDiv.textContent = 'Eres un broadcaster';
  }else {
    // Configurar conexiones para espectadores
    //startButton.style.display = 'none';
    statusDiv.textContent = 'Eres un espectador';
        
    // Crear conexiones con los broadcasters existentes
    const broadcasters = status.broadcasters || [];
    for(const broadcasterId of broadcasters) {
      await createPeerConnection(broadcasterId, true, idUser);
    }
  }
});

socket.on('initiate-peer-connection', async (peerId, users_) => {
  users = users_;
  //$("#usersNo").text(Object.keys(users).length);
  //console.log('usersNum', Object.keys(users).length);
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
    let pc = peerConnections.get(offerId);
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
    const pc = peerConnections.get(answerId);
    if (pc) {
        await pc.setRemoteDescription(answer);
    }
});

socket.on('ice-candidate', async ({ candidate, candidateId }) => {
    const pc = peerConnections.get(candidateId);
    if (pc) {
        await pc.addIceCandidate(candidate);
    }
});

socket.on('broadcaster-disconnected', (broadcasterId) => {
    const element = videoElements.get(broadcasterId);
    if (element) {
      console.log('Broadcaster desconectado:', broadcasterId, element, element.container);
        element.video.remove();
        $(element.container).html('<i class="fas fa-user"></i>');
        videoElements.delete(broadcasterId);
    }
    
    const pc = peerConnections.get(broadcasterId);
    if (pc) {
        pc.close();
        peerConnections.delete(broadcasterId);
    }
});

socket.on('room-info', (info) => {
  const roomId = info.roomId;
  const broadcasters = info.broadcasters;
  const viewerCount = info.viewerCount;
  const users_ = info.users;
  users = users_;
    statusDiv.textContent = ` | Broadcasters: ${broadcasters.length}/2 | Espectadores: ${viewerCount}`;
    $("#usersNo").text(Object.keys(users).length);
    console.log('usersNum', Object.keys(users).length, users);
});

