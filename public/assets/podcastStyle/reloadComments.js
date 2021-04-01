let comNumb = 0;
let pId;
window.addEventListener('load', function() {
    const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
    pId = document.getElementById("podcastId").value;
    url.searchParams.append('topic', 'http://127.0.0.1:8000/addComment')
    const eventSource = new EventSource(url);
    eventSource.addEventListener('message', function(event){
    $.post('/refreshCommentsList', {comId: event.data, podId: pId, currentR: window.location.href}, function(data) {
          if(data !== "-1") {
            addCommentToView(data);
          }
        })
    });

});



function checkKey(event,id){
    if(event.code==="Enter" && document.getElementById('comment').value!==""){
        document.getElementById('comment').disabled  = true;
        document.getElementById('warningDiv').style.display = "none";
        sendComment(document.getElementById('comment').value, id);
        document.getElementById('comment').value  = "Posting comment...";
        document.getElementById('comment').style.borderBottomColor="transparent";
    }
}

function sendComment(comment, id){
    $.post("/addComment",{comment:comment,podId:id}, function(data) {
        setTimeout(function (){
            if (document.getElementById('comment') != null) {
                document.getElementById('comment').disabled = false;
                document.getElementById('comment').value  = "";
                document.getElementById('comment').style.borderBottomColor="white";
            }
        if (data === "1") {
            document.getElementById('warningDiv').style.display = "inherit";
        }   else if (data === "0"){
            document.getElementById('warningDiv').style.display = "inherit";
            document.getElementById('warningDiv').innerHTML = "Sorry, commenting for this podcast is disabled for now";
        }
        },500);
    })
}
function deleteComment(id){
    if (document.getElementById("commentsLength")!=null) {
        comNumb = parseInt(document.getElementById('commentsLength').innerHTML);
    }
    document.getElementById("deletingMsg"+id).style.display="inherit";

    $.post("/deleteComment",{commentId:id},function(data) {
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
            fadeIn("carItem2",1);
            break;
        }
        case 2:
        {
            topicRate = rate;
            document.getElementById("topicRate"+rate).style.color =color[rate];

            document.getElementById("carItem2").classList.remove('active');
            fadeIn("carItem3",1);
            break;
        }
        case 3:
        {
            hostRate = rate;
            document.getElementById("hostRate"+rate).style.color =color[rate];

            document.getElementById("carItem3").classList.remove('active');
            fadeIn("carItem4",1);
            break;
        }
        case 4:
        {
            soundQualityRate = rate;
            document.getElementById("soundQualityRate"+rate).style.color =color[rate];

            document.getElementById("carItem4").classList.remove('active');
            fadeIn("carItem5",1);
            let finalRate = (podcastRate+topicRate+hostRate+soundQualityRate)/4;
            $.post("/addReview",{review:finalRate}, function(data){
                let reviewId = data.substr(0,data.indexOf(' '));
                let reviewMoy = data.substr(data.indexOf(' ')+1, data.length );
                setTimeout(function(){
                    document.getElementById('userReviewSpan').innerHTML = finalRate;
                    setRatingColor(""+finalRate, document.getElementById("userReviewSpan"));

                    document.getElementById("carItem5").classList.remove('active');
                    fadeIn("carItem6",1);
                    document.getElementById("reviewButton").style.color = color[Math.round(finalRate)];

                    setTimeout(function (){
                        if (document.getElementById("ratingMoyValue") != null) {
                           let moyRating = parseFloat(document.getElementById("ratingMoyValue"));

                        }
                        document.getElementById("newReviewBody").style.display = "none";
                        if (document.getElementById("deleteReviewButton") == null) {
                            document.getElementById("carItem6").classList.remove('active');
                            fadeIn("carItem1",1);
                            document.getElementById("deleteReviewButton2").addEventListener("click", function (){
                                deleteReview(parseInt(reviewId));

                            });
                            document.getElementById("deleteReviewButton2").style.display = "inherit";
                            document.getElementById("userRatingMoyValue").innerHTML = ""+finalRate;
                            setRatingColor(""+finalRate, document.getElementById("userRatingMoyValue"));


                        } else {
                            document.getElementById("deleteReviewButton").removeEventListener("click", deleteComment, true);
                            document.getElementById("deleteReviewButton").addEventListener("click", function (){
                                deleteReview(parseInt(reviewId));

                            });
                            document.getElementById("userRatingMoyValue").innerHTML = ""+finalRate;
                            setRatingColor(""+finalRate, document.getElementById("userRatingMoyValue"));
                        }

                        fadeIn("editReviewBody",2);

                    },5000);
                    if (document.getElementById("ratingMoyValue") != null) {
                        if (reviewMoy !== "") {
                    document.getElementById('ratingMoyTD').style.display = "inherit";
                    document.getElementById("ratingMoyValue").innerHTML = reviewMoy;
                    setRatingColor(reviewMoy, document.getElementById("ratingMoyValue"));
                        } else {

                            document.getElementById('ratingMoyTD').style.display = "none";
                        }
                    } else {
                        if (reviewMoy !== "") {
                            document.getElementById("ratingMoyTD").innerHTML = "Rating:<span style='margin-left: 15px'> <span class='ratingMoyValue' id='ratingMoyValue'>" + reviewMoy + "</span> / 10</span>";
                            setRatingColor(reviewMoy, document.getElementById("ratingMoyValue"));
                        }
                    }

                }, 3000)
            });
            break;
        }
    }

}


