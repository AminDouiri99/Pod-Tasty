function changeButt(x, id) {
    if (x == 1) {
    document.getElementById("podPlayButt"+id).style.display = "none";
    document.getElementById("podPlayButtFull"+id).style.display = "inherit";
    }else {

        document.getElementById("podPlayButtFull"+id).style.display = "none";
        document.getElementById("podPlayButt"+id).style.display = "inherit";
    }
}