<?php
/*
Plugin Name: BC Orienteering Maps
Description: Basic plugin to create a leaflet map with all the orienteering maps in BC in WJR's database - use [wjr-map] shortcode to activate it
Author: Jonathan Bakker
Version: 1.0
*/

function shortcodes_init()
{
	function wjr_maps_shortcode($atts = [], $content = null)
	{
 		// do something to $content, if needed

		// Enqueue the script in the footer, with leaflet dependencies
		wp_enqueue_script('wjr-map-script', plugins_url('mapsMap.php', __FILE__), array('leaflet-base-js', 'leaflet-markercluster-script'), null, true);

		// always return
		return '<div id="mapid" style="height: 450px;"></div>';

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
