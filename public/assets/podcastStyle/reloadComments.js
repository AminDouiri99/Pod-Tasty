window.onscroll = function(){

    if (window.scrollY ==0) {
        document.getElementById('player').style.width = "70%";
        document.getElementById('player').style.left = "50%";
        document.getElementById('player').style.height = "190px";
        document.getElementById('player').style.marginTop = "0";
        document.getElementById('podInfo').style.marginTop = "0";
        document.getElementById('podInfo').style.marginLeft = "0";
        document.getElementById('controls').style.marginLeft = "0px";
        document.getElementById('coverImg').style.width = "190px";
        document.getElementById('coverImg').style.borderBottomLeftRadius = "20px";
        document.getElementById('coverImg').style.borderTopRightRadius = "0";
        document.getElementById('sliderDiv').style.display = "inherit";
        setTimeout(function () {
            document.getElementById('podcastTools').style.display = "inherit";
        },200);

    } else if(window.scrollY >80){
        document.getElementById('sliderDiv').style.display = "none";
        document.getElementById('podcastTools').style.display = "none";
        document.getElementById('podInfo').style.marginTop = "200px";
        document.getElementById('podInfo').style.marginLeft = "-210px";
        document.getElementById('controls').style.marginLeft = "-120px";
        document.getElementById('player').style.left = "92%";
        document.getElementById('player').style.width = "15%";
        document.getElementById('coverImg').style.width = "100%";
        document.getElementById('coverImg').style.borderBottomLeftRadius = "0";
        document.getElementById('coverImg').style.borderTopRightRadius = "20px";
        document.getElementById('player').style.height = "360px";
        document.getElementById('player').style.marginTop = "70px";
    }
}
function changeStatus(x){
    if (x === 1) {
        document.getElementById("play").style.display = "none";
        document.getElementById("stop").style.display = "inherit";
    } else {

        document.getElementById("stop").style.display = "none";
        document.getElementById("play").style.display = "inherit";
    }

}

comNumb = 0;
if(document.getElementById("commentsLength")!=null){

comNumb = parseInt(document.getElementById('commentsLength').innerHTML);
}

function checkKey(event){
    if(event.code==="Enter" && document.getElementById('comment').value!==""){
        document.getElementById('comment').disabled  = true;
        sendComment(document.getElementById('comment').value);
        document.getElementById('comment').value  = "Posting comment...";
        document.getElementById('comment').style.borderBottomColor="transparent";
    }

}
function sendComment(comment){
    $.post("/addComment",{comment:comment}, function(data) {
        setTimeout(function (){
            comNumb++;
            document.getElementById('CommentsUL').innerHTML = data+document.getElementById('CommentsUL').innerHTML;
            document.getElementById('comment').disabled = false;
            document.getElementById('comment').value  = "";
            document.getElementById('comment').style.borderBottomColor="white";
            if(document.getElementById('commentsLength') != null) {
                var x = parseInt(document.getElementById('commentsLength').innerHTML) + 1;
                document.getElementById('commentsLength').innerHTML = x;
            } else {
                stringmessage = "<span style='margin-right: 15px'>";
                stringmessage += comNumb;
                stringmessage +="</span>";
                stringmessage +=" Comment";
                if(comNumb>1){
                    stringmessage +="s";
                }
                document.getElementById('noCom').innerHTML =stringmessage;
            }
        },1000);
    })
}
function deleteComment(id){
    document.getElementById("deletingMsg"+id).style.display="inherit";
    $.post("/deleteComment/"+id, function(data) {
        var comToDelete =document.getElementById("comment"+id);
        comToDelete.parentNode.removeChild(comToDelete);
        if (comNumb>1) {
            comNumb--;
            if (document.getElementById('commentsLength') != null) {
                var x = parseInt(document.getElementById('commentsLength').innerHTML) - 1;
                document.getElementById('commentsLength').innerHTML = x;
            } else {
                stringmessage = "<span style='margin-right: 15px'>";
                stringmessage += comNumb;
                stringmessage += "</span>";
                stringmessage += " Comment";
                if (comNumb > 1) {
                    stringmessage += "s";
                }
                document.getElementById('noCom').innerHTML = stringmessage;
                comNumb++;
            }
        }
        else if (comNumb === 1) {
            comNumb--;
            if (document.getElementById('commentsLength') != null) {
            document.getElementById('commentsLength').innerHTML = "No comments yet";
            document.getElementById('comText').innerHTML = "";
            } else {
                document.getElementById('noCom').innerHTML = "No comments yet";
            }
        }
    })
}

color = ["red","red","darkorange","darkorange","deepskyblue","deepskyblue","green","green","deeppink","deeppink","gold","gold"];
var podcastRate;
var topicRate;
var hostRate;
var soundQualityRate;
function addRate(id,rate) {
    switch (id){
       case 1:
        {
            podcastRate = rate;
            for (let i = 0; i<=10; i++){
                document.getElementById("podcastRate"+i).style.color ="black";
                i++;
            }
            document.getElementById("podcastRate"+rate).style.color =color[rate];
            document.getElementById("carouselExampleControls")

            document.getElementById("carItem1").classList.remove('active');
            fadeIn("carItem2");
            break;
        }
        case 2:
        {
            topicRate = rate;
            document.getElementById("topicRate"+rate).style.color =color[rate];

            document.getElementById("carItem2").classList.remove('active');
            fadeIn("carItem3");
            break;
        }
        case 3:
        {
            hostRate = rate;
            document.getElementById("hostRate"+rate).style.color =color[rate];

            document.getElementById("carItem3").classList.remove('active');
            fadeIn("carItem4");
            break;
        }
        case 4:
        {
            soundQualityRate = rate;
            document.getElementById("soundQualityRate"+rate).style.color =color[rate];

            document.getElementById("carItem4").classList.remove('active');
            fadeIn("carItem5");
            let finalRate = (podcastRate+topicRate+hostRate+soundQualityRate)/4;
            $.post("/addReview",{review:finalRate}, function(){
                setTimeout(function(){
                    document.getElementById("carItem5").classList.remove('active');
                    fadeIn("carItem6");
                    console.log(finalRate);
                    document.getElementById("reviewButton").style.color = color[Math.round(finalRate)];
                    console.log( Math.round(finalRate));
                }, 3000)
            });
            break;
        }
    }

}

function fadeIn(id) {
    document.getElementById(id).classList.add('active');
    document.getElementById(id).style.opacity = 0;
    let op = 0;
    let inter = setInterval(function () {
        op+=0.05;
        if (op >= 1) {
            document.getElementById(id).style.opacity = 1;
            clearInterval(inter);
        }
        document.getElementById(id).style.opacity =op;
    }, 50);

}