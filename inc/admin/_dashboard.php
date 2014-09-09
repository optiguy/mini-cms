<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
	redirect_if_user();
?>

<div class="row">
	<div class="col-xs-12">
		<h1>Velkommen til administrationen</h1>	
	</div>
	<div class="col-sm-6 col-md-4">
		<a href="<?php echo BASE_URL ?>?page=admin_user">Bruger administration</a>
	</div>
</div>