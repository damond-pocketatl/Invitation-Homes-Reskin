<?php

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) {
	?> <p><?php _e('This post is password protected. Enter the password to view comments.', 'InvitationHomes'); ?></p> <?php
	return;
}
	
function theme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	
	<div class="commentlist-item">
		<div <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<div class="avatar-holder"><?php echo get_avatar( $comment, 48 ); ?></div>
			<div class="commentlist-holder">
				<?php edit_comment_link( __( '(Edit)', 'InvitationHomes'), '<p class="edit-link">', '</p>' ); ?>
				<p class="meta"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php comment_date('F d, Y'); ?> <?php _e('at', 'InvitationHomes'); ?> <?php comment_time('g:i a'); ?></a>, <?php comment_author_link(); ?> <?php _e('said:', 'InvitationHomes'); ?></p>
				<?php if ($comment->comment_approved == '0') : ?>
				<p><?php _e('Your comment is awaiting moderation.', 'InvitationHomes'); ?></p>
				<?php else: ?>
				<?php comment_text(); ?>
				<?php endif; ?>
				
				<?php
					comment_reply_link(array_merge( $args, array(
						'reply_text' => __('Reply', 'InvitationHomes'),
						'before' => '<p>',
						'after' => '</p>',
						'depth' => $depth,
						'max_depth' => $args['max_depth']
				))); ?>
			</div>
		</div>
	<?php }
	
	function theme_comment_end() { ?>
		</div>
	<?php }
?>

<?php if ( have_comments() ) : ?>

<div class="section comments" id="comments">

	<h2><?php comments_number(__('No Responses', 'InvitationHomes'), __('One Response', 'InvitationHomes'), __('% Responses', 'InvitationHomes') );?> <?php _e('to', 'InvitationHomes'); ?> &#8220;<?php the_title(); ?>&#8221;</h2>

	<div class="commentlist">
		<?php wp_list_comments(array(
			'callback' => 'theme_comment',
			'end-callback' => 'theme_comment_end',
			'style' => 'div'
		)); ?>
	</div>

	<div class="navigation">
		<div class="next"><?php previous_comments_link(__('&laquo; Older Comments', 'InvitationHomes')) ?></div>
		<div class="prev"><?php next_comments_link(__('Newer Comments &raquo;', 'InvitationHomes')) ?></div>
	</div>

</div>

<?php endif; ?>
 

<?php if ( comments_open() ) : ?>
<div class="comments-area">
    <div class="holder">
<?php /*?><form class="comment-form" method="post" action="#">
    <div class="form-columns">
        <div class="column">
            <div class="column-main">
                <p class="comment-form-comment">
                    <label for="comment">Comment <span class="note">Start typing...</span></label>
                    <textarea rows="8" cols="45" id="comment"></textarea>
                </p>
            </div>
        </div>
        <div class="column-r">
            <p>
                <label for="email">Email Address</label>
                <input id="email" type="email">
            </p>
            <p class="comment-form-author">
                <label for="author">Name <span class="note">(as you would like it to appear)</span></label>
                <input id="author" type="text">
            </p>
            <p class="form-submit">
                <input value="Post comment" type="submit">
            </p>
        </div>
    </div>
</form><?php */?>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php"  class="comment-form" method="post" id="commentform">
            <fieldset>
                	<p class="comment-notes">Your email address will not be published. Required fields are marked <span class="required">*</span></p>
                    <h2><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h2>
            
                    <div class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></div>
                    
                    <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
                        <p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
                    <?php else : ?>
                	<div class="column">
                        <div class="column-main">
                            <p class="comment-form-comment">
                                <label for="comment">Comment <span class="note">Start typing...</span></label>
                                <textarea name="comment" id="comment" class="form-control" cols="45" rows="8"></textarea>
                            </p>
                        </div>
                    </div>
                    <div class="column-r">
						<?php if ( is_user_logged_in() ) : ?>
                            <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
                        <?php else : ?>
                            <p>
                                <label for="email">Email Address</label>
                               <input type="text" name="email" id="email" class="form-control" aria-required="true" size="30" value="<?php echo esc_attr($comment_author_email); ?>" />
                            </p>
                            <p class="comment-form-author">
                                <label for="author">Name <span class="note">(as you would like it to appear)</span></label>
                               <input type="text" name="author" id="author" aria-required="true" class="form-control" size="30" value="<?php echo esc_attr($comment_author); ?>" />
                            </p>
                        <?php endif; ?>
                        <p class="form-submit">
                            <input name="submit" type="submit" id="submit" class="btn btn-default" value="Submit Comment" />
                        </p>
                     </div>
                        <?php
                            comment_id_fields();
                            do_action('comment_form', $post->ID);
                        ?>
                    </p>
                <?php endif; ?>
            </fieldset>
        </form>
        <?php //comment_form(); ?>
    </div>
</div>
<?php else : ?>

	<?php if (is_singular(array('post'))) : ?>
	<p><?php _e('Comments are closed.', 'InvitationHomes'); ?></p>
	<?php endif; ?>
	
<?php endif; ?>