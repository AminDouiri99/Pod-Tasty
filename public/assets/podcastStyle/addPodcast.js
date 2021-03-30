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
