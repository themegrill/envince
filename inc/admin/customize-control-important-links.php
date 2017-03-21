<?php
// Theme important links started
class Envince_Important_Links extends WP_Customize_Control {

	public $type = "envince-important-links";

	public function render_content() {
		//Add Theme instruction, Support Forum, Demo Link, Rating Link
		$important_links = array(
			'view-pro' => array(
				'link' => esc_url('https://themegrill.com/themes/envince/'),
				'text' => esc_html__('View Pro', 'envince'),
			),
			'theme-info' => array(
				'link' => esc_url('https://themegrill.com/themes/envince/'),
				'text' => esc_html__('Theme Info', 'envince'),
			),
			'support' => array(
				'link' => esc_url('https://themegrill.com/support-forum/'),
				'text' => esc_html__('Support', 'envince'),
			),
			'documentation' => array(
				'link' => esc_url('https://docs.themegrill.com/envince/'),
				'text' => esc_html__('Documentation', 'envince'),
			),
			'demo' => array(
				'link' => esc_url('https://demo.themegrill.com/envince/'),
				'text' => esc_html__('View Demo', 'envince'),
			),
			'rating' => array(
				'link' => esc_url('http://wordpress.org/support/view/theme-reviews/envince?filter=5'),
				'text' => esc_html__('Rate this theme', 'envince'),
			),
		);
		foreach ($important_links as $important_link) {
			echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr($important_link['text']) . ' </a></p>';
		}
	}

}
