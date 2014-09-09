<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
	redirect_if_user();

	if(isset($_GET['task']) and $_GET['task'] == 'del')
	{
		if(isset($_GET['user_id']))
		{
			$user = ORM::for_table('users')->find_one($_GET['user_id']);
			if(!$user or !$user->delete())
			{
				set_message('user', 'Kunne ikke slette brugeren');
				redirect_to(BASE_URL.'?page=admin_user');
			} else {
				set_message('user', 'Brugeren er blevet slettet permanent');
				redirect_to(BASE_URL.'?page=admin_user');
			}
		} else {
			set_message('user', 'Brugeren findes ikke');
			redirect_to(BASE_URL.'?page=admin_user');
		}
	} elseif( isset($_POST['save_user']) or isset($_POST['edit_user']) )
	{
		$user = (isset($_POST['save_user'])) ? ORM::for_table('users')->create() : ORM::for_table('users')->find_one($_GET['user_id']);
		$user->name     = $_POST['name'];
		$user->email    = $_POST['email'];
		$user->password = $_POST['password'];
		$user->adresse  = $_POST['adresse'];
		$user->avatar   = $_FILES['avatar']['name'];
		$user->rolle_id = $_POST['rolle'];
		if(!$user->save())
		{
			set_message('user','Kunne ikke gemme brugeren');
			redirect_to(BASE_URL.'?page=admin_user');
		}
	}

	$users = ORM::for_table('users')->select('users.*')->select('rolle.navn','rolename')->inner_join('rolle','rolle.id = users.rolle_id')->find_many();

?>
<?php if(isset($_GET['task']) and ($_GET['task'] == 'new' or $_GET['task'] == 'edit')): ?>
<?php if($_GET['task'] == 'edit')
		$user = ORM::for_table('users')->find_one($_GET['user_id']); ?>
	<form method="post" class="form form-horizontal" enctype="multipart/form-data">
		<div class="form-group">
			<label for="name">Navn</label>
			<input type="text" class="controle-group" value="<?php echo (isset($user->name))?$user->name:''?>" name="name" placeholder="Indsæt brugerens navn...">
		</div>
		<div class="form-group">
			<label for="email">email</label>
			<input type="email" class="controle-group" value="<?php echo (isset($user->email))?$user->email:''?>" name="email" placeholder="Indsæt brugerens navn...">
		</div>
		<div class="form-group">
			<label for="password">password</label>
			<input type="password" class="controle-group" name="password" placeholder="Indsæt brugerens navn...">
		</div>
		<div class="form-group">
			<label for="rolle">rolle</label>
			<select class="controle-group" name="rolle">
				<?php
					$roller = ORM::for_table('rolle')->find_many();
					foreach($roller as $rolle){
						if( isset($user->rolle_id) )
							$selected = ($rolle->id == $user->rolle_id)? 'selected' : '';
						echo '<option '.$selected.' value="'.$rolle->id.'">'.$rolle->navn.'</option>';
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<?php echo (isset($user->avatar))?'<img src="'.$user->avatar.'" alt="" >':''?>
			<label for="avatar">Avatar</label>
			<input type="file" name="avatar">
		</div>
		<div class="form-group">
			<label for="adresse">adresse</label>
			<textarea class="controle-group" name="adresse" id="" cols="30" rows="10"><?php echo (isset($user->adresse))?$user->adresse:''?></textarea>
		</div>
		<?php if( $_GET['task'] == 'new' ): ?>
			<input type="submit" name="save_user" class="btn btn-primary" value="Gem bruger">
		<?php else: ?>
			<input type="submit" name="edit_user" class="btn btn-primary" value="Gem bruger">
		<?php endif; ?>
	</form>
<?php endif; ?>

<table class="table table-bordered">
	<tr>
		<th>Navn</th>
		<th>Mail</th>
		<th>Rolle</th>
		<th colspan="2">
			<a href="<?php echo BASE_URL ?>?page=admin_user&task=new">Opret</a>
		</th>
	</tr>
	<?php foreach($users as $user): ?>
		<tr>
			<td><?php echo $user->name ?></td>
			<td><?php echo $user->email ?></td>
			<td><?php echo $user->rolename ?></td>
			<td><a href="<?php echo BASE_URL ?>?page=admin_user&task=edit&user_id=<?php echo $user->id ?>">Ret</a></td>
			<td><a href="<?php echo BASE_URL ?>?page=admin_user&task=del&user_id=<?php echo $user->id ?>">Slet</a></td>
		</tr>
	<?php endforeach; ?>
</table>