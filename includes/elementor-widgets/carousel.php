<?php

class CarouselWidget extends \Elementor\Widget_Base {

	public function get_name() 
	{
		return 'carousel';
	}

	public function get_title() 
	{
		return esc_html__( 'Carousel Widget', 'textdomain' );
	}

	public function get_icon() 
	{
		return 'eicon-post-slider';
	}

	public function get_custom_help_url() 
	{
		return 'https://support.reviews.io/en/articles/3203716-woocommerce-plugin';
	}

	public function get_categories() 
	{
		return [ 'reviewsio' ];
	}

	public function get_keywords() 
	{
		return [ 'reviews.io', 'reviewsio', 'widget', 'widgets', 'carousel', ];
	}

	public function get_script_depends() 
	{
		return [ '' ];
	}

	public function get_style_depends() 
	{
		return [ '' ];
	}

	protected function register_controls() 
	{
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Widget Settings', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

		$this->add_control(
			'widget_description',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => "
					<p style='line-height: 16px;'>
						A minimal carousel widget with a header on the side. Displays reviews, photos, videos & feedback from 3rd party platforms in cards sliding horizontally.
					</p>
					<br>
				",
				'label_block' => true,
			]
		);

		$this->add_control(
			'sku',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'SKU', 'textdomain' ),
				'placeholder' => esc_html__( 'Optional', 'textdomain' ),
				'description' => "Add product specific SKU's seperated by a semi-colon (;) without gaps.",

			]
		);

		$this->end_controls_section();
	}

	protected function settings($option) 
	{
		$settings = $this->get_settings_for_display();
		return esc_attr( $settings[$option] );
	}

	protected function render() 
	{
		$shortcode = '[carousel_widget sku=' . $this->settings('sku') . ']';

        // Render the shortcode
        echo do_shortcode($shortcode);
	}

	protected function content_template() {}
}

?>