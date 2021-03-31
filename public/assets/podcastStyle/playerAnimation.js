window.onscroll = function(){

    if (window.scrollY ==0) {
        document.getElementById('player').style.width = "70%";
        document.getElementById('player').style.left = "50%";
        document.getElementById('player').style.height = "190px";
        document.getElementById('player').style.marginTop = "0";
        document.getElementById('podInfo').style.marginTop = "0";
        document.getElementById('podInfo').style.marginLeft = "0";
        if (document.getElementById('controls') != null)
            document.getElementById('controls').style.marginLeft = "0px";
        document.getElementById('coverImg').style.width = "190px";
        document.getElementById('coverImg').style.borderBottomLeftRadius = "20px";
        document.getElementById('coverImg').style.borderTopRightRadius = "0";
        if (document.getElementById('sliderDiv') != null)
            document.getElementById('sliderDiv').style.display = "inherit";
        if (document.getElementById('podcastTools') !=null) {
            setTimeout(function () {
                document.getElementById('podcastTools').style.display = "inherit";
            }, 200);
        }

    } else if(window.scrollY >80){
        if (document.getElementById('sliderDiv') != null)

            document.getElementById('sliderDiv').style.display = "none";
        if (document.getElementById('podcastTools') !=null) {
            document.getElementById('podcastTools').style.display = "none";
        }
        document.getElementById('podInfo').style.marginTop = "200px";
        document.getElementById('podInfo').style.marginLeft = "-210px";
        if (document.getElementById('controls') != null)
            document.getElementById('controls').style.marginLeft = "120px";
        document.getElementById('player').style.left = "92%";
        document.getElementById('player').style.width = "15%";
        document.getElementById('coverImg').style.width = "100%";
        document.getElementById('coverImg').style.borderBottomLeftRadius = "0";
        document.getElementById('coverImg').style.borderTopRightRadius = "20px";
        document.getElementById('player').style.height = "360px";
        document.getElementById('player').style.marginTop = "70px";
    }
}