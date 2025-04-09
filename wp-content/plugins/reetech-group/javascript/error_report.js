window.addEventListener('error', function(e){
    try {
        var data = new FormData();
        data.append('js' , true) ;
        data.append('cid', window.cid);
        data.append('identifier' ,  "ohvfr96w49749q4t9nnu9yfhgomhfmowfn32330i9jffw");
        data.append('type' , 1) ;
        data.append('browser', window.navigator.userAgent);
        data.append('location', window.location.href);
        
        if (e) {
            data.append('error', String(e));
            data.append('msg', e.message);
            data.append('stack', e.error && e.error.stack);
            data.append('file', e.filename);
            data.append('line', e.lineno);
        }

        var xhr = new XMLHttpRequest();

        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    console.log('Error report sent successfully');
                } else {
                    console.log('Error sending the report');
                }
            } catch (error) { }
        };

        // xhr.open('POST', '/ErrorAlert.php', true);
        // xhr.send(data);
    } catch (error) { }
});
