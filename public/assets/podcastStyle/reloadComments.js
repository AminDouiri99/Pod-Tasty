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