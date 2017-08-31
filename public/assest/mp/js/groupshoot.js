$(document).ready(function(){
	var width = $(window).width();
	$("#content > li > img").each(function(i){
		$(this).width(width/3);
	})
	$("#content").fadeIn();
	$("#block").height($("#footer > img").height() + 12.5);
	$("#content li").click(function(){
		playIndex = $(this).index();
		playVideo();
	})
});

var playIndex = 0;
function playVideo() {

	if( playIndex + 1 > playList.length ) {
		playIndex = 0;
		return false;
	}

	var videoSrc = playList[playIndex];
	var videoDom = $("#my-video");

	videoDom.attr("src", videoSrc);
	videoDom[0].load();
	videoDom[0].play();

	playIndex = playIndex + 1;
}
