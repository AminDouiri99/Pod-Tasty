function changeButt(x, id) {
    if (x == 1) {
    document.getElementById("podPlayButt"+id).style.display = "none";
    document.getElementById("podPlayButtFull"+id).style.display = "inherit";
    }else {

        document.getElementById("podPlayButtFull"+id).style.display = "none";
        document.getElementById("podPlayButt"+id).style.display = "inherit";
    }
}

function showPod(id) {
    for(let tag of document.getElementById("tagsWrapper").childNodes) {
        if(tag.id === "tag"+id) {
            tag.className = "badge-pill badge-light badgeTag";

        } else {
        tag.className = "badge-pill badge-dark badgeTag";
        }
         tag.disabled=true;
    }
   $.post("/filterPodcasts", {id:id}, function(data) {
       for(let tag of document.getElementById("tagsWrapper").childNodes) {
           tag.disabled = false;
       }
       if(data === "Sorry we are out of tasty podcasts !") {
        document.getElementById("allContent").className = "allContentEmpty";
       }else {

           document.getElementById("allContent").className = "";
       }
       document.getElementById("allContent").innerHTML = data;
   })
}