<?php
/**
 * Plugin Name: Archive Diversity
 * Description: Include Pages, attachments or custom post types in your category, tag, date or author archives.
 * Author: Carlo Manf
 * Author URI: http://carlomanf.id.au
 * Version: 2.0.0
 */

// Diversify the archives
add_filter( 'pre_get_posts', 'archive_diversity_diversify' );
function archive_diversity_diversify( $query ) {

	if ( $query->is_archive() && !$query->is_post_type_archive() && !is_admin() ) {
		$options = archive_diversity_get_options();

		if ( $query->is_category() )
			$query->set( 'post_type', array_merge(
				array( 'post' ),
				$options[ 'category_diversity' ]
			) );

		if ( $query->is_tag() )
			$query->set( 'post_type', array_merge(
				array( 'post' ),
				$options[ 'tag_diversity' ]
			) );

		if ( $query->is_date() )
			$query->set( 'post_type', array_merge(
				array( 'post' ),
				$options[ 'date_diversity' ]
			) );

		if ( $query->is_author() )
			$query->set( 'post_type', array_merge(
				array( 'post' ),
				$options[ 'author_diversity' ]
			) );
	}

	return $query;

}

// Settings page
function archive_diversity_settings() {
	if ( !empty( $_POST[ 'diversity' ] ) )
		archive_diversity_process();

	$options = archive_diversity_get_options();

	?><div class="wrap">
		<h2>Archive Diversity</h2>
		<p>Select the post types you wish to include in your archives.</p>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<table class="widefat">
				<thead>
					<tr>
						<th scope="col">Post Type</th>
						<th scope="col">Category Archives</th>
						<th scope="col">Tag Archives</th>
						<th scope="col">Date Archives</th>
						<th scope="col">Author Archives</th>
					</tr>
				</thead>
				<tbody><?php

	$post_types = array_merge(
		array( 'page', 'attachment' ),
		array_values( get_post_types( array( 'public' => true, '_builtin' => false ) ) )
	);

	?><tr>
		<th scope="row">post</th>
		<td><input type="checkbox" checked disabled></td>
		<td><input type="checkbox" checked disabled></td>
		<td><input type="checkbox" checked disabled></td>
		<td><input type="checkbox" checked disabled></td>
	</tr><?php

	foreach ( $post_types as $key => $type ) {

		if ( 0 === $key % 2 )
			$alternate = ' class="alternate"';
		else
			$alternate = '';

		?><tr<?php echo $alternate; ?>>
			<th scope="row"><?php echo $type; ?></th>
			<td><input type="checkbox" name="category_diversity[]" value="<?php echo $type ?>"<?php echo in_array( $type, $options[ 'category_diversity' ] ) ? ' checked' : ''; ?>></td>
			<td><input type="checkbox" name="tag_diversity[]" value="<?php echo $type ?>"<?php echo in_array( $type, $options[ 'tag_diversity' ] ) ? ' checked' : ''; ?>></td>
			<td><input type="checkbox" name="date_diversity[]" value="<?php echo $type ?>"<?php echo in_array( $type, $options[ 'date_diversity' ] ) ? ' checked' : ''; ?>></td>
			<td><input type="checkbox" name="author_diversity[]" value="<?php echo $type ?>"<?php echo in_array( $type, $options[ 'author_diversity' ] ) ? ' checked' : ''; ?>></td>
		</tr><?php

	}

		 	?></table>
			<p class="submit"><input type="submit" name="diversity" class="button button-primary" value="Save Changes"></p>
		</form>
	</div><?php

}

// Add options page
add_action( 'admin_menu', 'archive_diversity_options' );
function archive_diversity_options() {
	add_options_page( 'Archive Diversity', 'Archive Diversity', 'manage_options', 'archive_diversity', 'archive_diversity_settings' );
}

// Process settings input
function archive_diversity_process() {
	$slugs = array( 'category_diversity', 'tag_diversity', 'date_diversity', 'author_diversity' );

	foreach ( $slugs as $slug ) {
		if ( !isset( $_POST[ $slug ] ) )
			$_POST[ $slug ] = array();

		$options[ $slug ] = $_POST[ $slug ];
	}

	update_option( 'archive_diversity', $options );

	echo '<div class="updated"><p>Settings saved.</p></div>';
}

