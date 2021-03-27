function searchchannel(){
    let comment = document.getElementById("searchInputp").value;
    $.post("/filterChannels", {text: comment}, function (data) {
        document.getElementById("channelcontainer").innerHTML = "";
        document.getElementById("channelcontainer").innerHTML = data;
        console.log(data);
    });
}