let audio = document.querySelector('audio');
let waitingForLoad;
let loadingInterval;
let audio2 = new Audio();
let chunks = [];
let first = true;
let ended = false;
let liveStatus = "1";
let liveEnded = false;
let podid;
let userId;
window.addEventListener('load', function() {
    podId=document.getElementById("podcastId").value;
    userId=document.getElementById("podcastId").value;
    $.post("/addWatcher",{id: podId});
    const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
    url.searchParams.append('topic', 'http://127.0.0.1:8000/stream/'+podId)
    const eventSource = new EventSource(url);
    eventSource.addEventListener('message', function(event){
        playAudio(event.data);
    });

});
function playAudio(data) {
    let id = data.substr(0, data.indexOf('.'));
    liveStatus = data.substr(data.length-1, 1);
    data = data.substr(0, data.length-1);
    fetch("/Files/podcastFiles/temp"+id+"/"+data)
        .then(response => {
            response.blob()
        .then(function(blob){
                chunks.push(blob);
            if (first){
                first = false;
                replaceAudio();
            }
        });
        })
}

function replaceAudio(){
    if (liveEnded) {
        document.getElementById("testPodcast").style.opacity = "0";
        setTimeout(()=> {

            document.getElementById("testPodcast").innerHTML = "";
            document.getElementById("testPodcast").innerHTML = "<div class='streamEnded'>Stream Ended !<br>Redirecting to home screen...</div>";
            fadeIn("testPodcast",2);
            setTimeout(()=> {
                window.location.replace("/");
            }, 4000);
        }, 1000);
    } else {
    if (chunks.length > 0 ) {
    audio2 = new Audio();
    audio2.controls = false;
    document.getElementById("audioContainer").appendChild(audio2);
    audio2.src=URL.createObjectURL(chunks[0]);
    audio2.play();
    audio.parentNode.removeChild(audio);
    audio = audio2;
    chunks.splice(0,1);
    audio.addEventListener("ended" ,function(){
        replaceAudio()
    })

    } else{
        loadingFunction();
    }
    if (liveStatus === "0" && chunks.length === 0) {
        liveEnded = true;
    }
    }
}



function loadingFunction(){
    // document.getElementById("loading").style.display="inherit";
    audio.pause();
    waitingForLoad = setTimeout(function(){
        // document.getElementById("loading").style.display="none";
                replaceAudio();
            },3000);
}

function pauseResumeLive(x) {
    if (x === 1) {
        audio.play();
        document.getElementById("play").style.display = "none";
        document.getElementById("stop").style.display = "inherit";
    } else{
        audio.pause();
        document.getElementById("stop").style.display = "none";
        document.getElementById("play").style.display = "inherit";
    }

}

window.onbeforeunload = function () {
    $.post("/removeWatcher", {id: podId});
}