<?php
/*
Plugin Name: BC Orienteering Maps
Description: Basic plugin to create a leaflet map with orienteering maps from WJR's database - use [wjr-map {Comma-seperated list of WJR club IDs}] shortcode to activate it
Author: Jonathan Bakker
Version: 1.1
*/

function shortcodes_init()
{
	function wjr_maps_shortcode($atts = [], $content = null)
	{
		// Enqueue the script in the footer, with leaflet dependencies
		wp_enqueue_script('wjr-map-script', plugins_url('mapsMap.js', __FILE__), array('leaflet-base-js', 'leaflet-markercluster-script'), null, true);

		// Add the mapid
		$toreturn = '<div id="mapid" style="height: 450px;"></div>';

		$toreturn .= '<script>';
		// Add the clubIds to an array
		if ($atts[0]) {
 			$toreturn .= 'var clubIds = [';
 			$clubIds = explode(",", $atts[0]);
 			for($i = 0; $i < count($clubIds); $i++)
				$toreturn .= $clubIds[$i] . ',';
			$toreturn .= '];';
 		} else {
 			$toreturn .= 'var clubIds = [1, 42, 43, 46, 80];';
		}

		$toreturn .= '</script>';

		return $toreturn;

	}
	add_shortcode('wjr-map', 'wjr_maps_shortcode');
}
add_action('init', 'shortcodes_init');


function wjr_enqueue_resources()
{
		// Leaflet base
		wp_register_script('leaflet-base-js', '//unpkg.com/leaflet@1.0.3/dist/leaflet.js', null, true);
		wp_enqueue_style('leaflet-base-style', '//unpkg.com/leaflet@1.0.3/dist/leaflet.css');

		// Leaflet markercluster
		wp_register_script('leaflet-markercluster-script', plugins_url('leaflet.markercluster.js', __FILE__), null, true);
		wp_enqueue_style('leaflet-markercluster-base-css', plugins_url('MarkerCluster.Default.css', __FILE__));
		wp_enqueue_style('leaflet-markercluster-css', plugins_url('MarkerCluster.css', __FILE__));

		// Our custom popup CSS
		wp_enqueue_style('wjr-popup-css', plugins_url('mapsPopup.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wjr_enqueue_resources');
