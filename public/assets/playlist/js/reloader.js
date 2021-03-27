function searchplaylist(){
    let comment = document.getElementById("searchInputp").value;
    $.post("/filterPlaylists", {text: comment}, function (data) {
        document.getElementById("playlistcontainer").innerHTML = "";
        document.getElementById("playlistcontainer").innerHTML = data;
        console.log(data);
    });
}


function searchplaylist1(){
    let comment = document.getElementById("searchInputp").value;
    $.post("/filterPlaylists1", {text: comment}, function (data) {
        document.getElementById("playlistcontainer").innerHTML = "";
        document.getElementById("playlistcontainer").innerHTML = data;
        console.log(data);
    });
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});