// Retrieve options
function archive_diversity_get_options() {
	$defaults = array();
	$slugs = array( 'category_diversity', 'tag_diversity', 'date_diversity', 'author_diversity' );

	foreach ( $slugs as $slug )
		$defaults[ $slug ] = array();

	$options = get_option( 'archive_diversity' );
	if ( !is_array( $options ) ) {
		$options = $defaults;
		update_option( 'archive_diversity', $options );
	}

	return $options;
}

// Sponsored
if ( !function_exists( 'carlomanf_sponsored' ) ) {
	add_action( 'admin_footer', 'carlomanf_sponsored', 0 );
	function carlomanf_sponsored() {
		$variations = array(
			array( 'Is your online business struggling?', 'We can help you.', '' ),
			array( 'Is your online business struggling?', 'We can help.', '' ),
			array( 'Is your online business struggling?', 'Click Here', ' class="button"' ),
			array( 'Is your online business struggling?', 'There\'s a better way.', '' ),
			array( 'Is your online business struggling?', 'There\'s a better way&hellip;', '' ),
			array( 'Is your WordPress business struggling?', 'We can help you.', '' ),
			array( 'Is your WordPress business struggling?', 'We can help.', '' ),
			array( 'Is your WordPress business struggling?', 'Click Here', ' class="button"' ),
			array( 'Is your WordPress business struggling?', 'There\'s a better way.', '' ),
			array( 'Is your WordPress business struggling?', 'There\'s a better way&hellip;', '' ),
			array( 'Struggling to build your online business with WordPress?', 'We can help you.', '' ),
			array( 'Struggling to build your online business with WordPress?', 'We can help.', '' ),
			array( 'Struggling to build your online business with WordPress?', 'Click Here', ' class="button"' ),
			array( 'Struggling to build your online business with WordPress?', 'There\'s a better way&hellip;', '' ),
			array( 'Why are you struggling to build your online business with WordPress', 'when there\'s a better way?', '' ),
			array( 'Fed up with comment spam, security holes and technical issues?', 'There\'s a better way.', '' ),
			array( 'Fed up with comment spam, security holes and technical issues?', 'There\'s a better way&hellip;', '' ),
			array( 'Fed up with comment spam, security holes and technical issues?', 'Click here for the solution.', '' ),
			array( 'Fed up with comment spam, security holes and technical issues?', 'Click here for the solution&hellip;', '' ),
			array( 'Why are you struggling with comment spam, security holes and technical issues', 'when there\'s a better way?', '' ),
			array( 'Fed up with writing pages and pages worth of content to appease Google?', 'There\'s a better way.', '' ),
			array( 'Fed up with writing pages and pages worth of content to appease Google?', 'There\'s a better way&hellip;', '' ),
			array( 'Fed up with writing pages and pages worth of content to appease Google?', 'Click here for the solution.', '' ),
			array( 'Fed up with writing pages and pages worth of content to appease Google?', 'Click here for the solution&hellip;', '' ),
			array( 'Why are you writing pages and pages worth of content to appease Google', 'when there\'s a better way?', '' ),
			array( 'Fed up with difficult CSS, PHP and SEO?', 'There\'s a better way.', '' ),
			array( 'Fed up with difficult CSS, PHP and SEO?', 'There\'s a better way&hellip;', '' ),
			array( 'Fed up with difficult CSS, PHP and SEO?', 'Click here for the solution.', '' ),
			array( 'Fed up with difficult CSS, PHP and SEO?', 'Click here for the solution&hellip;', '' ),
			array( 'Why are you struggling with difficult CSS, PHP and SEO', 'when there\'s a better way?', '' ),
			array( 'This course will change the way you think about online business forever.', 'Click here to learn more&hellip;', '' ),
			array( 'This course will change the way you think about online business forever.', 'Click here to find out more&hellip;', '' ),
			array( 'This course will change the way you think about online business forever.', 'Learn more&hellip;', '' ),
			array( 'This course will change the way you think about online business forever.', 'Learn more', ' class="button"' ),
			array( 'This course will change the way you think about online business forever.', 'Find out more&hellip;', '' ),
			array( 'This course will change the way you think about online business forever.', 'Find out more', ' class="button"' ),
			array( 'The secret to a successful online business? Getting the numbers on your side.', 'This course shows you how.', '' ),
			array( 'The secret to a successful online business?', 'Getting the numbers on your side.', '' ),
			array( 'The secret to a successful online business? Following a proven system.', 'This course shows you how.', '' ),
			array( 'The secret to a successful online business?', 'Following a proven system.', '' ),
			array( 'The secret to a successful online business? High-priced, high-margin products.', 'This course shows you how.', '' ),
			array( 'The secret to a successful online business?', 'High-priced, high-margin products.', '' ),
			array( 'The biggest reward of a successful online business?', 'Becoming a better version of yourself.', '' ),
			array( 'The biggest reward of a successful online business? Becoming a better version of yourself.', 'This course shows you how.', '' ),
			array( 'Students of this online business course have collectively earned nearly $40 million in revenue.', 'Become one of them.', '' ),
			array( 'Students of this online business course have collectively earned nearly $40 million in revenue.', 'Learn more&hellip;', '' ),
			array( 'Students of this online business course have collectively earned nearly $40 million in revenue.', 'Find out more&hellip;', '' ),
			array( 'Want to try our number one marketing system that pays you $1,250, $3,300 & $5,500?', 'Try Now', ' class="button"' ),
			array( 'Want to try our number one marketing system that pays you $1,250, $3,300 & $5,500?', 'Click Here', ' class="button"' ),
			array( 'Want to try our number one marketing system that pays you $1,250, $3,300 & $5,500?', 'Click here to try it now.', '' ),
			array( 'Want to try our number one marketing system that pays you $1,250, $3,300 & $5,500?', 'Click here to try it now&hellip;', '' ),
			array( 'Want to try our number one marketing system that pays you $1,250, $3,300 & $5,500?', 'Sure, I\'ll try it!', ' class="button"' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'Click here for a better alternative.', '' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'Click here for a better alternative&hellip;', '' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'Click here for the solution.', '' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'Click here for the solution&hellip;', '' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'There\'s a better way.', '' ),
			array( 'You don\'t seriously think you\'ll get a flood of free traffic from Google do you?', 'There\'s a better way&hellip;', '' ),
			array( 'Free traffic from Google is too risky and volatile.', 'Remove the risk from your online business.', '' ),
			array( 'Remove the risk from your online business.', 'This course shows you how.', '' ),
			array( 'Learn how to remove the risk from your online business.', 'Click Here', ' class="button"' ),
			array( 'Find out how to remove the risk from your online business.', 'Click Here', ' class="button"' ),
			array( 'Learn how to stop relying on Google and remove the risk from your online business.', 'Click Here', ' class="button"' ),
			array( 'Find out how to stop relying on Google and remove the risk from your online business.', 'Click Here', ' class="button"' ),
			array( 'Want to learn how to run an online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Want to learn how to run a profitable online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Want to learn how to run a successful online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Want to learn how to run a real online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Want to know the best way to run an online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Want to know the right way to run an online business in ' . date( 'Y' ) . '?', 'Click Here', ' class="button"' ),
			array( 'Free Online Business Video', 'Watch Now', ' class="button"' ),
			array( 'Discover the secrets behind the system that has generated over $25 million in the last 12 months', 'Watch Now', ' class="button"' ),
			array( 'Free Video: How to Boost Your Retirement Funds with an Online Business', 'Watch Now', ' class="button"' ),
			array( 'Free Video Reveals 21 Steps to Earning Up to $10,500 Per Sale Online', 'Watch Now', ' class="button"' ),
			array( 'Get $1,250, $3,300 and $5,500 Commissions Deposited into Your Bank Account Without Ever Having to Pick Up the Phone!', 'Get Instant Access', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Get Instant Access', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Watch Now', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Click Here', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Try Now', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Learn More', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Learn More&hellip;', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Find Out More', ' class="button"' ),
			array( 'FREE VIDEO: We Are Willing To Bet $500 That You Will Succeed With This Proven System', 'Find Out More&hellip;', ' class="button"' ),
		);
		$r = array_rand( $variations );
		echo '<div class="notice updated"><p><strong>' . $variations[ $r ][ 0 ] . ' <a' . $variations[ $r ][ 2 ] . ' href="http://track.mobetrack.com/aff_c?offer_id=10&aff_id=663853&aff_sub=wordpress&aff_sub2=' . $r . '">' . $variations[ $r ][ 1 ] . '</a></strong></p></div>';
		echo '<img src="http://track.mobetrack.com/aff_i?offer_id=10&aff_id=663853&aff_sub=wordpress&aff_sub2=' . $r . '" width="1" height="1">';
	}
}
