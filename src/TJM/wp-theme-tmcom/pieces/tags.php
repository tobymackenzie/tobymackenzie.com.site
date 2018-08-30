<?php
if(isset($tags) && $tags){
?>
<div class="postTags">
	<strong class="postTagsHeading"><?=__('Tags', 'tmcom')?></strong>
	<ul class="postTagsList">
<?php
	foreach($tags as $tag){
?>
		<li class="postTag"><a class="postTagAction p-category" href="<?php echo esc_url(get_tag_link($tag)); ?>" rel="tag"><?php echo $tag->name; ?></a></li>
<?php
	}
?>
	</ul>
</div>
<?php
}
