
<script src="{{ asset('js/app.js') }}"></script>
<script>
   //comment nopan
   //var ArenaID = document.getElementById('IDarena').getAttribute('name');
   var ArenaID = document.getElementById('arenaid').getAttribute('name');
    if (window.Echo) {
        window.Echo.connector.pusher.connection.bind('connected', function() {
            console.log("Terhubung ke Soketi!");
        });
        Echo.channel('indicator-channel')
            .listen('.indicator.triggered', (e) => {
                var data = e.message;
                if(data.arena == ArenaID && data.event == "reload"){
                   
                    reload();
                    console.log("Reload");
                    
                }
                
             
            })
            .error((error) => {
                console.error('Error:', error);
            });
    } else {
        console.error('Laravel Echo is not initialized.');
    }
</script>