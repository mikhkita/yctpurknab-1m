$(document).ready(function(){	
    var isMobile = false,
        rotation = 0,
        prevHeight = 10000;

    function resize(){
       if( typeof( window.innerWidth ) == 'number' ) {
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }

        isMobile = myWidth < 540;

        if( Math.abs(myWidth/myHeight-rotation) > 0.5 || myHeight-prevHeight < 0 || !firstTog ){
            firstRender();
        }
        prevHeight = myHeight;
        rotation = myWidth/myHeight;
    }

    function firstRender(){
        $(".b-head").css({
            "height" : myHeight
        });
    }

    $(window).resize(resize);
    resize();

    // alert($(".b-head").height()+" "+myHeight);

    $.fn.placeholder = function() {
        if(typeof document.createElement("input").placeholder == 'undefined') {
            $('[placeholder]').focus(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function() {
                var input = $(this);
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur().parents('form').submit(function() {
                $(this).find('[placeholder]').each(function() {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            });
        }
    }
    $.fn.placeholder();

    $('.select-chosen').chosen({
        width: '100%',
        disable_search_threshold: 10000
    });

    $('select[name="debtor"]').on('change', function(){
        if($(this).val() == "physical"){
            $(".b-form-request .b-input-name").show()
                .children("input").prop({"required": true, "disabled": false}).removeClass("error");
            $(".b-form-request .b-input-INN").hide()
                .children("input").prop({"required": false, "disabled": true}).removeClass("error");
        }else{
            $(".b-form-request .b-input-name").hide()
                .children("input").prop({"required": false, "disabled": true}).removeClass("error");
            $(".b-form-request .b-input-INN").show()
                .children("input").prop({"required": true, "disabled": false}).removeClass("error").focus();
            // $("input[name=INN]").val("").change();    
            if($(this).val() == "legal"){
                INNMask.updateOptions({mask: "0000000000"});
            }else{
                INNMask.updateOptions({mask: "000000000000"});
            }
        }
    });

    $('input[name="payment"]').on('change', function(){
        if($(this).val() == "card"){
            $(".b-payment-card").show();
            $(".b-payment-account").hide();
        }else{
            $(".b-payment-card").hide();
            $(".b-payment-account").show();
        }
    });

    function isIE() {
        var rv = -1;
        if (navigator.appName == 'Microsoft Internet Explorer')
        {
            var ua = navigator.userAgent;
            var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        else if (navigator.appName == 'Netscape')
        {
            var ua = navigator.userAgent;
            var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        return rv == -1 ? false: true;
    }

    if( isIE ){
        $("body").on('mousedown click', ".b-input input, .b-input textarea", function(e) {
            $(this).parents(".b-input").addClass("focus");
        });
    }

    $("body").on("focusin", ".b-input input, .b-input textarea", function(){
        $(this).parents(".b-input").addClass("focus");
    });

    $("body").on("focusin", ".b-form-consultation input", function(){
        $(".b-form-consultation .warning").removeClass("show");
    });

    $("body").on("focusout", ".b-input input, .b-input textarea", function(){
        $(this).parents(".b-input").removeClass("focus");
        if( $(this).val() != "" && $(this).val() != "+7 (   )    -  -  " ){
            $(this).parents(".b-input").addClass("not-empty");
        }else{
            $(this).parents(".b-input").removeClass("not-empty");
        }
    });

    $('.show-more').on('click', function(){
        if($(this).find(".open").hasClass("show")){
            $(".b-2-text p").addClass("full");
            $(this).find(".close").addClass("show");
            $(this).find(".open").removeClass("show");
        }else{
            $(".b-2-text p").removeClass("full");
            $(this).find(".close").removeClass("show");
            $(this).find(".open").addClass("show");
        }
        return false;
    });

    // $(".b-step-slider").slick({
    //     dots: true,
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     infinite: true,
    //     cssEase: 'ease', 
    //     speed: 500,
    //     arrows: true,
    //     prevArrow: '<button type="button" class="slick-prev slick-arrow icon-arrow-left"></button>',
    //     nextArrow: '<button type="button" class="slick-next slick-arrow icon-arrow-right"></button>',
    //     touchThreshold: 100
    // });

    // // Первая анимация элементов в слайде
    // $(".b-step-slide[data-slick-index='0'] .slider-anim").addClass("show");

    // // Кастомные переключатели (тумблеры)
    // $(".b-step-slider").on('beforeChange', function(event, slick, currentSlide, nextSlide){
    //     $(".b-step-tabs li.active").removeClass("active");
    //     $(".b-step-tabs li").eq(nextSlide).addClass("active");
    // });

    // // Анимация элементов в слайде
    // $(".b-step-slider").on('afterChange', function(event, slick, currentSlide, nextSlide){
    //     $(".b-step-slide .slider-anim").removeClass("show");
    //     $(".b-step-slide[data-slick-index='"+currentSlide+"'] .slider-anim").addClass("show");
    // });


    
	// var myPlace = new google.maps.LatLng(55.754407, 37.625151);
 //    var myOptions = {
 //        zoom: 16,
 //        center: myPlace,
 //        mapTypeId: google.maps.MapTypeId.ROADMAP,
 //        disableDefaultUI: true,
 //        scrollwheel: false,
 //        zoomControl: true
 //    }
 //    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); 

 //    var marker = new google.maps.Marker({
	//     position: myPlace,
	//     map: map,
	//     title: "Ярмарка вакансий и стажировок"
	// });

});