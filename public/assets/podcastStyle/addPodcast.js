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
