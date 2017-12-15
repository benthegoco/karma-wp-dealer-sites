

        jQuery(document).ready(function(){
            jQuery('#header-gallery').slick({
              dots: true,
              infinite: true,
              speed: 500,
              fade: true,
              cssEase: 'linear'
            });


            jQuery( "#revero-tab-1" ).click(function() {openTab(1);});
            jQuery( "#revero-tab-2" ).click(function() {openTab(2);});
            jQuery( "#revero-tab-3" ).click(function() {openTab(3);});


            function openTab (whichOne) {
              if (whichOne == 1) { 
                jQuery( "#tab-1-content" ).css( "display", "block" );
                jQuery( "#tab-2-content" ).css( "display", "none" );
                jQuery( "#tab-3-content" ).css( "display", "none" );

                jQuery( "#revero-tab-1" ).css("border-top", "#e82c33 solid 3px");
               
                jQuery( "#revero-tab-2" ).css("border-top", "unset");
                jQuery( "#revero-tab-3" ).css("border-top", "unset");

                jQuery( "#revero-tab-1" ).css("border-right", "1px solid grey");
                jQuery( "#revero-tab-2" ).css("border-right", "unset");
                jQuery( "#revero-tab-3" ).css("border-right", "unset");

                jQuery( "#revero-tab-1" ).css("height", "41px");
                jQuery( "#revero-tab-2" ).css("height", "40px");
                jQuery( "#revero-tab-3" ).css("height", "40px");
               
              }
              else if (whichOne == 2) { 
                jQuery( "#tab-1-content" ).css( "display", "none" );
                jQuery( "#tab-2-content" ).css( "display", "block" );
                jQuery( "#tab-3-content" ).css( "display", "none" );

                jQuery( "#revero-tab-1" ).css("border-top", "unset");
                jQuery( "#revero-tab-2" ).css("border-top", "#e82c33 solid 3px");
                jQuery( "#revero-tab-3" ).css("border-top", "unset");

                jQuery( "#revero-tab-1" ).css("border-right", "1px solid grey");
                jQuery( "#revero-tab-2" ).css("border-right", "1px solid grey");
                jQuery( "#revero-tab-3" ).css("border-right", "unset");

                jQuery( "#revero-tab-1" ).css("height", "40px");
                jQuery( "#revero-tab-2" ).css("height", "41px");
                jQuery( "#revero-tab-3" ).css("height", "40px");
              }

              else if (whichOne == 3) { 
                jQuery( "#tab-1-content" ).css( "display", "none" );
                jQuery( "#tab-2-content" ).css( "display", "none" );
                jQuery( "#tab-3-content" ).css( "display", "block" );

                jQuery( "#revero-tab-1" ).css("border-top", "unset");
                jQuery( "#revero-tab-2" ).css("border-top", "unset");
                jQuery( "#revero-tab-3" ).css("border-top", "#e82c33 solid 3px");
               

                jQuery( "#revero-tab-1" ).css("border-right", "unset");
                jQuery( "#revero-tab-2" ).css("border-right", "1px solid grey");
                jQuery( "#revero-tab-3" ).css("border-right", "unset");

                jQuery( "#revero-tab-1" ).css("height", "40px");
                jQuery( "#revero-tab-2" ).css("height", "40px");
                jQuery( "#revero-tab-3" ).css("height", "41px");

              }
            }


            
          });