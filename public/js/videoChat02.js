document.addEventListener('DOMContentLoaded', function() {

  const configuration = {
    iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
  };

  let localStream;
  let isBroadcaster = false;
  const peerConnections = new Map();
  const videoElements = new Map();
  let roomId;

  const socket = io('http://localhost:3001');
  const videoGrid = document.getElementById('videoGrid');
  const startButton = document.getElementById('startButton');
  const joinButton = document.getElementById('joinButton');
  const statusDiv = document.getElementById('status');

  function createVideoElement(userId, label = '') {
      const videoBox = document.createElement('div');
      videoBox.className = 'video-box';
      
      const video = document.createElement('video');
      video.id = `video-${userId}`;
      video.autoplay = true;
      video.playsinline = true;
      
      const userLabel = document.createElement('div');
      userLabel.className = 'user-label';
      userLabel.textContent = label;
      
      videoBox.appendChild(video);
      videoBox.appendChild(userLabel);
      videoGrid.appendChild(videoBox);
      
      return { video, container: videoBox };
  }

  async function createPeerConnection(userId, isInitiator = false) {
      console.log(`Creando conexión peer con ${userId}. Iniciador: ${isInitiator}`);
      
      const pc = new RTCPeerConnection(configuration);
      peerConnections.set(userId, pc);

      // Si somos broadcaster, agregamos nuestro stream
      if (isBroadcaster && localStream) {
          console.log('Agregando tracks locales a la conexión');
          localStream.getTracks().forEach(track => {
              pc.addTrack(track, localStream);
          });
      }

      // Configurar el manejo de streams entrantes
      pc.ontrack = (event) => {
          console.log(`Stream recibido de ${userId}`);
          const videoElement = videoElements.get(userId) || 
                            createVideoElement(userId, 'Broadcaster');
          videoElement.video.srcObject = event.streams[0];
          videoElements.set(userId, videoElement);
      };

      // Manejar candidatos ICE
      pc.onicecandidate = (event) => {
          if (event.candidate) {
              socket.emit('ice-candidate', {
                  targetId: userId,
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
                  targetId: userId,
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
      if (roomId) {
          socket.emit('join', roomId);
          joinButton.disabled = true;
      }
  });

  // Eventos del socket
  socket.on('broadcaster-status', async (status) => {
      console.log('Estado de broadcaster recibido:', status);
      isBroadcaster = status.isBroadcaster;
      
      if (isBroadcaster) {
          // Mostrar video local para broadcasters
          const videoElement = createVideoElement('local', 'Tú');
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
              await createPeerConnection(broadcasterId, true);
          }
      }
  });

  socket.on('initiate-peer-connection', async (peerId) => {
      console.log('Iniciando conexión peer con:', peerId);
      await createPeerConnection(peerId, true);
  });

  socket.on('viewer-joined', async (viewerId) => {
      if (isBroadcaster) {
          console.log('Nuevo espectador unido:', viewerId);
          await createPeerConnection(viewerId, true);
      }
  });

  socket.on('offer', async ({ offer, offerId }) => {
      console.log('Oferta recibida de:', offerId);
      let pc = peerConnections.get(offerId);
      if (!pc) {
          pc = await createPeerConnection(offerId);
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

});