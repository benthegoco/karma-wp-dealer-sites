var customJS;

jQuery(document).ready(function($) {

	customJS = {

		common: {
			commonJS: function() {

				$('.homeSlider').flexslider({
					animation: "slide",
					slideshow: false,
					useCSS: false,
					smoothHeight: true,
					slideshow: true,
					pauseOnHover: true
				});

				$('.bannerHolder').flexslider({
					animation: "slide",
					slideshow: false,
					useCSS: false,
					smoothHeight: true,
					slideshow: true,
                    animationLoop: false,
					pauseOnHover: true
				});




				$('.banner, .articleList figure, .imgHolder').each(function() {
					var bannerUrl = $(this).find('.hidden').attr('src');
					$(this).css('background-image', 'url(' + bannerUrl + ')');
				});


				function articleHeight1() {
					var maxHeight1 = -1;
					$('.articleHolder.first .articleList').each(function() {
						if ($(this).height() > maxHeight1) {
							maxHeight1 = $(this).height();
						}
					});
					$('.articleHolder.first .articleList').height(maxHeight1);
				}

				function articleHeight2() {
					var maxHeight = -1;
					$('.articleHolder.second .articleList').each(function() {
						if ($(this).height() > maxHeight) {
							maxHeight = $(this).height();
						}
					});
					$('.articleHolder.second .articleList').height(maxHeight);

				}

				function specAlign(){
					var rightSHeight = $('.specHolder .rightSide').outerHeight();
					$('.leftSide').height(rightSHeight);
				}
				function bannerHeightAdjust(){
					$('.bannerHolder .flex-viewport .slides').each(function () {
						var bannerHeight = 0;
                        $(this).find('li').each(function () {
                            var captionHeight = 0;
                            $(this).find('.pageCenter .contentHolder').children().each(function () {
                                captionHeight += $(this).height();
                                //console.log("height:"+captionHeight);
                            });
                            if (bannerHeight < captionHeight){
                            	bannerHeight = captionHeight;
							}
                        });
                        console.log("height:"+bannerHeight);
                        $(this).parent().height(275+bannerHeight);
                    });
				}
				function bannerSwich(){
					
					if ($(window).width() >= 640) {
						$('.swichBanner').each(function(){
							var banner = $(this).attr('desk');
							$(this).css('background-image','url('+banner+')');
						});
						
					}else{
						$('.swichBanner').each(function(){
							var banner = $(this).attr('mobile');
							$(this).css('background-image','url('+banner+')');
						});
					}
				}
				bannerSwich();


				$(window).on('load', function() {
					if ($(window).width() >= 640) {
						articleHeight1();
						articleHeight2();
						specAlign();
					} else {
                        bannerHeightAdjust();
					}
				});

				$(window).resize(function() {
					bannerSwich();
					if ($(window).width() >= 640) {
						articleHeight1();
						articleHeight2();
						specAlign();
					} else {
                        bannerHeightAdjust();
					}
				});
				specAlign();

				/*$(document).on('click', '.location a', function(event) {
					event.preventDefault();
					var target = $(this).attr('href');
					$('html, body').animate({
						scrollTop: $(target).offset().top
					}, 2000);
				});*/

					/*-----Tab-----*/
				$('.tabWrapper li:eq(0)').addClass('selected');
				$('.tabContent:eq(0)').show();
				$('body').on('click', '.tabWrapper li', function() {
					$('.tabWrapper  li').removeClass('selected');
					$(this).addClass('selected');
					var getUrl = $(this).attr('rel');
					$('#' + getUrl).height();
					$('.tabContent').hide();
					$('#' + getUrl).fadeIn();
				});
			},

			html5Tags: function() {
				document.createElement('header');
				document.createElement('section');
				document.createElement('nav');
				document.createElement('footer');
				document.createElement('menu');
				document.createElement('hgroup');
				document.createElement('article');
				document.createElement('aside');
				document.createElement('details');
				document.createElement('figure');
				document.createElement('time');
				document.createElement('mark');
			},

			commonInput: function() {

				var $inputText = $('.queryInput input, .queryInput textarea');
				$inputText.each(function() {
					var $thisHH = $(this);
					if (!$(this).val()) {
						$(this).parent().find('label').show();
					} else {
						setTimeout(function() {
							$thisHH.parent().find('label').hide();
						}, 100);
					}

				});
				$inputText.focus(function() {
					if (!$(this).val()) {
						$(this).parent().find('label').addClass('showLab');
					}
				});
				$inputText.keydown(function() {
					if (!$(this).val()) {
						$(this).parent().find('label').hide();
					}
				});
				$inputText.on("blur", function() {
					var $thisH = $(this);
					if (!$(this).val()) {
						$(this).parent().find('label').show().removeClass('showLab');
					} else {
						$thisH.parent().find('label').hide();
					}

				});

			},
			commonSelect: function() {
				$('.bgSelect input').attr('readonly', 'readonly');
				var $selectText = $('.bgSelect input');
				var $selectLi = $('.bgSelect li');
				var selectval;
				var Drop = 0;

				$('body').click(function() {
					if (Drop == 1) {
						$('.bgSelect ul').hide();
						Drop = 0;
					}
				});
				$selectText.click(function() {
					$('.bgSelect ul').hide();
					Drop = 0;
					if (Drop == 0) {
						$(this).parent().parent().find('ul').slideDown();
					}
					setTimeout(function() {
						Drop = 1;
					}, 50);
				});
				$selectLi.click(function() {
					Drop = 1;
					selectval = $(this).text();
					$(this).parent().parent().find('input').val(selectval);
				});
			}


		} //end commonJS

	};


	customJS.common.commonJS();
	customJS.common.html5Tags();
	customJS.common.commonInput();
	customJS.common.commonSelect();

});
