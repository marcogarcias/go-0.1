document.addEventListener('DOMContentLoaded', function() {

  let users = {};
  const configuration = {
    iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
  };

  let localStream;
  let isBroadcaster = false;
  const peerConnections = new Map();
  const videoElements = new Map();
  let roomId;

  //const socket = io('http://localhost:3000');
  //const socket = io('https://webrtc01.onrender.com');
  const socket = io('https://webrtc04.onrender.com');
  const videoGrid = document.getElementById('videoGrid');
  const startButton = document.getElementById('startButton');
  const joinButton = document.getElementById('joinButton');
  const statusDiv = document.getElementById('status');


  function createVideoElement(idBroadcaster, label = '', type) {
    const typeBroadcaster = users[idBroadcaster] ? users[idBroadcaster].type : '';
    console.log('createVideoElement', idBroadcaster, label, type, typeBroadcaster, users);
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
      
    //videoBox.appendChild(video);
    //videoBox.appendChild(userLabel);
    //videoGrid.appendChild(videoBox);
    if(idBroadcaster != 'local' && typeBroadcaster == 'admin' && type == 'viewer'){
      videoBox = document.getElementById('local');
      $('#local').empty();
      $('#local').append(video);
      //$('#local').append(userLabel);
      console.log('1 createVideoElement: local');
    }else if(idBroadcaster != 'local' && typeBroadcaster == 'guest' && type == 'viewer'){
      videoBox = document.getElementById('broadcaster');
      $('#broadcaster').empty();
      $('#broadcaster').append(video);
      console.log('2 createVideoElement: broadcaster');
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
    }
    return { video, container: videoBox };
  }

  async function createPeerConnection(userIdBroadcaster, isInitiator = false, idUser) {
      console.log(`Creando conexión peer con ${userIdBroadcaster}. Iniciador: ${isInitiator}`, idUser, users[idUser], users);
      
      const pc = new RTCPeerConnection(configuration);
      peerConnections.set(userIdBroadcaster, pc);

      // Si somos broadcaster, agregamos nuestro stream
      if (isBroadcaster && localStream) {
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
          if (event.candidate) {
              socket.emit('ice-candidate', {
                  targetId: userIdBroadcaster,
                  candidate: event.candidate
              });
          }
      };

      // Si somos iniciador y broadcaster, crear y enviar oferta
      if (isInitiator && isBroadcaster) {
          try {
              const offer = await pc.createOffer();
              await pc.setLocalDescription(offer);
              socket.emit('offer', {
                  targetId: userIdBroadcaster,
                  offer: offer
              });
          } catch (e) {
              console.error('Error creando oferta:', e);
          }
      }

      return pc;
  }

  startButton.addEventListener('click', async () => {
      try {
          localStream = await navigator.mediaDevices.getUserMedia({ 
              video: true, 
              audio: true 
          });
          startButton.disabled = true;
          joinButton.disabled = false;
      } catch (error) {
          console.error('Error accediendo a la cámara:', error);
          statusDiv.textContent = 'Error al acceder a la cámara';
      }
  });

  joinButton.addEventListener('click', () => {
    roomId = document.getElementById('roomId').value;
    if(roomId){
      socket.emit('join', roomId);
      joinButton.disabled = true;
    }
  });

  // Eventos del socket
  socket.on('broadcaster-status', async (status) => {
      console.log('Estado de broadcaster recibido:', status);
      let idUser = status.idUser;
      isBroadcaster = status.isBroadcaster;
      users = status.users;
      
      if (isBroadcaster) {
          // Mostrar video local para broadcasters
          const videoElement = createVideoElement('local', 'Tú', users[idUser].type);
          videoElement.video.srcObject = localStream;
          videoElement.video.muted = true;
          videoElements.set('local', videoElement);
          statusDiv.textContent = 'Eres un broadcaster';
      } else {
          // Configurar conexiones para espectadores
          startButton.style.display = 'none';
          statusDiv.textContent = 'Eres un espectador';
          
          // Crear conexiones con los broadcasters existentes
          const broadcasters = status.broadcasters || [];
          for (const broadcasterId of broadcasters) {
              await createPeerConnection(broadcasterId, true, idUser);
          }
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
      console.log('Broadcaster desconectado:', broadcasterId);
      const element = videoElements.get(broadcasterId);
      if (element) {
          element.container.remove();
          videoElements.delete(broadcasterId);
      }
      
      const pc = peerConnections.get(broadcasterId);
      if (pc) {
          pc.close();
          peerConnections.delete(broadcasterId);
      }
  });

  socket.on('room-info', ({broadcasters, viewerCount}) => {
      statusDiv.textContent += ` | Broadcasters: ${broadcasters.length}/2 | Espectadores: ${viewerCount}`;
  });


  // Elementos del chat
  const messageInput = document.getElementById('messageInput');
  const sendButton = document.getElementById('sendButton');
  const chatMessages = document.getElementById('chatMessages');
    
  // Función para crear un elemento de mensaje
  function createMessageElement(messageData) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message';
    
    const time = new Date(messageData.timestamp).toLocaleTimeString();
    
    messageDiv.innerHTML = `
        <span class="user">${messageData.userType} ${messageData.userId}</span>
        <span class="time">${time}</span>
        <div class="text">${messageData.message}</div>
    `;
    
    return messageDiv;
  }

  // Manejar envío de mensajes
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

  // Permitir enviar con Enter
  messageInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          sendButton.click();
      }
  });

  // Escuchar mensajes nuevos
  socket.on('chat-message', (messageData) => {
    console.log('chat-message', messageData);
      const messageElement = createMessageElement(messageData);
      chatMessages.appendChild(messageElement);
      
      // Scroll automático hacia abajo
      chatMessages.scrollTop = chatMessages.scrollHeight;
  });

});