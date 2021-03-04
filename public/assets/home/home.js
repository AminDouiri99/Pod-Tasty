function changeButt(x) {
    if (x == 1) {
    document.getElementById("podPlayButt").style.display = "none";
    document.getElementById("podPlayButtFull").style.display = "inherit";
    }else {

        document.getElementById("podPlayButtFull").style.display = "none";
        document.getElementById("podPlayButt").style.display = "inherit";
    }
}