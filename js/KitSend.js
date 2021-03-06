function getNextField($form){
	var j = 1;
	while( $form.find("input[name="+j+"]").length ){
		j++;
	}
	return j;
}

function fancyOpen(el){
    $.fancybox(el,{
    	padding:0,
    	fitToView: false,
        scrolling: 'no',
        beforeShow: function(){
			$(".fancybox-wrap").addClass("beforeShow");
			if( !device.mobile() ){
		    	$('html').addClass('fancybox-lock'); 
		    	$('.fancybox-overlay').html($('.fancybox-wrap')); 
		    }
		},
		afterShow: function(){
			$(".fancybox-wrap").removeClass("beforeShow");
			$(".fancybox-wrap").addClass("afterShow");
			setTimeout(function(){
                $('.fancybox-wrap').css({
                    'position':'absolute'
                });
                $('.fancybox-inner').css('height','auto');
            },200);
		},
		beforeClose: function(){
			$(".fancybox-wrap").removeClass("afterShow");
			$(".fancybox-wrap").addClass("beforeClose");
		},
		afterClose: function(){
			$(".fancybox-wrap").removeClass("beforeClose");
			$(".fancybox-wrap").addClass("afterClose");
		},
    }); 
    return false;
}

var customHandlers = [];
var INNMask, creditorINNMask;

