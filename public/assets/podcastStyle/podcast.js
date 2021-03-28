var timerInterval;
var podAudio;
var elapsed = 0;
let sendView = true;
window.addEventListener('load', function() {
    setPlayer()

})

function startSetUp() {

    if(document.getElementById("userReview") !=null) {
        document.getElementById("newReviewBody").style.display = "none";
        document.getElementById("editReviewBody").style.display = "inherit";
        setRatingColor(document.getElementById("userReview").value, document.getElementById("userReviewSpan"));
        setRatingColor(document.getElementById("userReview").value, document.getElementById("reviewButton"));
        if (document.getElementById("userRatingMoyValue") !=null)
            setRatingColor(document.getElementById("userReview").value, document.getElementById("userRatingMoyValue"));
    }
    if (document.getElementById("ratingMoyValue") !=null) {
        setRatingColor(document.getElementById("ratingMoyValue").innerHTML, document.getElementById("ratingMoyValue"));
    }
    if (document.getElementById("podcastAudio") != null) {
    podAudio = document.getElementById("podcastAudio");
    fetch(podAudio.src)
        .then(response => response.blob())
        .then(function(blob) {
            podAudio.src = URL.createObjectURL(blob);
        });
    document.getElementById("playerSpinner").style.display = "none";
    document.getElementById("playerComponents").style.display = "inherit";
    if (document.getElementById("elapsed") != null){
        document.getElementById("elapsed").style.width = "0";
        setTime(podAudio.duration);
    }
    podAudio.loop = false;
    } else {
        document.getElementById("playerSpinner").style.display = "none";
        document.getElementById("playerComponents").style.display = "inherit";
    }
}

function setPlayer(){
    if (document.getElementById("podcastAudio") != null) {
    if (isNaN(document.getElementById("podcastAudio").duration)) {
        setTimeout(setPlayer, 500);
    } else{
        startSetUp()
    }
    } else {
        startSetUp()
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
function moveTime() {
    if(elapsed>=75 && document.getElementById("podcastId") && sendView) {
        let id = document.getElementById("podcastId").value;
        document.getElementById("podcastId").parentElement.removeChild(document.getElementById("podcastId"));
        $.post("/addViewToPod",{id:id}, function(data) {
            console.log(data);
            sendView = false;
        });
    }
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

function moveToTime(event) {
    let offset = document.getElementById("slider").getBoundingClientRect();
    elapsed = event.clientX-offset.left;
    document.getElementById("elapsed").style.width = elapsed+"px";
    let pas = parseInt(document.getElementById("podcastAudio").duration) / 300;
    let time = (elapsed * pas);
    time = Math.round(time);
    setTime(time);
    console.log(time);
    podAudio.currentTime = time;
 }

function deleteReview(id) {
    $.post("/deleteReview/"+id, function(data) {
    document.getElementById("userReviewSpan").innerHTML ="";
    document.getElementById("editReviewBody").style.display = "none";
    document.getElementById("newReviewBody").style.display = "inherit";
    document.getElementById("reviewButton").style.color = "white";
    if(document.getElementById("userReview") !=null) {
        document.getElementById("userReview").value ="";
    }
    if(data !== "") {
    document.getElementById("ratingMoyValue").innerHTML = data;
        setRatingColor(data, document.getElementById("ratingMoyValue"));
    } else {
    document.getElementById('ratingMoyTD').style.display = "none";
    }

    });
}

function bigger() {
    document.getElementById("slider").style.height = "7px";
    document.getElementById("elapsed").style.height = "6px";
    document.getElementById("timer").style.marginTop = "-1px";
    document.getElementById("elapsed").style.marginTop = "-1px";
    document.getElementById("controls").style.marginTop = "17.9px";

}

function smaller() {
    document.getElementById("slider").style.height = "4px";
    document.getElementById("elapsed").style.height = "4px";
    document.getElementById("timer").style.marginTop = "-2px";
    document.getElementById("elapsed").style.marginTop = "1px";
    document.getElementById("controls").style.marginTop = "20px";

}