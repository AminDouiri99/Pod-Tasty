let uId
window.addEventListener('load', function() {
        console.log("aa")
        const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
        uId = document.getElementById("userId").value
        // console.log(uId)

        url.searchParams.append('topic', 'http://127.0.0.1:8000/addnotification/'+uId)
        let eventSource = new EventSource(url);
        eventSource.addEventListener('message',function(event){
            console.log(event.data)
            $.post('/refreshnotification',{notifId: event.data}, function(data){
                console.log("aa")
                console.log(data)
               // document.getElementById('notifnotviewed').textContent(data)
            })
        })
})