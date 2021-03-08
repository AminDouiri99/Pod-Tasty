var extensions = ["M4A", "FLAC", "MP3", "WMA","AAC" ];
function openFileLoader() {
    document.getElementById('podcast_PodcastSource').click();
    $('#podcast_PodcastSource').change(function() {
        var file = $('#podcast_PodcastSource')[0].files[0].name;
        var extc = file.substr(file.lastIndexOf('.')+1, file.length);
        if (extensions.indexOf(extc.toUpperCase()) === -1) {
            document.getElementById("podcastAudioName").style.color="red";
            document.getElementById("podcastAudioName").innerHTML="Invalid audio file !";
        } else {
            document.getElementById("podcastAudioName").style.color="black";
            document.getElementById("podcastAudioName").innerHTML=file;
            document.getElementById("podcastSpinner").style.display = "inherit";
            setTimeout(function(){

                document.getElementById("podCarItem1").classList.remove('active');
                fadeIn("podCarItem2",1);
            },1700);
        }
    });

}

// function addImg(file)
// {
//     var input=file.target;
//     var reader = new FileReader();
//     reader.onload = function(){
//         var dataURL = reader.result;
//         var output = document.getElementById('PodcastImage');
//         output.src = dataURL;
//         output.style.display="inherit";
//     };
//     reader.readAsDataURL(input.files[0]);
//     document.getElementById("PodcastImage").style.opacity="0.7";
//     //document.getElementById("sub").disabled=false;
//     //document.getElementById("ch").innerHTML="Change <img src='pen.png' width='20px' height='15px'/>";
// }



function fadeIn(id,x) {
    if(x === 1){
        document.getElementById(id).classList.add('active');
    } else {
        document.getElementById(id).style.display = "inherit";
    }
    document.getElementById(id).style.opacity = 0;
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
