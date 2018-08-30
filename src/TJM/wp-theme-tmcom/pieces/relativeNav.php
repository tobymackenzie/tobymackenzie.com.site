<?php
/*=====
Piece template: Renders relative navigation

Data:
	classes (String): classes to add to wrapper
	id (String): html id attribute of wrapper
	nextLink (String): link for next action
	prevLink (String): link for previous action
	title (String): text to use for heading
	type (String): what this nav is for.  if set, default values will be provided
=====*/

if(!isset($classes)){
	$classes = '';
}else{
	$classes .= ' ';
}
$classes .= "nav relNav";

if(isset($type) && $type){
	switch($type){
		case 'page':
		case 'single':
			$classes .= ' relNav-item appMainFooter';
			$title = 'posts';
			if(!isset($nextLink)){
				$nextLink = get_next_post_link('%link', __('Next post:', 'tjmbase') . ' %title');
			}
			if(!isset($prevLink)){
				$prevLink = get_previous_post_link('%link', __('Previous post:', 'tjmbase') . ' %title');
			}
		break;
		case 'comments':
			$title = 'comments';
		break;
		default:
			if($wp_query->max_num_pages < 2){
				return;
			}
			$classes .= ' relNav-list appMainFooter';
			$title = 'posts';
			if(!isset($nextLink)){
				$nextLink = get_next_posts_link(__('Older posts', 'tjmbase'));
			}

			if(!isset($prevLink)){
				$prevLink = get_previous_posts_link(__('Newer posts', 'tjmbase'));
			}
		break;
	}
}

//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Piece: TJMBase:relativeNav
-->
<?php
}

//=====content
//-! old theme had index list, like '1 2 … 28 ->'
?>
<nav
	<?php if(isset($id)){ ?> id="<?php echo $id; ?>"<?php } ?>
	class="<?=$classes?>"
	aria-label="<?=$title?>"
	role="navigation"
>
<?php
if(isset($prevLink) && $prevLink){
?>
	<div class="prevNav relNavItem"><?=$prevLink?></div>
<?php
}
if(isset($nextLink) && $nextLink){
?>
	<div class="nextNav relNavItem"><?=$nextLink?></div>
<?php
}
?>
</nav>
