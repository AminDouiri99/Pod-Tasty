function fadeIn(id,x) {
    document.getElementById(id).style.opacity = 0;
    if(x === 1){
        document.getElementById(id).classList.add('active');
    } else {
        document.getElementById(id).style.display = "inherit";
    }
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
