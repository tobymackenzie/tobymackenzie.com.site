<!DOCTYPE html>
<html lang="en">
	<title>Maintenance - &lt;toby&gt;</title>
	<header><a href="/">&lt;toby&gt; Site of Toby Mackenzie</a></header>
	<main>
		<h1>Maintenance</h1>
		<p>Apologies.  The site is undergoing maintenance at the moment.  Please
<?php if(isset($_GET['return'])){ ?>
			<a href="<?=htmlspecialchars($_GET['return'], ENT_QUOTES)?>">
<?php } ?>
				try back later.
<?php if(isset($_GET['return'])){ ?>
			</a>
<?php } ?>
		</p>
	</main>
</html>
