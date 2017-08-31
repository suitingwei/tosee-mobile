$(document).ready(function(){
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	var videoDom = $("#playvideo").get(0);
	var playStatus = 'play';
	var videoLoaded = false;

	$("#play").click(function(){
		$(this).hide();
		if( videoLoaded == false ) {
			videoDom.load();
			videoLoaded = true;
		}
		videoDom.play();
	})

	debugEvents(videoDom);

	if( hasJoinUser == 0 ) {
		var minHeight = $("#video").height() - $(window).height() + 50;
		$("#join-user").css("height", minHeight);
	}

	$(".answer").click(function(){
		var isChoose = $(this).attr("data-choose");
		if(isChoose == 1) {
			var imgSrc = "http://s.toseeapp.com/mp/img/smile.png";
			var imgText = "竞猜成功！";
		} else {
			var imgSrc = "http://s.toseeapp.com/mp/img/cry.png";
			var imgText = "竞猜失败！";
		}

		$("#answer-tip-img").attr("src", imgSrc);
		$("#answer-tip-text").html(imgText);

		$(this).children("img").attr("src", "http://s.toseeapp.com/mp/img/choosed.png");
		$(this).children("span").css("color", "#ed4956");
		var answerTopic = $('#answer-tip');
		answerTopic.modal("show");
	})

	$("#praise, #praise-img").click(function(ev) {
		var opacity = $("#praise").css("opacity");
		if( opacity == 1 ) {
			$("#praise-img").attr("src", "http://s.toseeapp.com/mp/img/praise_hollow.png");
			$("#praise").css("background", "#222222").css("opacity", 0.14);
		} else {
			$("#praise-img").attr("src", "http://s.toseeapp.com/mp/img/praise_honest.png");
			$("#praise").css("background", "#ed4956").css("opacity", 1);
		}
		var topicId = $("#praise-img").attr("data-id");
		$.post("/mp/parise", {topicId: topicId});
	})

	$("#share, #share-img").click(function(ev) {
		$("#share-component").modal("show");
	});

	$(".component").click(function(){
		$(this).siblings().css("opacity", 1).children(".name").css("opacity", 1);
		$(this).css("opacity", 0.5).children(".name").css("opacity", 0.5);
		$("#share-tip").modal("show");
	});

	$(".cancel").click(function(ev) {
		$(this).css("opacity", 0.5);
		$("#share-component").modal("hide");
		$("#share-tip").modal("hide");
	});

	$("#share-component").on('hidden.bs.modal', function (e) {
		$("#share-img").attr("src", "http://s.toseeapp.com/mp/img/share.png");
		$(".component").css("opacity", 1).children(".name").css("opacity", 1);
		$(".cancel").css("opacity", 1);
	});

	$("#answer-tip").on("hidden.bs.modal", function(e){
		$(".answer").children("img").attr("src", "http://s.toseeapp.com/mp/img/choose.png");
		$(".answer").children("span").css("color", "#fff");
	});

	$("#show-answer").click(function(ev) {
		$('#answer-tip').modal("hide");
		$("#topic").hide();
		videoDom.play();
	});

	function debugEvents(video) {
		[
			'loadstart',
			'progress',
			'suspend',
			'abort',
			'error',
			'emptied',
			'stalled',
			'loadedmetadata',
			'loadeddata',
			'canplay',
			'canplaythrough',
			'playing', // fake event
			'waiting',
			'seeking',
			'seeked',
			'ended',
		    'durationchange',
			'timeupdate',
			'play', // fake event
			'pause', // fake event
	        'ratechange',
		    'resize',
		    'volumechange',
			'webkitbeginfullscreen',
			'webkitendfullscreen',
		].forEach(function (event) {
			video.addEventListener(event, function () {
				currentTime = video.currentTime;
				if( currentTime >= $("#playvideo").attr("data-time") && playStatus == 'play' ) {
					playStatus = 'pause';
					video.pause();
					$("#topic").show();
				//	video.webkitExitFullscreen();
				}
				if( event == 'ended' ) {
					playStatus = 'play';
					$("#play").show();
					//video.webkitExitFullscreen();
				}
			});
		});
	}
});
