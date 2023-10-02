<?php

class RatingSnippet extends \Elementor\Widget_Base {

	public function get_name() 
    {
		return 'rating_snippet';
	}

	public function get_title() 
    {
		return esc_html__( 'Rating Snippet', 'textdomain' );
	}

	public function get_icon() 
    {
		return 'eicon-rating';
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
		return [ 'reviews.io', 'reviewsio', 'widget', 'widgets', 'rating', 'stars' ];
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
						An ideal way to display a product rating on your category pages.
					</p>
					<br>
					<p style='line-height: 16px;'>
						Quickly evaluate the overall customer sentiment by considering the average rating and review count. This helps customers gauge the satisfaction level of previous buyers for customers to utilize this snippet to make an informed purchasing decision.
					</p>
				",
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() 
    {
		$shortcode = '[rating_snippet]';

        // Render the shortcode
        echo do_shortcode($shortcode);
	}

	protected function content_template() {}
}

?>