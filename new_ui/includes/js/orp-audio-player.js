$(function() {

    // On Player Click
	$('.audio-table').on('click', '.orp_player button', function(e) {
        e.stopPropagation();
        var playerNum = $(this).parent('div').attr('id');

        // timeupdate event listener
        $('#'+playerNum+' audio').on('ended', function(){
            $('#'+playerNum+' button').removeClass('pause');
            $('#'+playerNum+' button').addClass('play');
        });

        // start audioFile
        if ($('#'+playerNum+' audio').get(0).paused) {
            $('#'+playerNum+' audio').get(0).play();
            $(this).removeClass('play');
            $(this).addClass('pause');

        } else { // pause audioFile
            $('#'+playerNum+' audio').get(0).pause();
            $(this).removeClass('pause');
            $(this).addClass('play');
        }

    });

})
