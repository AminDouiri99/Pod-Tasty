let audio = document.querySelector('audio');
let startBtn = document.querySelector('.js-start');
let stopBtn = document.querySelector('.js-stop');
let pre = document.querySelector('pre');
let streamInterval;
let removeInt;
let isRecording = false;
let mediaStreamSource;
let fullRecorder;
let recorder
let currentBuffer = null;
let timeSlice = 4000;
let elementVolume = document.querySelector('.js-volume');
let ctx = elementVolume.getContext('2d');
let liveStatus = 1;
let podId;
window.addEventListener("load",function() {

    podId = document.getElementById("podcastId").value;
    const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
    url.searchParams.append('topic', 'http://127.0.0.1:8000/refreshWatchers/'+podId);
    const eventSource = new EventSource(url);
    eventSource.addEventListener('message', function(event){
        document.getElementById("watchingNow").innerHTML = event.data+" watching";
    });
    // $.ajax({
    //     url: '/isItLive/'+podId,
    //     type: 'POST',
    //     timeout: 3000
    // });

    // setTimeout(function() {
    //     sendIsLive();
    // },10000);
    window.onbeforeunload = function () {
        return 'You have unsaved changes!';
    }
});
// function sendIsLive() {
//     $.ajax({
//         url: '/isStillLive',
//         type: 'POST',
//         success: function () {
//             setTimeout(function() {
//                 sendIsLive();
//             },10000);
//         }
//     });
// }
window.URL = window.URL || window.webkitURL;
/**
 * Detecte the correct AudioContext for the browser
 * */

let onFail = function(e) {
    alert('Error '+e);
    console.log('Rejected!', e);
};

function savePodcast() {
    clearInterval(streamInterval);
    document.getElementById("redMic").style.display = "none";
    stopBtn.style.display = "none";
    isRecording = false;
    elementVolume.style.display = "none";
    recorder.stop();
    fullRecorder.stop();
    liveStatus = 0;
    src = recorder.exportWAV(createUrl);
}


function createUrl(blob) {
    clearInterval(streamInterval);
    let x = 0;
    if(liveStatus !== 0) {
    removeInt = setInterval(function(){x++},1);
    }
    formData = new FormData();
    formData.append('category', 'general');
    formData.append('liveStreaming', blob);
    formData.append('podId', podId);
    formData.append('status', liveStatus);

    recorder.clear();
    $.ajax({
        url: '/stream/'+podId,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success : function(){
            clearInterval(removeInt);
            if (liveStatus !== 0) {
            streamInterval = setInterval(function() {
                src = recorder.exportWAV(createUrl);
            },(5000-(x+100)));
            } else{
                setTimeout(function(){
                    src = fullRecorder.exportWAV(sendFinal);
                },3000);
            }
        }
    });

}
function sendFinal(blob) {
    formData = new FormData();
    formData.append('liveStreaming', blob);
    formData.append('podId', podId);
    $.ajax({
        url: '/savePodcast',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success : function(){
            document.getElementById("streamEnded").style.opacity = 0;
           setTimeout(()=> {
               window.onbeforeunload = null;
               document.getElementById("streamEnded").innerHTML = "Podcast Saved !<br>Redirecting to home screen...";

               fadeIn("streamEnded",2);
               setTimeout(()=> {

                   window.location.replace("/");
               }, 3000);
                }, 1000);
        }
    });

}

let onSuccess = function(s) {
    startBtn.style.display = "none";
    isRecording = true;
    document.getElementById("greyMic").style.display = "none";
    document.getElementById("redMic").style.display = "inherit";
    stopBtn.style.display = "inherit";
    console.log('Recording...');
    context = new AudioContext();
    mediaStreamSource = context.createMediaStreamSource(s);
    fullRecorder = new Recorder(mediaStreamSource, {numChannels:1});
    recorder = new Recorder(mediaStreamSource, {numChannels:1});
    recorder.record();
    analizer(context);
    fullRecorder.record();
    streamInterval = setInterval(function() {
        src = recorder.exportWAV(createUrl);
},5000);

    stopBtn.addEventListener('click', ()=>{
        savePodcast();
        document.getElementById("streamEnded").style.display = "none";
        document.getElementById("streamEnded").innerHTML = "Stream Ended !<br>Saving your podcast...";
        fadeIn("streamEnded",2);
    });
    $.post("/setPodToLive",{podId:podId});
}
let analizer = (context) => {
    let listener = context.createAnalyser();
    mediaStreamSource.connect(listener);
    listener.fftSize = 256;
    let bufferLength = listener.frequencyBinCount;
    let analyserData = new Uint8Array(bufferLength);

    let getVolume = () => {
        let volumeSum = 0;
        let volumeMax = 0;

        listener.getByteFrequencyData(analyserData);

        for (let i = 0; i < bufferLength; i++) {
            volumeSum += analyserData[i];
        }

        let volume = volumeSum / bufferLength;

        if (volume > volumeMax)
            volumeMax = volume;

        drawAudio(volume / 10);
        /**
         * Call getVolume several time for catch the level until it stop the record
         */
        return setTimeout(()=>{
            if (isRecording)
                getVolume();
            else
                drawAudio(0);
        }, 10);
    }

    getVolume();
}

let drawAudio = (volume) => {
    ctx.save();
    ctx.translate(0, 120);

    for (var i = 0; i < 14; i++) {
        fillStyle = '#fff';
        if (i < volume)
            fillStyle = '#5c2e27';

        ctx.fillStyle = fillStyle;
        ctx.beginPath();
        ctx.arc(100, 100, 50, 0, 2 * Math.PI);
        ctx.closePath();
        ctx.fill();
        ctx.translate(-0.5,-8);
    }

    ctx.restore();
}

startBtn.addEventListener('click', ()=>{
    if (navigator.getUserMedia) {
        /**
         * ask permission of the user for use microphone or camera
         */
        navigator.getUserMedia({audio: true}, onSuccess, onFail);
    } else {
        console.warn('navigator.getUserMedia not present');
    }
});

function changeCommentingStatus(id) {

    document.getElementById("deactivateComs").disabled = true;
    document.getElementById("activateComs").disabled = true;

    $.post("/admin/podcast/changeCommentingStatus/"+podId, function() {
        setTimeout(function() {
            if(id === 1) {
                document.getElementById("deactivateComs").style.display = "none";
                document.getElementById("activateComs").style.display = "inherit";
            } else {

                document.getElementById("deactivateComs").style.display = "inherit";
                document.getElementById("activateComs").style.display = "none";
            }

            document.getElementById("deactivateComs").disabled = false;
            document.getElementById("activateComs").disabled = false;
        },1000);
    });
}