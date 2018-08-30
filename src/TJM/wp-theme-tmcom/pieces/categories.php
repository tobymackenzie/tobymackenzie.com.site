<?php
if(!isset($categories)){
	$categories = get_the_category();
}
if(isset($categories) && $categories){
?>
<div class="postCats">
	<strong class="postCatsHeading"><?=__('Categories', 'tmcom')?></strong>
	<ul class="postCatsList">
<?php
	foreach($categories as $category){
?>
		<li class="postCat"><a class="postCatAction p-category" href="<?php echo esc_url(get_tag_link($category)); ?>" rel="tag"><?php echo $category->name; ?></a></li>
<?php
	}
?>
	</ul>
</div>
<?php
}
