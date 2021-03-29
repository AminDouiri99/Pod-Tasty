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
    if(document.getElementById("tag"+id).className !== "badge-pill badge-light badgeTag") {
    for(let tag of document.getElementById("tagsWrapper").childNodes) {
        if(tag.id === "tag"+id) {
            tag.className = "badge-pill badge-light badgeTag";

        } else {
        tag.className = "badge-pill badge-dark badgeTag";
        }
    }
    }
}