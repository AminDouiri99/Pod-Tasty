var timerInterval;
var podAudio;
window.addEventListener('load', function() {
    setPlayer()

})
function setPlayer(){
    if (isNaN(document.getElementById("podcastAudio").duration)) {
        setTimeout(setPlayer, 500);
    } else{
     if(document.getElementById("userReview") !=null) {
         document.getElementById("newReviewBody").style.display = "none";
         document.getElementById("editReviewBody").style.display = "inherit";
         setRatingColor(document.getElementById("userReview").value, document.getElementById("reviewButton"));
     }
     if (document.getElementById("ratingMoyValue") !=null) {
    setRatingColor(document.getElementById("ratingMoyValue").innerHTML, document.getElementById("ratingMoyValue"));
     }
    podAudio = document.getElementById("podcastAudio");
    document.getElementById("playerSpinner").style.display = "none";
    document.getElementById("playerComponents").style.display = "inherit";

    document.getElementById("elapsed").style.width = "0";
    podAudio.loop = false;
    setTime(podAudio.duration);
    }
}
function setRatingColor(value, item) {
    if (parseInt(value)===0)
    {
        item.style.color = "red";
    } else if(parseInt(value)<=2) {
        item.style.color = "darkorange";
    } else if(parseInt(value)<=4) {
        item.style.color = "deepskyblue";
    }else if(parseInt(value)<=6) {
        item.style.color = "green";
    }else if(parseInt(value)<=8) {
        item.style.color = "deeppink";
    } else {
        item.style.color = "gold";
    }
}
function setTime(timeToConvert) {
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
    if (x === 3) {
        document.getElementById("elapsed").style.width = 0;
    }
    if (x === 1 || x === 3) {
        podAudio.play();
        document.getElementById("play").style.display = "none";
        document.getElementById("stop").style.display = "inherit";
        document.getElementById("replay").style.display = "none";
        timerInterval= setInterval(moveTime, 1000);
    } else{
        clearInterval(timerInterval);
        podAudio.pause();
        document.getElementById("stop").style.display = "none";
        document.getElementById("play").style.display = "inherit";
    }

}
var elapsed = 0;
function moveTime() {
    if(elapsed <300) {
    toAdd = 300/ parseInt(podAudio.duration);
    elapsed=elapsed+toAdd;
    document.getElementById("elapsed").style.width = elapsed+"px";
    let time = podAudio.duration- podAudio.currentTime;
    setTime(time);
    } else {
        clearInterval(timerInterval);
        elapsed = 0;
        document.getElementById("stop").style.display = "none";
        document.getElementById("play").style.display = "none";
        document.getElementById("replay").style.display = "inherit";
    }

}

// function moveToTime(event) {
//     clearInterval(timerInterval);
//     let offset = document.getElementById("slider").getBoundingClientRect();
//     elapsed = event.clientX-offset.left;
//     document.getElementById("elapsed").style.width = elapsed+"px";
//     let pas = 300/ parseInt(podAudio.duration);
//     let time = podAudio.duration-(elapsed/pas);
//     time = Math.round(time);
//     setTime(time);
//     console.log(time);
//     if(!podAudio.paused) {
//     podAudio.pause();
//     }
//     podAudio.currentTime = time;
//     console.log(podAudio.currentTime);
//
//     if(podAudio.paused) {
//         podAudio.play();
//         timerInterval= setInterval(moveTime, 1000);
//     }
//  }

function deleteReview(id) {
    $.post("/deleteReview/"+id, function(data) {
    document.getElementById("editReviewBody").style.display = "none";
    document.getElementById("newReviewBody").style.display = "inherit";
    document.getElementById("reviewButton").style.color = "white";
    if(document.getElementById("userReview") !=null)
        document.getElementById("userReview").value ="";
    else
        document.getElementById("userReview1").value = "";
    document.getElementById("ratingMoyValue").innerHTML = data;
    });
}