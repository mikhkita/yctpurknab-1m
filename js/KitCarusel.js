$(document).ready(function(){
	var count = $(".b-thumbs-cont li").length,
		itemWidth = 184,
		nowItem = 0,
		show = 5,
		nowSlide = 0;

	$(".b-thumbs-cont").css({
		"width" : count*itemWidth
	});

	$(".b-thumbs-prev").click(function(){
		if( nowItem > 0 ){
			nowItem--;
			goTo(nowItem);
		}
	});
	$(".b-thumbs-next").click(function(){
		if( nowItem < (count-show) ){
			nowItem++;
			goTo(nowItem);
		}
	});

	function goTo(nowItem){
		TweenLite.to($(".b-thumbs-cont"), 0.3, { "margin-left" : -(nowItem*itemWidth), ease : Quad.easeInOut } );
		checkBtns();
	}

	function checkBtns(){
		$(".b-thumbs-btn").removeClass("blocked");
		if( nowItem == 0 ) $(".b-thumbs-prev").addClass("blocked");
		if( nowItem == count-show ) $(".b-thumbs-next").addClass("blocked");
	}	

	checkBtns();

});