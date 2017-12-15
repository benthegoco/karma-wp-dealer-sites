<?php

function karma_single_car_info_content() {
	return <<< END

		<div class="single-car-option-box">
			<div class="single-car-description-label">FEATURES</div>
			<div class="single-car-description-label-line"></div>
			<div id="car_features_box" class="car_features_box">
				<div class="car_features">
					<ul class="tabs">
						<li class="tab_inactive tab_active"> <a href="javascript:car_demon_switch_tabs(1, 6, 'tab_', 'content_');" id="tab_1" class="active">Description</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(2, 6, 'tab_', 'content_');" id="tab_2">Specs</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(3, 6, 'tab_', 'content_');" id="tab_3">Safety</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(4, 6, 'tab_', 'content_');" id="tab_4">Convenience</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(5, 6, 'tab_', 'content_');" id="tab_5">Comfort</a></li>
						<li class="tab_inactive last-item"><a href="javascript:car_demon_switch_tabs(6, 6, 'tab_', 'content_');" id="tab_6">Entertainment</a></li>
					</ul>
					<div id="content_1" class="car_features_content"><p>This is a sample vehicle for the FREE <a href="https://cardemons.com" target="_blank">Car Demon PlugIn</a>.</p></div>
				</div>
			</div>
		</div>	

		<div class="single-car-option-box">
			<div class="single-car-description-label">OPTIONS</div>
			<div class="single-car-description-label-line"></div>
			<div id="car_features_box" class="car_features_box">
				<div class="car_features">
					<ul class="tabs">
						<li class="tab_inactive tab_active"> <a href="javascript:car_demon_switch_tabs(1, 6, 'tab_', 'content_');" id="tab_1" class="active">Description</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(2, 6, 'tab_', 'content_');" id="tab_2">Specs</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(3, 6, 'tab_', 'content_');" id="tab_3">Safety</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(4, 6, 'tab_', 'content_');" id="tab_4">Convenience</a></li>
						<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(5, 6, 'tab_', 'content_');" id="tab_5">Comfort</a></li>
						<li class="tab_inactive last-item"><a href="javascript:car_demon_switch_tabs(6, 6, 'tab_', 'content_');" id="tab_6">Entertainment</a></li>
					</ul>
					<div id="content_1" class="car_features_content"><p>This is a sample vehicle for the FREE <a href="https://cardemons.com" target="_blank">Car Demon PlugIn</a>.</p></div>
				</div>
			</div>
		</div>	

END;
}

?>

