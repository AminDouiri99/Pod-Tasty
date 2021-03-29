let updateName;
let updateStyle;
function choseColor(color , id) {
    if(id === 1) {
    document.getElementById("tag_tagStyle").value = color;
    }
    else {
        document.getElementById("updateTagStyle").value = color;
        if(updateStyle === color) {
            document.getElementById("updateTagButt").disabled = true;
        } else {
            document.getElementById("updateTagButt").disabled = false;
        }
    }
    for(let tag of document.getElementById("colorTable"+id).childNodes) {
       if(tag.id != null) {
        if(tag.className.indexOf(color) >-1 ) {
            $(tag).addClass("colorsChecked");

        } else {
            $(tag).removeClass("colorsChecked");
        }
       }
    }
}
function checkTagNam() {
    if(document.getElementById("updateTagName").value === "" || document.getElementById("updateTagName").value ===  updateName){
        document.getElementById("updateTagButt").disabled = true;
} else {
    document.getElementById("updateTagButt").disabled = false;
}
}
function updateTag(id, name, style) {
updateName=name;
updateStyle=style;
document.getElementById("newTagBody").style.display = "none";
document.getElementById("updateTagBody").style.display = "inherit";
document.getElementById("updateTagButt").style.display = "inherit";
document.getElementById("updateTagId").value = id;
document.getElementById("updateTagName").value = name;
choseColor(style, 2)
}

function sendUpdate() {
$.post("/updataTag", {id:document.getElementById("updateTagId").value, name:document.getElementById("updateTagName").value, style:document.getElementById("updateTagStyle").value}, function() {

});
}

function resetForm() {

    document.getElementById("newTagBody").style.display = "inherit";
    document.getElementById("updateTagBody").style.display = "none";
    document.getElementById("updateTagButt").style.display = "none";
}