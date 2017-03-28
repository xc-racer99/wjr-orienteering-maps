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


		// Enqueue the script in the footer
		wp_enqueue_script('wjr-map-script', plugins_url() . '/wjr-orienteering-maps/mapsMap.js', null, null, true);

		// always return
		return '<div id="mapid" style="height: 450px;"></div>';

	}
	add_shortcode('wjr-map', 'wjr_maps_shortcode');
}
add_action('init', 'shortcodes_init');


function wjr_enqueue_resources()
{
		// Leaflet base
		wp_enqueue_script('leaflet-base', '//unpkg.com/leaflet@1.0.3/dist/leaflet.js');
		wp_enqueue_style('leaflet-markercluster-css', '//unpkg.com/leaflet@1.0.3/dist/leaflet.css');

		// Leaflet markercluster
		wp_enqueue_script('leaflet-markercluster-script', plugins_url() . '/wjr-orienteering-maps/leaflet.markercluster.js');
		wp_enqueue_style('leaflet-markercluster-base-css', plugins_url() . '/wjr-orienteering-maps/MarkerCluster.Default.css');
		wp_enqueue_style('leaflet-markercluster-css', plugins_url() . '/wjr-orienteering-maps/MarkerCluster.css');

		wp_enqueue_style('leaflet-markercluster-css', plugins_url() . '/wjr-orienteering-maps/mapsPopup.css');
}
add_action('wp_enqueue_scripts', 'wjr_enqueue_resources');
