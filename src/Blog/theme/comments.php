<?php
/*=====
Piece template: Renders the comments area.  Uses 'pieces/comment.php' to render individual comments.
=====*/

//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Template tmweb/comments.php
-->
<?php
}

//=====content
?>
<aside class="postComments comments postAside" id="comments">
<?php if(have_comments()){ ?>
	<header class="commentsHeader">
		<h2 class="commentsHeading"><?php _e('Responses', 'tmweb'); ?></h2>
	</header>
<?php
}
//--if the post is requires authentication and the visitor hasn't provided it, display message instead of comments
if(post_password_required()){
?>
	<p class="notice"><?php _e('This post requires authentication.  Please enter the password to view comments.', 'tmweb'); ?></p>
<?php
}else{
	if(have_comments()){
?>
<?php
		if(!empty($comments_by_type['comment'])){
?>
	<ol class="commentsList">
		<?php
		//--render each comment from 'pieces/comments.php'.  Create this file in a child theme to override
		wp_list_comments(['style'=> 'ol', 'type'=> 'comment', 'callback'=> function($comment, $args, $depth){
			echo TMWebWPTheme::$helper->renderer->renderPiece('comment', [
				'args'=> $args
				,'comment'=> $comment
				,'depth'=> $depth
			]);
		}]);
		?>
	</ol>
<?php
		}
		if(!empty($comments_by_type['pings'])){
?>
	<ol class="commentsList">
		<?php
		//--render each comment through WPThemeHelper->outputCommentPiece().  This function renders and outputs 'pieces/comment.php'.  Create this file in a child theme to override
		wp_list_comments(['style'=> 'ol', 'type'=> 'pings', 'callback'=> function($comment, $args, $depth){
			echo TMWebWPTheme::$helper->renderer->renderPiece('comment', [
				'args'=> $args
				,'comment'=> $comment
				,'depth'=> $depth
			]);
		}]);
		?>
	</ol>
<?php
		}

		//--output comments navigation if there are multiple comments pages
		if(get_comment_pages_count() > 1 && get_option('page_comments')){
			TMWebWPTheme::$helper->renderer->renderPiece('relativeNav', [
				'classes'=> 'commentRelNav'
				,'id'=> 'comment-nav-below'
				,'nextLink'=> get_next_comments_link(__('Newer Comments', 'tmweb'))
				,'prevLink'=> get_previous_comments_link(__('Older Comments', 'tmweb'))
				,'title'=> __('Comment navigation', 'tmweb')
				,'type'=> 'comments'
			]);
		}
?>
	<hr />
<?php
	}
	if(comments_open()){
		comment_form(['title_reply'=> 'Leave a comment']);
	}else{
?>
	<p>To reply to this post, <?php if(pings_open()){ ?> post on your blog and send a pingback, or <?php } ?><a href="mailto:admin@tobymackenzie.com">email me</a>.</p>
<?php
	}
}
?>
	<hr />
</aside>
