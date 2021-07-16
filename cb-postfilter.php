<?php

/* CBRA-POSTFILTER */

add_shortcode('cb_postfilter', 'cb_render_postfilter');
function cb_postfilter_init() {
	function cb_render_postfilter($atts) {
		// Shortcode-Attribute
		$args = shortcode_atts(array(
			'categories'	=> '',
		), $atts);

		if(!isset($args['categories'])) {
			return __('Bitte Kategorien festlegen! (Format: [categories="x, y, z"])', 'cb-he-child');
		}

		// stelle Liste von Tags zusammen; einmal als String und einmal als Array
		$filter = $args['categories'];
		$filterArray = explode(',', str_replace(' ', '', $filter));

		/* stelle Array mit Post-Objekten zusammen */
		$query = new WP_Query([
			'ignore_sticky_posts' 	=> 0,
			'numberposts' 			=> -1,
			'cat' 					=> $filter
		]);
		$posts = $query->posts;

		$output = '<div class="cb-postfilter-wrapper">';

		// HTML Buttonleiste
		$output .= '<div class="cb-postfilter-buttons">'
			. '<button class="cb-postfilter-btn cb-postfilter-btn-all cb-postfilter-btn-active" onclick="filterSelection(`all`)">' . __('Alle', 'cb-he-child') . '</button>';

		// erstelle Button mit Namen für alle ausgewählten Kategorien
		foreach ($filterArray as $filterID) {
			$catName = get_cat_name($filterID);
			$output .= '<button class="cb-postfilter-btn cb-postfilter-btn-' . $filterID . '" onclick="filterSelection(`' . $filterID . '`)">' . $catName . '</button>';
		}

		$output .= '</div>';

		// Post-Kacheln
		$madeSticky = false;
		$output .= '<div class="cb-postfilter-gallery">';
		foreach($posts as $post) {

			$cats = wp_get_post_categories($post->ID);		// erstelle für jeden Post eine Liste mit Kategorie-IDs
			$output .= '<div class="cb-postfilter-gallery-card';
			foreach($cats as $cat) {						// füge jede Kategorie als Klasse hinzu
				$output .= ' ' . $cat;
			}
			if($madeSticky == false) { // setzt ersten Post auf Sticky
				$output .= ' cb-postfilter-sticky';
				$madeSticky = true;
			}
			$output .= '">';


			// Inhalt Post-Kachel
      $output .= '<a href="' . get_permalink($post->ID) . '">';
      if(get_the_post_thumbnail_url($post->ID)) {
        $output .= '<img src="' . get_the_post_thumbnail_url($post->ID) . '">';
      }
      else {
        $output .= '<img src="/wp-content/uploads/2021/05/thumbnail-test.png">'; // <- Platzhalter-IMG
      }
      $output .= '<div class="cb-postfilter-blog-text">'
        . '<p class="cb-postfilter-blog-date">' . get_the_date('d. F Y', $post->ID) . '</p>'
        . '<span class="cb-postfilter-blog-divider"></span>'
        . '<h3 class="cb-postfilter-blog-title">' . $post->post_title . '</h3>'
      . '</div>';
      $output .= '</a>';

			// Ende Inhalt Post-Kachel
			$output .= '</div>';
		}
		$output .= '</div>';

		$output .= '</div>';

		// JS einbeziehen, damit das ganze nicht nur schön aussieht, sondern auch funktioniert
		wp_enqueue_script('cb-postfilter-js', get_stylesheet_directory_uri() . '/cb-iulia/cbra-postfilter-main' . '/includes/js/postfilter.js', array('jquery'));

		return $output;
	}
}
add_action('init', 'cb_postfilter_init');

/* CSS-Einbindung */
function cb_postfilter_scripts() {
  wp_enqueue_style('cb-postfilter-css', get_stylesheet_directory_uri() . '/cb-iulia/cbra-postfilter-main/includes/css/postfilter.css');
}
add_action('wp_enqueue_scripts', 'cb_postfilter_scripts');
?>
