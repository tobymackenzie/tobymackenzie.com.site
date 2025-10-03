<?php
/*=====
Page template: Default page template when no other more specific page templates are defined.  Page templates are loaded directly by WordPress:  All other templates are loaded from actions in the page template.  TJMBase only has this one, but other common page templates include page.php for pages and single.php for single posts.
=====*/

//=====pre-content
get_header();

//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Template tmweb/index.php
-->
<?php
}

$title = wp_title(null, false);
//-# wp encodes various characters.  we must convert them back for the title element, which is itself encoded in twig.
SymfonyHelper::$viewData['doc']['title'] = html_entity_decode($title, ENT_QUOTES | ENT_XML1, 'UTF-8');
ob_start();
wp_head();
SymfonyHelper::$viewData['headExtra'] = ob_get_contents();
ob_end_clean();
ob_start();
wp_footer();
SymfonyHelper::$viewData['footExtra'] = ob_get_contents();
ob_end_clean();

//=====content
if(have_posts()){
	$postType = TMWebWPTheme::getPostType();
	if(!in_array($postType, ['page', 'single'])){
?>
	<header class="appMainHeader">
		<h1><?=get_the_title()?></h1>
<?php
		$paged = get_query_var('paged') ?: 1;
		if(is_archive() && get_the_archive_description() && $paged == 1){
?>
		<div><?=get_the_archive_description()?></div>
<?php
		}
?>
	</header>
<?php
	}
	while(have_posts()){
		the_post();
		get_template_part('content', get_post_format());
	}
	if($postType !== 'page'){
		echo TMWebWPTheme::$helper->renderer->renderPiece('relativeNav', ['id'=> 'nav-below', 'type'=> $postType]);
	}
}else{
	get_template_part('content', 'none');
}

//=====post-content
get_footer();