$(document).ready(function(){	
	var rePhone = /^\+\d \(\d{3}\) \d{3}-\d{2}-\d{2}$/,
		tePhone = '+7 (999) 999-99-99',
		reINN = /^[\d+]{10,12}$/,//любой вариант ИНН
		reINNLegal = /^\d{10}$/, //Юр. лицо
		reINNEntrepreneur = /^\d{12}$/; //Физ. лицо

	$.validator.addMethod('customPhone', function (value) {
		return rePhone.test(value);
	});

	$.validator.addMethod('INN', function (value) {
		var reg = /^.*$/;
		if($("select[name='debtor']").val() == "legal"){
			reg = reINNLegal;
		}
		if($("select[name='debtor']").val() == "entrepreneur"){
			reg = reINNEntrepreneur;
		}
		return reg.test(value);
	});

	$.validator.addMethod('creditorINN', function (value) {
		return reINN.test(value);
	});

	$(".ajax").parents("form").each(function(){
		if($(this).find("input[name=phone]").length && $(this).find("input[name=phone]").prop("required")){
			$(this).validate({
				onkeyup: true,
				rules: {
					email: 'email',
					phone: 'customPhone',
					INN: 'INN',
					creditorINN: 'creditorINN'
				}
			});
		}else{
			$(this).validate({
				onkeyup: true,
				rules: {
					email: 'email',
					INN: 'INN',
					creditorINN: 'creditorINN'
				}
			});
		}
		if( $(this).find("input[name=phone]").length ){
			$(this).find("input[name=phone]").each(function(){
				if (typeof IMask == 'function') {
					var phoneMask = new IMask($(this)[0], {
			        	mask: '+{7} (000) 000-00-00',
			        	prepare: function(value, masked){
					    	if( value == 8 && masked._value.length == 0 ){
					    		return "+7 (";
					    	}

					    	tmp = value.match(/[\d\+]*/g);
					    	if( tmp && tmp.length ){
					    		value = tmp.join("");
					    	}else{
					    		value = "";
					    	}
					    	return value;
					    }
			        });
			    } else {
					$(this).mask("+7 (999) 999-99-99");
				}
			});
		}

		if( $(this).find("input[name=creditorINN]").length ){
			if (typeof IMask == 'function') {
				$(this).find("input[name=creditorINN]").each(function(){
					creditorINNMask = new IMask($(this)[0], {
			        	mask: '0000000000[00]',
			        	placeholderChar: ' '
			        });
				});
		    } else {
				$(this).find("input[name=creditorINN]").mask("9999999999?99",{placeholder:" "});
			}
		}

		if( $(this).find("input[name=INN]").length ){
			if (typeof IMask == 'function') {
				$(this).find("input[name=INN]").each(function(){
					INNMask = new IMask($(this)[0], {
			        	mask: '0000000000',
			        	placeholderChar: ' '
			        });
				});
		    } else {
				$(this).find("input[name=INN]").mask("9999999999");
			}
		}

		$(this).find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").blur(function(){
		   $(this).valid();
		});

		$(this).find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").keyup(function(){
		   // $(this).valid();
		});
	});

	function whenScroll(){
		var scroll = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
		if( customHandlers["onScroll"] ){
			customHandlers["onScroll"](scroll);
		}
	}
	$(window).scroll(whenScroll);
	whenScroll();

	$(".fancy:not(.fancy-binded)").each(function(){
		var $popup = $($(this).attr("href")),
			$this = $(this);
		$this.fancybox({
			padding : 0,
			content : $popup,
			touch : false,
			helpers: {
	         	overlay: {
	            	locked: true 
	         	}
	      	},
	      	btnTpl: {
	      		smallBtn: '<button data-fancybox-close class="fancybox-button fancybox-button--close" title="Закрыть"></button>'
	      	},
			beforeShow: function(){
				$(".fancybox-wrap").addClass("beforeShow");
				$popup.find(".custom-field").remove();
				if( $this.attr("data-value") ){
					var name = getNextField($popup.find("form"));
					$popup.find("form").append("<input type='hidden' class='custom-field' name='"+name+"' value='"+$this.attr("data-value")+"'/><input type='hidden' class='custom-field' name='"+name+"-name' value='"+$this.attr("data-name")+"'/>");
				}
				if( $popup.attr("data-beforeShow") && customHandlers[$popup.attr("data-beforeShow")] ){
					customHandlers[$popup.attr("data-beforeShow")]($popup);
				}
			},
			afterShow: function(){
				$(".fancybox-wrap").removeClass("beforeShow");
				$(".fancybox-wrap").addClass("afterShow");
				if( $popup.attr("data-afterShow") && customHandlers[$popup.attr("data-afterShow")] ){
					customHandlers[$popup.attr("data-afterShow")]($popup);
				}
				$popup.find("input[type='text'],input[type='number'],textarea").eq(0).focus();
			},
			beforeClose: function(){
				$(".fancybox-wrap").removeClass("afterShow");
				$(".fancybox-wrap").addClass("beforeClose");
				if( $popup.attr("data-beforeClose") && customHandlers[$popup.attr("data-beforeClose")] ){
					customHandlers[$popup.attr("data-beforeClose")]($popup);
				}
			},
			afterClose: function(){
				$(".fancybox-wrap").removeClass("beforeClose");
				$(".fancybox-wrap").addClass("afterClose");
				if( $popup.attr("data-afterClose") && customHandlers[$popup.attr("data-afterClose")] ){
					customHandlers[$popup.attr("data-afterClose")]($popup);
				}
			}
		});
		$this.addClass("fancy-binded");
	});

	var open = false;
    $("body").on("mouseup", ".b-popup *, .b-popup", function(){
        open = true;
    });
    $("body").on("mousedown", ".fancybox-slide", function() {
        open = false;
    }).on("mouseup", ".fancybox-slide", function(){
        if( !open ){
            $.fancybox.close();
        }
    });

	$(".b-go").click(function(){
		var block = $( $(this).attr("data-block") ),
			off = $(this).attr("data-offset")||0,
			duration = $(this).attr("data-duration")||800;
		$("body, html").animate({
			scrollTop : block.offset().top-off
		},duration);
		return false;
	});

	$(".fancy-img").fancybox({
		padding : 0
	});

	$(".goal-click").click(function(){
		if( $(this).attr("data-goal") )
			ym(55155955, 'reachGoal', $(this).attr("data-goal"));
	});

	$(".ajax").parents("form").submit(function(){
  		if( $(this).find("input.error,select.error,textarea.error").length == 0 ){
  			var $this = $(this),
  				$thanks = $($this.attr("data-block"));

  			$this.find(".ajax").attr("onclick", "return false;");

  			if( $this.attr("data-beforeAjax") && customHandlers[$this.attr("data-beforeAjax")] ){
				customHandlers[$this.attr("data-beforeAjax")]($this);
			}

			if($this.hasClass("b-form-consultation")){
				if(!$this.find("input[name='phone']").val() && !$this.find("input[name='email']").val()){
					$(".b-form-consultation .warning").addClass("show");
					return false;
				}else{
					$(".b-form-consultation .warning").removeClass("show");
				}
			}

			if( $this.attr("data-goal") && typeof ym != "undefined" ){
	            ym(55155955, 'reachGoal', $(this).attr("data-goal"));
	        }

  			$.ajax({
			  	type: $(this).attr("method"),
			  	url: $(this).attr("action"),
			  	data:  $this.serialize(),
				success: function(msg){
					var $link;
					if( msg == "1" ){
						$link = $this.find(".b-thanks-link");
						if($this.hasClass("b-form-request")){
							$(".b-btn-popup-request").attr("href", "#b-popup-success");
						}
					}else{
						$link = $(".b-error-link");
					}

					if( $this.attr("data-afterAjax") && customHandlers[$this.attr("data-afterAjax")] ){
						customHandlers[$this.attr("data-afterAjax")]($this);
					}

					$.fancybox.close();
					$link.click();
				},
				error: function(){
					$.fancybox.close();
					$(".b-error-link").click();
				},
				complete: function(){
					$this.find(".ajax").removeAttr("onclick");
					$this.find("input[type=text],textarea").val("").parent().removeClass("focus not-empty");
					$this.find(".company-name").text("");
                    $this.find(".company-name-input").val("");
				}
			});
  		}else{
  			$(this).find("input.error,select.error,textarea.error").eq(0).focus();
  		}
  		return false;
  	});

	$("body").on("click", ".ajax", function(){
		$(this).parents("form").submit();
		return false;
	});
});