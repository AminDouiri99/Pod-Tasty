function searchplaylist(){
    let comment = document.getElementById("searchInputp").value;
    $.post("/filterPlaylists", {text: comment}, function (data) {
        document.getElementById("playlistcontainer").innerHTML = "";
        document.getElementById("playlistcontainer").innerHTML = data;
        console.log(data);
    });
}