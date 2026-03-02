<!-- laravel echo -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    if (window.Echo) {
        window.Echo.connector.pusher.connection.bind('connected', function() {
            console.log("Terhubung ke Soketi!");
        });
        Echo.channel('indicator-channel')
            .listen('.indicator.triggered', (e) => {
                console.log(e);
                
                var wss_arena = e.message.arena;
             
            })
            .error((error) => {
                console.error('Error:', error);
            });
    } else {
        console.error('Laravel Echo is not initialized.');
    }
</script>