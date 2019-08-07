$(document).ready(function(){

	var items = [],
		count = $(".b-comp").length,
		nowItem = 0,
		blocked = false,
		mainInt,
		autoPlay = false;

	$(".b-comp").each(function(){
		items.push($(this));
	});

	$(".b-get-more").click(function(){
		clearInterval(mainInt);
	});

	$(".b-nav-left").click(function(){
		var next = ( nowItem - 1 >= 0 )?(nowItem-1):(count-1);
		goTo(next);
		clearInterval(mainInt);
	});

	$(".b-nav-right").click(function(){
		var next = ( nowItem + 1 < count )?(nowItem+1):0;
		goTo(next);
		clearInterval(mainInt);
	});

	function goTo(next){
		if( blocked ) return false;
		blocked = true;
		var nowDom = items[nowItem],
			nextDom = items[next];
		nowDom.fadeOut(1000);
		$(".b-more").fadeOut(1000);
		nextDom.find(".b-comp-cont").hide();
		nextDom.find(".b-card").hide();
		nextDom.css("z-index",11);
		nextDom.fadeIn(1000);
		nextDom.find(".b-comp-cont").delay(1100).fadeIn(500);
		if( nextDom.find(".b-card").length ){
			var tmp = 0;
			nextDom.find(".b-card").each(function(){
				$(this).delay(1500+tmp*300).fadeIn(500);
				tmp++;
			});
			setTimeout(function(){
				blocked = false;
				nextDom.css("z-index",10);
				nowItem = next;
				$(".b-more").fadeIn(500);
			},1500+tmp*300);
		}else{
			setTimeout(function(){
				blocked = false;
				nextDom.css("z-index",10);
				nowItem = next;
				$(".b-more").fadeIn(500);
			},1100);
		}
	}

	function whenScroll(){
		var scroll = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
			if( !autoPlay && scroll > $(".b-5").offset().top - 150 ){
				autoPlay = true;
				mainInt = setInterval(function(){
					var next = ( nowItem + 1 < count )?(nowItem+1):0;
					goTo(next);
				},5000);
			}
	}
$(window).scroll(whenScroll);
	whenScroll();

});