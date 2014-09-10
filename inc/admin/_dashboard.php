<?php if(!defined('BASE_URL')) die('No Script access is allowed'); ?>
<?php
	redirect_if_user(); //redirect if user is not admin - See inc/functions.php
?>
<div class="row">
	<div class="col-xs-12">
		<h1>Velkommen til administrationen</h1>	
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Bruger administration</a>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Media manager</a>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Menuer</a>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Kategorier</a>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Content</a>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="widget">
			<a href="<?php echo BASE_URL ?>?page=admin_user">Attributter</a>
		</div>
	</div>
</div>