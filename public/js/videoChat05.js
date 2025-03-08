

let users = {};
const cfg = {};

const configuration = {
  iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
};

let localStream;
let isBroadcaster = false;
const peerConnections = {}; // Changed from Map to object
const videoElements = {}; // Changed from Map to object
let roomId;
let userType = 'viewer';

const socket = io('http://localhost:3001');
//const socket = io('https://webrtc01.onrender.com');
//const socket = io('https://webrtc04.onrender.com');

const videoGrid = document.getElementById('videoGrid');
const statusDiv = document.getElementById('status');
  
// Chat elements
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
      localStream = await navigator.mediaDevices.getUserMedia({ 
        video: true, 
        audio: true 
      });
      $('#startButton').hide();
      $('#joinButton').show();
    }catch(error) {
      console.error('Error accediendo a la cámara:', error);
      statusDiv.textContent = 'Error al acceder a la cámara';
    }
  });

  $(document).on('click', '#joinButton', async function(){
    roomId = document.getElementById('roomId').value;
    console.log('roomId: ', roomId);
    if(userType == 'kukurygirl' && !roomId){
      return  alert('Ingresa una sala.');
    }
    socket.emit('join', roomId, userType);
    $('#joinButton').hide();
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

function createVideoElement(idBroadcaster, label = '', type) {
  const typeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].type : '';
  const userTypeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].userType : '';
  console.log(`1 createVideoElement ${idBroadcaster}, ${label}, ${type}, ${typeBroadcaster}, ${userTypeBroadcaster}`, users);
  
  let videoBox;
  const video = document.createElement('video');
  video.id = `video-${idBroadcaster}`;
  video.autoplay = true;
  video.playsinline = true;
    
  const userLabel = document.createElement('div');
  userLabel.className = 'user-label';
  userLabel.textContent = label;

  // Display logic based on user types
  if((userType == 'kukurygirl' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('local');
    video.height = $('#local').height();
    video.width = $('#local').width();
    $('#local').empty();
    $('#local').append(video);
    console.log('2.1 createVideoElement: kukurygirl viendo a kukurygirl en el video 1');
  }else if((userType == 'kukurygirl' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('2.2 createVideoElement: kukurygirl viendo a guest en el video 2');
  }

  if((userType == 'guest' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('local');
    video.height = $('#local').height();
    video.width = $('#local').width();
    $('#local').empty();
    $('#local').append(video);
    console.log('3.1 createVideoElement: guest viendo a guest en el video 1');
  }else if((userType == 'guest' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
    console.log('3.2 createVideoElement: guest viendo a kukurygirl en el video 2');
  }

  if((userType == 'viewer' && userTypeBroadcaster == 'kukurygirl')){
    videoBox = document.getElementById('local');
    video.height = $('#local').height();
    video.width = $('#local').width();
    $('#local').empty();
    $('#local').append(video);
    console.log('4.1 createVideoElement: viewer viendo a kukurygirl en el video 1');
  }else if((userType == 'viewer' && userTypeBroadcaster == 'guest')){
    videoBox = document.getElementById('broadcaster');
    video.height = $('#broadcaster').height();
    video.width = $('#broadcaster').width();
    $('#broadcaster').empty();
    $('#broadcaster').append(video);
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

    const userTypeForLabel = users[userIdBroadcaster] ? users[userIdBroadcaster].userType : 'Broadcaster';
    const videoElement = createVideoElement(userIdBroadcaster, userTypeForLabel, users[idUser].type);
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
    <span class="user">${messageData.userType} ${messageData.userId}</span>
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
    const videoElement = createVideoElement(idUser, 'Tú', users[idUser].type);
    videoElement.video.srcObject = localStream;
    videoElement.video.muted = true;
    videoElements['local'] = videoElement;
  } else {
    // Configure connections for viewers
    statusDiv.textContent = 'Eres un espectador';
        
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
  if (pc) {
    pc.close();
    delete peerConnections[broadcasterId];
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