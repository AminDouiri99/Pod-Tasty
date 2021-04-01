var extensions = ["M4A", "FLAC", "MP3", "WMA","AAC" ];
let tags=[];
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


function addRemoveTag(id) {
    console.log("xx");
    if(document.getElementById(id).className === "btn btn-light") {
        tags.push(id);
    document.getElementById(id).className= "btn btn-info";
    document.getElementById("podcast_tags").value ="";
    for(let i =0; i <tags.length; i++) {
    document.getElementById("podcast_tags").value = document.getElementById("podcast_tags").value+','+tags[i];
    }
    } else {
        tags.splice(tags.indexOf(id), 1);
        document.getElementById(id).className= "btn btn-light";
        document.getElementById("podcast_tags").value ="";
        for(let i =0; i <tags.length; i++) {
            document.getElementById("podcast_tags").value = document.getElementById("podcast_tags").value+','+tags[i];
        }
    }
}