function updateComment(id,comment){
    $.post("/UpdateComment",{commentId:id, commentText:comment}, function(data) {
        document.getElementById("commentTextDiv"+id).innerHTML = comment;
        document.getElementById('editCommentText'+id).disabled  = false;
        document.getElementById('editCommentText'+id).style.borderBottomColor="white";
        showUpdateComment(2,id, comment);
    })
}

function showUpdateComment(x,id,comment) {
    if(x === 1){
        document.getElementById("commentTextDiv"+id).style.display = "none";
        document.getElementById("editButton"+id).style.opacity = 0;
        document.getElementById("editButton"+id).disabled =true;
        document.getElementById("editButton"+id).style.pointerEvents ="none";
        document.getElementById("commentEditText"+id).style.display = "inherit";
    }
    else{
        document.getElementById("editCommentText"+id).value = comment ;
        document.getElementById("editButton"+id).style.pointerEvents ="auto";
        document.getElementById("editButton"+id).style.opacity = 1;
        document.getElementById("editButton"+id).disabled =false;
        document.getElementById("commentEditText"+id).style.display = "none";
        document.getElementById("commentTextDiv"+id).style.display = "inherit";
    }

}
function checkKeyEdit(event,id){
    if(event.code==="Enter" && document.getElementById('editCommentText'+id).value!==""){
        document.getElementById('editCommentText'+id).disabled  = true;
        updateComment(id,document.getElementById('editCommentText'+id).value);
        document.getElementById('editCommentText'+id).style.borderBottomColor="transparent";
    }
}

function addRemoveFav(id) {
    $.post("/addRemoveFav", {id:id}, function(data){
        if(data === "1") {

            document.getElementById("favButton").style.color="deeppink";
        } else {
            document.getElementById("favButton").style.color="white";
        }

    })
}

function filterComments(id) {
    document.getElementById('searchSpinner').style.opacity = "1";
    let comment = document.getElementById("searchInput").value;
    $.post("/filterComments", {id: id, text: comment}, function (data) {
        document.getElementById("comments").innerHTML = "";
        document.getElementById('searchSpinner').style.opacity = "0";
        document.getElementById("comments").innerHTML = data;
    });
}
function addCommentToView(data) {
            comNumb++;
            document.getElementById('CommentsUL').innerHTML = data+document.getElementById('CommentsUL').innerHTML;
            if(document.getElementById('commentsLength') != null) {
                var x = 1;
                if(!isNaN(parseInt(document.getElementById('commentsLength').innerHTML))){
                    x += parseInt(document.getElementById('commentsLength').innerHTML);
                }
                c = "Comment";
                if (x>1){
                    c+="s";
                }

                document.getElementById('comText').innerHTML = c;
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
}