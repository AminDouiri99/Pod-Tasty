    function myFunction() {
    document.getElementById("buttonEdit").style.display="none"
    document.getElementById("name").style.display="none"
    document.getElementById("biography").style.display="none"
    document.getElementById("birthdate").style.display="none"
    document.getElementById("edittext").style.display="inherit"
    document.getElementById("firstname").style.display="inherit"
    document.getElementById("lastname").style.display="inherit"
    document.getElementById("bio").style.display="inherit"
    document.getElementById("buttonSave").style.display="inherit"
    }
    function settingBtn() {
    if(document.getElementById("settingBp").style.display=="none"){
        document.getElementById("settingNp").style.display="inherit"
        document.getElementById("settingOp").style.display="inherit"
        document.getElementById("settingBp").style.display="inherit"
        document.getElementById("settingT1").style.display="inherit"
        document.getElementById("settingT2").style.display="inherit"
        document.getElementById("settingET").style.display="inherit"
        document.getElementById("settingEB").style.display="inherit"


    }else{
        document.getElementById("settingNp").style.display="none"
        document.getElementById("settingOp").style.display="none"
        document.getElementById("settingBp").style.display="none"
        document.getElementById("settingT1").style.display="none"
        document.getElementById("settingT2").style.display="none"
        document.getElementById("settingET").style.display="none"
        document.getElementById("settingEB").style.display="none"

    }
    }
