
$(function(){
	var val1 = "";
	var val2 = "";
	val1 = ".secondFlash";
	val2 = ".firstFlash";
	$(val1).css('display','none');
	$(val2).css('display','none');

	setInterval(function(){
		$(val1).fadeIn("normal",function(){$(val1).fadeOut()});
		$(val2).css('display','none');
		if (val1 == ".secondFlash") {
			val1 = ".firstFlash";
			val2 = ".secondFlash";
		} else {
			val1 = ".secondFlash";
			val2 = ".firstFlash";
		}
	},1500);
});


