
$(document).ready(function(){
	$(".robot").click(function(){
		$(".show > *").not("#tip").hide();
		$("#tip").toggle();
	});
	$(".command").click(function(){
		var classstr = $(this).attr("id");
		$(".show > *").hide();
		$("."+classstr).show();
		if($("."+classstr+":has(source)").length>0){
			var myVideo=document.getElementById(classstr+"id");
			myVideo.play();
		}
	});
	$("#keyword").blur(function(){
		var obj = $(this);
		var keyword = $(obj).val();
		getkeyword = false;
		$(".command span").each(function(){
			if($(this).text() == keyword){
				var classstr = $(this).parent().attr("id");
				$(".show > *").hide();
				$("."+classstr).show();
				if($("."+classstr+":has(source)").length>0){
					var myVideo=document.getElementById(classstr+"id");
					myVideo.play();
				}
				$(obj).val('')
				getkeyword = true;
				return;
			}
		});
		if(!getkeyword){
			$(".show > *").not("#tip").hide();
			$("#tip").show();
			$(obj).val('')
		}
	});
});
