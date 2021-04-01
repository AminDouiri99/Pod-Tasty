function sendReport() {
    document.getElementById("reportButt").disabled = true;
    let reportType = $("input[type='radio'][name='inquiry[Type]']:checked").val();
    let desc = document.getElementById("reportDescription").value;
    $.post("/reclamation/report/new", {type: reportType, desc: desc, podId:pId}, function () {
        document.getElementById("successReport").style.display = "inherit";
        document.getElementById("dismissModal").click();

        setTimeout(function() {
            document.getElementById("reportDescription").value= "";
            document.getElementById("reportButt").disabled= false;
            document.getElementById("reportButton").disabled = false;
            document.getElementById("successReport").style.display = "none";
        }, 3000);
    });
}
