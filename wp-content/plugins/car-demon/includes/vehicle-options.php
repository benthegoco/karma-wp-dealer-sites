<?php
function cd_get_vehicle_map() {
	$map = array();
	$map = get_option( 'cd_vehicle_option_map' );
	if ( ! isset( $map['description'] ) ) {
		$map['description'] = get_default_description_maps();
	}
	if ( ! isset( $map['specs'] ) ) {
		$map['specs'] = get_default_specs_maps();
	}
	if ( ! isset( $map['safety'] ) ) {
		$map['safety'] = get_default_safety_maps();
	}
	if ( ! isset( $map['convenience'] ) ) {
		$map['convenience'] = get_default_convenience_maps();
	}
	if ( ! isset( $map['comfort'] ) ) {
		$map['comfort'] = get_default_comfort_maps();
	}
	if ( ! isset( $map['entertainment'] ) ) {
		$map['entertainment'] = get_default_entertainment_maps();
	}
	if ( ! isset( $map['about_us'] ) ) {
		$map['about_us'] = get_default_about_us_maps();
	}
	return $map;
}

function get_default_description_maps() {
	$map = array();
	return $map;
}

function get_default_specs_maps() {
	$map = array();
	$map[__( 'Specifications', 'car-demon' )] = __( 'Trim Level, Production Seq. Number, Exterior Color, Interior Color, Manufactured in, Engine Type, Transmission, Driveline, Tank (gallon),Fuel Economy (City miles/gallon),Fuel Economy (Highway miles/gallon),Anti-Brake System,Steering Type,Length (in.),Width (in.),Height (in.),Wheels,Brakes','car-demon' );
	return $map;
}

function get_default_safety_maps() {
	$map = array();
	$map[__( 'Equipment - Anti-Theft & Locks','car-demon' )] = __( 'Child Safety Door Locks,Locking Pickup Truck Tailgate,Power Door Locks,Vehicle Anti-Theft', 'car-demon' );
	$map[__( 'Equipment - Braking & Traction','car-demon' )] = __( '4WD/AWD,ABS(2-Wheel/4-Wheel),Automatic Load-Leveling,Electronic Brake Assistance,Limited Slip Differential,Locking Differential,Traction Control,Vehicle Stability Control System', 'car-demon' );
	$map[__( 'Equipment - Safety','car-demon' )] = __( 'Driver Airbag,Front Side Airbag,Front Side Airbag with Head Protection,Passenger Airbag,Side Head Curtain Airbag,Second Row Side Airbag,Second Row Side Airbag with Head Protection,Electronic Parking Aid,First Aid Kit,Trunk Anti-Trap Device', 'car-demon' );
	return $map;
}

function get_default_convenience_maps() {
	$map = array();
	$map[__( 'Equipment - Remote Controls & Release','car-demon' )] = __( 'Keyless Entry,Remote Ignition', 'car-demon' );
	$map[__( 'Equipment - Interior Features','car-demon' )] = __( 'Cruise Control,Tachometer,Tilt Steering Wheel,Tilt Steering Column,Heated Steering Wheel,Leather Steering Wheel,Steering Wheel Mounted Controls,Telescopic Steering Column,Adjustable Foot Pedals,Genuine Wood Trim,Tire Inflation/Pressure Monitor,Trip Computer', 'car-demon' );
	$map[__( 'Equipment - Storage','car-demon' )] = __( 'Cargo Area Cover,Cargo Area Tiedowns,Cargo Net,Load Bearing Exterior Rack,Pickup Truck Bed Liner', 'car-demon' );
	$map[__( 'Equipment - Roof','car-demon' )] = __( 'Power Sunroof/Moonroof,Manual Sunroof/Moonroof,Removable/Convertible Top', 'car-demon' );
	$map[__( 'Equipment - Climate Control','car-demon' )] = __( 'Air Conditioning,Separate Driver/Front Passenger Climate Controls', 'car-demon' );
	return $map;
}

function get_default_comfort_maps() {
	$map = array();
	$map[__( 'Equipment - Seat','car-demon' )] = __( 'Driver Multi-Adjustable Power Seat,Front Cooled Seat,Front Heated Seat,Front Power Lumbar Support,Front Power Memory Seat,Front Split Bench Seat,Leather Seat,Passenger Multi-Adjustable Power Seat,Second Row Folding Seat,Second Row Heated Seat,Second Row Multi-Adjustable Power Seat,Second Row Removable Seat,Third Row Removable Seat', 'car-demon' );
	$map[__( 'Equipment - Exterior Lighting','car-demon' )] = __( 'Automatic Headlights,Daytime Running Lights,Fog Lights,High Intensity Discharge Headlights,Pickup Truck Cargo Box Light', 'car-demon' );
	$map[__( 'Equipment - Exterior Features','car-demon' )] = __( 'Bodyside/Cab Step or Running Board,Front Air Dam,Rear Spoiler,Skid Plate or Underbody Protection,Splash Guards,Wind Deflector or Buffer for Convertible,Power Sliding Side Van Door,Power Trunk Lid', 'car-demon' );
	$map[__( 'Equipment - Wheels','car-demon' )] = __( 'Alloy Wheels,Chrome Wheels,Steel Wheels', 'car-demon' );
	$map[__( 'Equipment - Tires','car-demon' )] = __( 'Full Size Spare Tire,Run Flat Tires', 'car-demon' );
	$map[__( 'Equipment - Windows','car-demon' )] = __( 'Power Windows,Glass Rear Window on Convertible,Sliding Rear Pickup Truck Window', 'car-demon' );
	$map[__( 'Equipment - Mirrors','car-demon' )] = __( 'Electrochromic Exterior Rearview Mirror,Heated Exterior Mirror,Electrochromic Interior Rearview Mirror,Power Adjustable Exterior Mirror', 'car-demon' );
	$map[__( 'Equipment - Wipers','car-demon' )] = __( 'Interval Wipers,Rain Sensing Wipers,Rear Wiper,Rear Window Defogger', 'car-demon' );
	$map[__( 'Equipment - Towings','car-demon' )] = __( 'Tow Hitch Receiver,Towing Preparation Package', 'car-demon' );
	return $map;
}

function get_default_entertainment_maps() {
	$map = array();
	$map[__( 'Equipment - Entertainment, Communication & Navigation','car-demon' )] = __( 'AM/FM Radio,Cassette Player,CD Player,CD Changer,DVD Player,Hands Free/Voice Activated Telephone,Navigation Aid,Second Row Sound Controls or Accessories,Subwoofer,Telematic Systems', 'car-demon' );
	return $map;
}

function get_default_about_us_maps() {
	$map = array();
	return $map;
}
?>
