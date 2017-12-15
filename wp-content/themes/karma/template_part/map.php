<div class="locationHolder">
    <div class="pageCenter">
        <h2>Location</h2>
        <br />
    </div>
    <div class="map" name="map"></div>
    <div class="getDirection">
        <div class="pageCenter">
            <a href="<?php echo of_get_option('map_direction_0'); ?>" class="btnDirection" target="_blank">Get Directions</a>
        </div>
    </div>
</div>
<?php for ($mapIndex=0; $mapIndex<3; ++$mapIndex) { ?>
    <div class="mapContentH" name="mapContentH" style="display: none;"><?php echo of_get_option('map_con_' . $mapIndex); ?></div>
    <div class="mapPhoneH" name="mapPhoneH" style="display: none;"><?php echo of_get_option('map_phone_' . $mapIndex); ?></div>
<?php } ?>
<script type="text/javascript">
    address = {};
    contentString = {};
    mapZoom = <?php echo (of_get_option('map_zoom_0'))?of_get_option('map_zoom_0'):14; ?>;
    address[0] = '<?php echo of_get_option('map_address_0'); ?>';
    address[1] = '<?php echo of_get_option('map_address_1'); ?>';
    address[2] = '<?php echo of_get_option('map_address_2'); ?>';

    var mapContent, mapPhone;

    <?php for ($mapIndex=0; $mapIndex<3; ++$mapIndex) { ?>
    mapContent = document.getElementsByName('mapContentH')[<?php print($mapIndex); ?>].innerHTML;
    mapPhone = document.getElementsByName('mapPhoneH')[<?php print($mapIndex); ?>].innerHTML;;

    contentString[<?php print($mapIndex); ?>] = '<div id="iw-container">' +
        '<div class="iw-title"><h2><?php echo of_get_option('map_title_' . $mapIndex); ?></h2></div>' +
        '<div id="bodyContent" class="iw-content">' +

        '<p><strong><?php echo of_get_option('map_sub_title_' . $mapIndex); ?></strong><br /> '+mapContent+'</p>' +
        '<p class="phone">'+mapPhone+'</p>' +
        '</div>' +
        '</div>';
    <?php } ?>

</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/map.js" type="text/javascript"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAM7DC1jal7qV6t34zpyO8ItrofQn0_I8w&callback=initMaps" async defer></script>

