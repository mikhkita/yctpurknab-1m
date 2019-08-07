// 
//	Kit Carousel v1.0
//
// 	Developed by Mike Kitaev
//
//	Website: http://kitaev.pro/
//	E-mail: mike@kitaev.pro
//

(function($) {
	$.fn.KitCarousel = function(options) {
		var _ = this,
			easing = {
				linear: function (t) { return t },
				InQuad: function (t) { return t*t },
				OutQuad: function (t) { return t*(2-t) },
				InOutQuad: function (t) { return t<.5 ? 2*t*t : -1+(4-2*t)*t },
				InCubic: function (t) { return t*t*t },
				OutCubic: function (t) { return (--t)*t*t+1 },
				InOutCubic: function (t) { return t<.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1 },
				InQuart: function (t) { return t*t*t*t },
				OutQuart: function (t) { return 1-(--t)*t*t*t },
				InOutQuart: function (t) { return t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t },
				InQuint: function (t) { return t*t*t*t*t },
				OutQuint: function (t) { return 1+(--t)*t*t*t*t },
				InOutQuint: function (t) { return t<.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t }
			};

		options = $.extend({
			duration : 500,
			fps : 60,
			dots : true,
			buttons : true,
			touch : true,
			slidesToScroll : 1,
			swipeEasing : "OutCubic",
			centerMode : false,
			buttonEasing : "OutCubic",
			prevButtonClass : "b-kit-carousel-prev",
			nextButtonClass : "b-kit-carousel-next",
			dotsClass : "b-kit-carousel-dots",
			autoPlay : false,
			autoPlaySpeed : 3000,
			states : [
				{
					left : -200
				},
				{
					left : 0
				},
				{
					left : 200
				},
				{
					left : 400
				},
				{
					left : 600
				}
			],
			style : function(s){
				return {
					"left" : s.left
				};
			},
			styleFunction : null,
			afterDomChange : function(){

			},
			afterInit : function(){

			},
			resize : function(){

			}
		}, options);

       	_.extend({ 
       		items : [],
       		queue : [],
       		nav : null,
       		dots : [],
       		dotsCont : null,
       		buttons : null,
       		wrap : null,
			dragged : false,
			startX : null,
			centerIndex : 0,
			slideWidth : null,
			animation : false,
			pageOffset : 0,
			globeX : 0,
			wasDrag : false,
			isHovered : false,
			isWindowOpened : true,
			points : [],
			isTouch : ('ontouchstart' in document.documentElement),
       		o : options 
       	});

		return this.each(function(){
			_.extend({
				_init : function (){
					_.slideWidth = _.children().first().width();

					var ind = 1;
					_.children().each(function(){
						_.items.push($(this).attr("data-s", ind).clone());
						ind++;
					}).remove();

					_.centerIndex = (_.o.centerMode)?(Math.floor(_.o.states.length/2)):1;

					// Create wrap
					_.wrap = _.append("<div class='b-kit-carousel-wrap'></div>").find(".b-kit-carousel-wrap").css({
						height: "100%",
						width: "100%"
					});

					_.resizeHandler();

					_.createNav();

					_.initHandlers();

					_.afterInit();
				},

				appendItems : function(){
					_.wrap.html("");

					// Append items from center
					for( var i = _.centerIndex; i <= _.o.states.length - 1; i++ )
						_.wrap.append( _.items[ ( i - _.centerIndex ) % _.items.length ].clone().attr("data-i", i));

					// Append items before center
					var tmp = _.items.length;
					for( var i = _.centerIndex - 1; i >= 0; i-- ){
						tmp = ( tmp + _.items.length - 1 ) % _.items.length;
						_.wrap.prepend( _.items[ tmp ].clone().attr("data-i", i));
					}
				},

				createNav : function(){
					if( _.o.dots || _.o.buttons ){
						_.nav = _.append("<div class='b-kit-carousel-nav'></div>").find(".b-kit-carousel-nav");
					}

					if( _.o.dots ){
						_.dotsCont = _.nav.append("<ul class='" + _.o.dotsClass + "'></ul>").find("." + _.o.dotsClass);
						for( var i in _.items ){
							_.dotsCont.append("<li>" + ( i * 1 + 1 ) + "</li>");
							_.dots[i] = _.dotsCont.find("li").eq(i);
						}
						_.dots[0].addClass("active");
					}

					if( _.o.buttons ){
						_.buttons = _.nav.prepend("<button class='b-kit-carousel-button b-kit-carousel-prev "+_.o.prevButtonClass+"' value='Назад'>").append("<button class='b-kit-carousel-button b-kit-carousel-next "+_.o.nextButtonClass+"' value='Вперед'>").find(".b-kit-carousel-button");
					}
				},

				render : function (t){
					var side = t < 0 ? -1 : 1,
						y = ( Math.abs(t) > 0.5 ) ? -1*side : 0;

					if( _.o.dots ){
						_.dotsCont.children().removeClass("active");
						_.dots[ _.wrap.children("[data-i='" + ( _.centerIndex * 1 + y ) + "']").attr("data-s") - 1 ].addClass("active");
					}

					if( Math.abs(t) < 1 ){
						_.setStates(t);
					}else{
						_.startX += _.slideWidth * side;

						_.setStates(side);

						_.changeStates(side);

						_.setStates( ( Math.abs(t) - 1 ) * side );
					}
				},

				changeStates : function (side){
					if( side < 0 ){
						_.wrap.children().eq(0).remove();
						_.wrap.children().each(function(){
							$(this).attr("data-i", $(this).attr("data-i")*1 - 1);
						});

						_.wrap.append( _.items[( _.wrap.children().last().attr("data-s") * 1 ) % _.items.length].clone().attr("data-i", _.o.states.length - 1 ) );
					}else{
						_.wrap.children().last().remove();
						_.wrap.children().each(function(){
							$(this).attr("data-i", $(this).attr("data-i")*1 + 1);
						});
						_.wrap.prepend( _.items[( _.wrap.children().eq(0).attr("data-s") * 1 - 2 + _.items.length ) % _.items.length].clone().attr("data-i", 0) );
					}
					_.afterDomChange();
				},

				animateTo : function (toT, duration, easing, pageX){
					// clearTimeout(_.animation);
					_.startX = ( _.startX == null ? 0 : _.startX );
					pageX = typeof pageX == "number" ? pageX : _.startX;

					var iter = 0,
						side = _.prevSide = toT < 0 ? -1 : 1,
						offset = Math.abs( pageX - _.startX ),
						to = Math.abs( _.slideWidth * toT ) - offset,
						count = Math.ceil( duration / (1000/_.o.fps) ),
						step = 1/count;

					toT = Math.abs(toT);

					animation = setTimeout(function run(last){
						iter++;

						_.globeX = pageX + easing( step * iter ) * side * to;
						var t = Math.abs( _.globeX - _.startX ) / _.slideWidth;

						if( typeof last == "number" ){
							_.render(0);
							_.destroyAnimation();
							_.checkQueue();
						}else{
							if( iter < count ){
								_.animation = setTimeout( run, (1000/_.o.fps) );
							}else{
								_.animation = setTimeout( function(){ run(1); }, (1000/_.o.fps));
							}
							_.render( t * side );
						}
					});
				},

				destroyAnimation : function (){
					clearTimeout(_.animation);
					_.animation = false;
					if( _.o.dots ){
						_.dotsCont.children().removeClass("to");
					}
				},

				checkQueue : function (){
					if( _.queue.length > 0 ){
						var item = _.queue.pop();
						_.animateTo( item.len, item.duration, item.easing );
					}
				},

				buttonAnimate : function (len){
					if( typeof _.animation != "boolean" ){
						_.queue.push({
							len : len,
							duration : _.durFunc( Math.abs( len ) ),
							easing : easing[_.o.buttonEasing]
						});	
					}else{
						_.animateTo( len, _.durFunc( Math.abs( len ) ), easing[_.o.buttonEasing] );
					}
				},

				setStates : function (t){
					// console.log(t);
					_.wrap.children().each(function(){
						var style = _.getStyle( $(this), t );
						_.setStyleToItem( $(this), style );
					});
				},

				setStyleToItem : function (el, s){
					if( typeof _.o.styleFunction == "function" ){
						_.o.styleFunction(el, s);
					}else{
						el.css(_.o.style(s));
					}
				},

				getStyle : function (el, t){
					var index = el.attr("data-i"),
						state = _.o.states[index];

					if( t == 0 || ( t > 0 && index == _.o.states.length-1 ) || ( t < 0 && index == 0 ) ){
						return state;
					}else if( t < 0 || t > 0 ){
						var side = t < 0 ? -1 : 1,
							prevState = _.o.states[index*1 + side ],
							out = {};

						for( var i in state )
							out[i] = state[i] + ( state[i] - prevState[i] ) * t * side * -1;
						state = out;
					}else{

					}
					return state;
				},

				addPoint : function (arr, x){
					for( var i = arr.length-1; i >= 0; i-- )
						arr[i] = arr[i-1];
					arr[0] = x;

					return arr;
				},

				durFunc : function (c){
					switch (c) {
					   	case 1:
					   		return _.o.duration;
					    	break;
					   	case 2:
					   		return _.o.duration/3 + _.o.duration;
					    	break;
					    case 3:
					   		return _.o.duration/3*2 + _.o.duration;
					    	break;
					   	default:
					   		return _.o.duration*2;
							break;
					}
				},

				initHandlers : function(){
					$(window).resize(function(){ _.resizeHandler(); });

					// Если используется DragNDrop, биндим обработчики
					if( _.o.touch ){
						_.wrap.on( _.isTouch ? "touchstart" : "mousedown", function(y){
							e = _.isTouch ? y.originalEvent.touches[0] : y;
							_.dragged = true;		

							if( typeof _.animation != "boolean" ){
								_.pageOffset = _.globeX - e.pageX;
								_.points = [_.globeX, _.globeX, _.globeX];
								_.wasDrag = true;
							}else{
								_.startX = e.pageX;
								_.pageOffset = 0;
								_.points = [_.startX, _.startX, _.startX];
							}

							_.destroyAnimation();
							_.queue = [];
							if(!_.isTouch) return false;
						});	

						$(document).on( _.isTouch ? "touchmove" : "mousemove", function(y){
							if( !_.dragged ) return true;
							e = _.isTouch ? y.originalEvent.touches[0] : y;

							var t = ( e.pageX + _.pageOffset - _.startX ) / _.slideWidth;

							_.render(t);

							_.points = _.addPoint(_.points, e.pageX + _.pageOffset);

							_.wasDrag = true;

							return false;
						});	

						$(document).on( _.isTouch ? "touchend" : "mouseup", function(y){
							if( !_.dragged ) return true;
							_.dragged = false;
							var side = _.points[0] - _.startX < 0 ? -1 : 1,
								len = Math.ceil( Math.abs( _.points[0] - _.points[_.points.length - 1] )/100 );

							if( len == 0 ){
								if( _.pageOffset != 0 ){
									side = _.prevSide;
									len = 1;
								}else{
									if (_.wasDrag) return true;
								}
							}

							setTimeout(function(){_.wasDrag = false;},30);

							_.animateTo( len*side, _.durFunc(len), easing[_.o.swipeEasing], _.points[0] );
							if (_.wasDrag) return false;
						});	
					}

					$("body").on("click", "a", function(){
						if( _.wasDrag ){ return false;}
					});

					_.hover(function(){
						_.isHovered = true;
					}, function(){
						_.isHovered = false;
					});

					$(window).blur(function() {
						_.isWindowOpened = false;
					});

					$(window).focus(function() {
						_.isWindowOpened = true;
					});


					// Если используются точки, биндим на них обработчики
					if( _.o.dots ){
						_.dotsCont.children().on( _.isTouch ? "touchstart" : "mousedown", function(){
							var len = _.dotsCont.find(".active").index() - $(this).index();
							len = _.dotsCont.find(".to").length ? _.dotsCont.find(".to").index() - $(this).index() : len;
							_.buttonAnimate( len );

							_.dotsCont.children().removeClass("to");
							$(this).addClass("to");
						});
					}

					// Если используются кнопки, биндим на них обработчики
					if( _.o.buttons ){
						_.buttons.on( _.isTouch ? "touchstart" : "mousedown", function(){
							_.buttonAnimate( $(this).hasClass("b-kit-carousel-next") ? -1 : 1 );
						});
					}

					// Если включено автопроигрывание
					if( _.o.autoPlay ){
						setInterval(function(){
							if( _.is(':visible') && !_.isHovered && _.isWindowOpened ){
								_.buttonAnimate( -1 );
							}
						}, _.o.autoPlaySpeed);
					}
				},

				afterDomChange : function(){
					_.o.afterDomChange(_);
				},

				afterInit : function(){
					_.o.afterInit(_);
					
					_.afterDomChange();
				},

				resizeHandler : function(){
					_.slideWidth = ( !_.wrap.children().length )?_.slideWidth:_.wrap.children().first().width();

					_.o.resize(_);

					if( _.o.states.length != _.wrap.children().length ){
						_.appendItems();

						// TODO: убрать!
						if( _.dotsCont !== null ){
							_.dotsCont.find(".active").removeClass("active");
							_.dotsCont.find("li").eq(0).addClass("active");
						}
					}

					_.setStates(0);
				}
			});

			_._init();
       	});
	};
})(jQuery);