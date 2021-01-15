<?php
namespace BRS\WPGoogleReviewSlider;



// No Direct Access
if( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}



add_action('rest_api_init', __NAMESPACE__ . '\\get_google_reviews');
/**
 * Add `get_google_reviews` endpoint to the REST API.
 *
 * Endpoint : wp-json/brs/v1/get_google_reviews
 *
 * Example: `curl https://example.com/wp-json/brs/v1/get_google_reviews`
 *
 *
 * @since 0.1.0
 */
function get_google_reviews()
{
    \register_rest_route(
        'brs/v1',
        '/get_google_reviews',
        [
            'methods' => 'GET',
            'callback' => function ($request) {
                $rlength = 10;
                $tablelimit = 10;
                $sorttable = 'created_time_stamp';
                $sortdir = 'DESC';

                global $wpdb;
                $table_name = $wpdb->prefix . 'wpfb_reviews';

                $totalreviews = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM ".$table_name."
                    WHERE id>%d AND review_length >= %d 
                    ORDER BY ".$sorttable." ".$sortdir." 
                    LIMIT ".$tablelimit." ", "0","$rlength")
                );
                
               return $totalreviews;
            },
        ]
    );
}


/*
Table
$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pageid varchar(50) DEFAULT '' NOT NULL,
				pagename tinytext NOT NULL,
				created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				created_time_stamp int(12) NOT NULL,
				reviewer_name tinytext NOT NULL,
				reviewer_id varchar(50) DEFAULT '' NOT NULL,
				rating int(2) NOT NULL,
				review_text text NOT NULL,
				hide varchar(3) DEFAULT '' NOT NULL,
				review_length int(5) NOT NULL,
				type varchar(12) DEFAULT '' NOT NULL,
				userpic varchar(250) DEFAULT '' NOT NULL,
				from_url varchar(250) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
            ) $charset_collate;";
            

            */