var timerInterval
window.addEventListener('load', function() {
    setTimeout(function(){
        document.getElementById("elapsed").style.width = "0";
        setTime(document.getElementById("podcastAudio").duration);
    },1000)
})

function setTime(timeToConvert){
        hours = Math.round(((timeToConvert/60)/60));
        minutes = Math.round((timeToConvert/60));
        seconds = Math.round(((timeToConvert%60)%60));
        if(hours<10){
            hours="0"+hours;
        }
        if(minutes<10){
            minutes="0"+minutes;
        }
        if(seconds<10){
            seconds="0"+seconds;
        }
        time = hours+":"+minutes+":"+seconds;
        document.getElementById("timer").innerHTML =time;
}
function changeStatus(x){
    if (x === 1) {
        document.getElementById("podcastAudio").play();
        document.getElementById("play").style.display = "none";
        document.getElementById("stop").style.display = "inherit";
        timerInterval= setInterval(moveTime, 1000);
    } else {
        clearInterval(timerInterval);
        document.getElementById("podcastAudio").pause();
        document.getElementById("stop").style.display = "none";
        document.getElementById("play").style.display = "inherit";
    }

}
var elapsed = 0;
function moveTime() {
    toAdd = 300/ parseInt(document.getElementById("podcastAudio").duration);
    elapsed=elapsed+toAdd;
    document.getElementById("elapsed").style.width = elapsed+"px";
   let time = document.getElementById("podcastAudio").duration- document.getElementById("podcastAudio").currentTime;
    setTime(time);
}

function moveToTime(event) {
    offset = window.innerWidth;
    offset -= document.getElementById("slider").parentElement.parentElement.parentElement.offsetLeft;
    console.log(offset);
console.log(event.clientX-offset);
}