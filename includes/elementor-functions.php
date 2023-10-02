<?php

class ElementorFunctions 
{
    /**
     * Register Elementor categories to group all widgets
     */
    public static function add_elementor_widget_categories( $elements_manager ) 
    {
        $elements_manager->add_category(
            'reviewsio',
            [
                'title' => esc_html__( 'REVIEWS.io Widgets', 'textdomain' ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    /**
     * Register widget blocks to Elementor editor
     */
    public static function register_widgets( $widgets_manager ) 
    {
        require_once( __DIR__ . '/elementor-widgets/product-reviews.php' );
        require_once( __DIR__ . '/elementor-widgets/rating-snippet.php' );
        require_once( __DIR__ . '/elementor-widgets/nuggets.php' );
        require_once( __DIR__ . '/elementor-widgets/ugc.php' );
        require_once( __DIR__ . '/elementor-widgets/rating-bar.php' );
        require_once( __DIR__ . '/elementor-widgets/carousel.php' );

        $widgets_manager->register( new \ProductReviewsWidget() );
        $widgets_manager->register( new \RatingSnippet() );
        $widgets_manager->register( new \NuggetsWidget() );
        $widgets_manager->register( new \UgcWidget() );
        $widgets_manager->register( new \RatingBarWidget() );
        $widgets_manager->register( new \CarouselWidget() );
    }

    /**
     * Unregister widget blocks from Elementor editor
     */
    public static function unregister_widgets( $widgets_manager ) 
    {
        $widgets_manager->unregister( 'ProductReviewsWidget' );
        $widgets_manager->unregister( 'RatingSnippet' );
        $widgets_manager->unregister( 'NuggetsWidget' );
        $widgets_manager->unregister( 'UgcWidget' );
        $widgets_manager->unregister( 'RatingBarWidget' );
        $widgets_manager->unregister( 'CarouselWidget' );
    }
}