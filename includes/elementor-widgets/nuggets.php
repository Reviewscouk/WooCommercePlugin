<?php

class NuggetsWidget extends \Elementor\Widget_Base {

	public function get_name() 
	{
		return 'nuggets';
	}

	public function get_title() 
	{
		return esc_html__( 'Nuggets Widget', 'textdomain' );
	}

	public function get_icon() 
	{
		return 'eicon-review';
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
		return [ 'reviews.io', 'reviewsio', 'widget', 'widgets', 'nuggets' ];
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
						A mobile-friendly Review Nuggets Widget that displays excerpts from company and product reviews. It can be placed close to your call-to-action buttons.
					</p>
					<br>
				",
				'label_block' => true,
			]
		);

		$this->add_control(
			'widget_id',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Widget Id', 'textdomain' ),
				'placeholder' => esc_html__( 'Required', 'textdomain' ),
				'description' => "Customise the widget from https://dash.reviews.io/widgets/editor/nuggets, and copy its ID to this field.",
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
		$shortcode = '[nuggets_widget widget_id=' . $this->settings('widget_id') . ' sku=' . $this->settings('sku') . ']';

        // Render the shortcode
        echo do_shortcode($shortcode);
	}

	protected function content_template() {}
}

?>