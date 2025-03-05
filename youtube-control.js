let players = [];

function onYouTubeIframeAPIReady() {
    let iframes = document.querySelectorAll(".video-iframe");
    iframes.forEach((iframe, index) => {
        players[index] = new YT.Player(iframe, {
            events: {
                "onStateChange": onPlayerStateChange
            }
        });
    });
}

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING) {
        players.forEach((player) => {
            if (player !== event.target) {
                player.pauseVideo();
            }
        });
    }
}
