
document.addEventListener('DOMContentLoaded', function() {

  // Configuración de WebRTC
  const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }
    ]
  };

  let localStream;
  let peerConnection;
  let roomId;

  const localVideo = document.getElementById('localVideo');
  const remoteVideo = document.getElementById('remoteVideo');
  const startButton = document.getElementById('startButton');
  const joinButton = document.getElementById('joinButton');
  const roomInput = document.getElementById('roomId');

  // Conectar al servidor de señalización (ejemplo usando socket.io)
  const socket = io('https://webrtc01.onrender.com');

  startButton.addEventListener('click', async () => {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
        startButton.disabled = true;
        joinButton.disabled = false;
    } catch (error) {
        console.error('Error accessing media devices:', error);
    }
  });

  joinButton.addEventListener('click', () => {
    roomId = roomInput.value;
    if (roomId && localStream) {
        initializePeerConnection();
        socket.emit('join', roomId);
    }
  });

  function initializePeerConnection() {
    peerConnection = new RTCPeerConnection(configuration);

    // Agregar las pistas locales
    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    // Manejar pistas remotas
    peerConnection.ontrack = event => {
        remoteVideo.srcObject = event.streams[0];
    };

    // Manejar candidatos ICE
    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            socket.emit('ice-candidate', {
                roomId: roomId,
                candidate: event.candidate
            });
        }
    };
  }

  // Eventos del socket
  socket.on('ready', async () => {
    // Crear y enviar oferta cuando la sala está lista
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);
    socket.emit('offer', { roomId: roomId, offer: offer });
  });

  socket.on('offer', async (offer) => {
    if (!peerConnection) {
        initializePeerConnection();
    }
    await peerConnection.setRemoteDescription(offer);
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    socket.emit('answer', { roomId: roomId, answer: answer });
  });

  socket.on('answer', async (answer) => {
    await peerConnection.setRemoteDescription(answer);
  });

  socket.on('ice-candidate', async (candidate) => {
    if (peerConnection) {
        await peerConnection.addIceCandidate(candidate);
    }
  });

});