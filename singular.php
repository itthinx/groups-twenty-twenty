<?php
/**
 * The template for displaying single posts and pages, extended to show excerpts only for non-members.
 *
 * An example of showing content conditionally in a template, based on the visitor's
 * group membership with the Registered group. Note that all users belong to the
 * Registered group by default, you can of course use another existing group name.
 *
 * @since Groups Twenty Twenty 1.0.0
 */

get_header();

/**
 * @var string $show_content whether the visitor will be able to view the protected content
 */
$show_content = false;

// Make sure that Groups' Groups_Group exists to proceed with the appropriate checks.
if ( class_exists( 'Groups_Group' ) ) {

	// Obtain the ID of the current visitor, this will be 0 if it's a guest:
	$user_id = get_current_user_id();

	// Get the ID of the Registered group (or of the group you choose instead).
	// We obtain the ID by name so we don't have to remember the group's ID.
	$group = Groups_Group::read_by_name( 'Registered' );

	// If we have a group, we can check if the visitor is a member:
	if ( $group !== false ) {

		// This object allows to check for membership easily.
		$groups_user = new Groups_User( $user_id );

		// Assign the value according to whether the user is a member or not.
		$show_content = $groups_user->is_member( $group->group_id );
	}
}
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			if ( $show_content ) :
				the_post();
			else :
				?>
				<div class="member-nag">
					<p>You are viewing an excerpt. To gain access to the full article, you must be a member.</p>
				</div>
				<?php
				the_excerpt();
				global $wp_query;
				$wp_query->next_post();
			endif;

			get_template_part( 'template-parts/content', get_post_type() );
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
