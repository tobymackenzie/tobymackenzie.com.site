<?php
/*=====
Piece template: Renders the comments area.  Uses 'pieces/comment.php' to render individual comments.
=====*/

//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Template tmcom/comments.php
-->
<?php
}

//=====content
if(comments_open() || get_comments_number()){
?>
<aside class="postComments comments postAside" id="comments">
<?php
	//--if the post is requires authentication and the visitor hasn't provided it, display message instead of comments
	if(post_password_required()){
?>
	<p class="notice"><?php _e('This post requires authentication.  Please enter the password to view comments.', 'tmcom'); ?></p>
<?php
	}else{
		if(have_comments()){
?>
	<header class="commentsHeader">
		<h2 class="commentsHeading"><?php _e('Responses', 'tmcom'); ?></h2>
	</header>
<?php
			if(!empty($comments_by_type['comment'])){
?>
	<ol class="commentsList">
		<?php
		//--render each comment from 'pieces/comments.php'.  Create this file in a child theme to override
		wp_list_comments(array('style'=> 'ol', 'type'=> 'comment', 'callback'=> function($comment, $args, $depth){
			echo TMComWPTheme::$helper->renderer->renderPiece('comment', Array(
				'args'=> $args
				,'comment'=> $comment
				,'depth'=> $depth
			));
		}));
		?>
	</ol>
<?php
			}
			if(!empty($comments_by_type['pings'])){
?>
	<ol class="commentsList">
		<?php
		//--render each comment through WPThemeHelper->outputCommentPiece().  This function renders and outputs 'pieces/comment.php'.  Create this file in a child theme to override
		wp_list_comments(array('style'=> 'ol', 'type'=> 'pings', 'callback'=> function($comment, $args, $depth){
			echo TMComWPTheme::$helper->renderer->renderPiece('comment', Array(
				'args'=> $args
				,'comment'=> $comment
				,'depth'=> $depth
			));
		}));
		?>
	</ol>
<?php
			}

			//--output comments navigation if there are multiple comments pages
			if(get_comment_pages_count() > 1 && get_option('page_comments')){
				TMComWPTheme::$helper->renderer->renderPiece('relativeNav', Array(
					'classes'=> 'commentRelNav'
					,'id'=> 'comment-nav-below'
					,'nextLink'=> get_next_comments_link(__('Newer Comments', 'tmcom'))
					,'prevLink'=> get_previous_comments_link(__('Older Comments', 'tmcom'))
					,'title'=> __('Comment navigation', 'tmcom')
					,'type'=> 'comments'
				));
			}
		}
		if(comments_open()){
			comment_form(Array('title_reply'=> 'Leave a comment'));
		}
	}
?>
</aside>
<?php
}
