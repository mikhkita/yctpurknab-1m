var custom = [];
$(document).ready(function(){	

	// if( device.mobile() || device.tablet() ){
	// 	$(".anim").each(function(){
	// 		var $this = $(this);
	// 			if( $this.attr("data-func") ){
	// 				custom[$this.attr("data-func")]($this);
	// 			}
	// 			$this.removeClass($this.attr("data-anim")).addClass($this.attr("data-anim")+"-show");
	// 	});
	// }else{
		var myWidth, myHeight;

		function whenScroll(){
			var scroll = ((document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop)+myHeight;
			$(".anim").each(function(){
				var tmp = ( $(this).attr("data-cont") )?$($(this).attr("data-cont")):$(this),
					offset = ( tmp.attr("data-offset") )?tmp.attr("data-offset"):tmp.height()/2;
				if( tmp.offset().top < scroll-offset ){
					var $this = $(this);
					if( $this.attr("data-func") ){
						custom[$this.attr("data-func")]($this);
					}
					$this.addClass("show");
					$(this).removeClass("anim");
				}
			});
		}

	  	function resize(){
	  		if( typeof( window.innerWidth ) == 'number' ) {
				myHeight = window.innerHeight;
			} else if( document.documentElement && ( document.documentElement.clientWidth || 
			document.documentElement.clientHeight ) ) {
				myHeight = document.documentElement.clientHeight;
			} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			  	myHeight = document.body.clientHeight;
			}
	  	}
	  	$(window).resize(resize);
	  	resize();
	  	$(window).scroll(whenScroll);
		whenScroll();
	// }

	// Кастомные функции
	custom['upPercent'] = function(el){
		var num = el.attr("data-num")*1,
			out = el.find(".b-proc-text span"),
			dur = 1000,
			timer,
			now = out.html()*1;
		timer = setInterval(function(){
			now++
			out.html(now);
			if( now >= num ) clearInterval(timer);
		},dur/(num-now));
	}

});