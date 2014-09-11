<?php if(!defined('BASE_URL')) die('No Script access is allowed'); ?>
<?php
	redirect_if_user(); //Redirect if user is not administrator - See inc/functions.php
	
	//Klargør
	$user = (isset($_POST['edit_user']) and isset($_GET['user_id'])) ? ORM::for_table('users')->find_one($_GET['user_id']) : ORM::for_table('users')->create();

	if(isset($_GET['task']) and $_GET['task'] == 'del')
	{
		if(isset($_GET['user_id']))
		{
			$user = ORM::for_table('users')->find_one($_GET['user_id']);
			$avatar = $user->avatar;
			if(!$user or !$user->delete())
			{
				if($avatar)unlink(BASE_URL.'uploads/avatars/'.$avatar);	
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
		//Todo : Add validation as a function
		$rules = array(
		    'name' => 'required|valid_name',
		    'email' => 'required|valid_email',
		    'password' => 'max_len,100|min_len,6',
		    'address' => 'alpha_numeric'
		);
		if( isset($_POST['save_user']) )
			$rules['password'] = 'required|max_len,100|min_len,6';

		$validator = new GUMP();
		$_POST = $validator->sanitize($_POST); //Sanitize all values
		$validator->validation_rules($rules);

		if(!$validator->run($_POST)):
		     foreach($validator->get_readable_errors() as $error)
	     		set_message('user',$error);
		else:
			//Todo : Add upload as a function
			$mime_type = array('jpg','jpeg','gif','png','bmp'); //Tilladte filtyper
			$max_size = 1024*1024*5; //Max tilladte fil størrelse
			$image = $_FILES['avatar']; //Gem billedet i en variabel
			if( !empty($image['name']) ) //Hvis der er et billede der skal uploades
			{
				if( $image['error']==0 ) //Hvis billedet ikke indeholder fejl
				{
					$mime = pathinfo($image['name'], PATHINFO_EXTENSION); //Hent fil endelse
					if( !in_array($mime, $mime_type) ) //Tjek på om filendelse er gyldig
					{
						set_message('avatar','Filen er ikke et billede'); //Sæt fejlbesked - See inc/functions.php
						redirect_to(BASE_URL.'?page=admin_user');//Redirect og vis besked
					}
					if( $image['size'] > $max_size )
					{
						set_message('avatar','Filen er for stor');//Sæt fejlbesked - See inc/functions.php
						redirect_to(BASE_URL.'?page=admin_user');//Redirect og vis besked
					}
					if( !empty($user->avatar) ) //Hvis brugeren allerede har et billede
						unlink('uploads/avatars/'.$user->avatar); //Slet billedet

					$filename = time().'_'.$image['name']; //Lav nyt filnavn til billedet
					WideImage::load($image['tmp_name'])->resize(150,150,'outside')->crop('center','center',150,150)->saveToFile('uploads/avatars/'.$filename);
					$user->avatar = $filename; //Gem i database

				} else {
					set_message('avatar','Billedet indeholder fejl!');//Sæt fejlbesked - See inc/functions.php
					redirect_to(BASE_URL.'?page=admin_user');//Redirect og vis besked
				}
			}

			if( isset($_POST['password']) ) $user->password = better_crypt($_POST['password']);
			$user->name     = $_POST['name'];
			$user->email    = $_POST['email'];
			$user->adresse  = $_POST['adresse'];
			$user->rolle_id = $_POST['rolle'];
			
			if(!$user->save())
			{
				set_message('user','Kunne ikke gemme brugeren');
				redirect_to(BASE_URL.'?page=admin_user');
			} else {
				set_message('user','Brugeren er oprettet');
				redirect_to(BASE_URL.'?page=admin_user');
			}
		endif;
	}
	$users = ORM::for_table('users')->select('users.*')->select('rolle.navn','rolename')->inner_join('rolle','rolle.id = users.rolle_id')->find_many();
?>
<?php echo show_message('user'); ?>
<?php if(isset($_GET['task']) and ($_GET['task'] == 'new' or $_GET['task'] == 'edit')): ?>
<?php if($_GET['task'] == 'edit')
		$user = ORM::for_table('users')->find_one($_GET['user_id']); ?>
	<form method="post" class="form form-horizontal" enctype="multipart/form-data">
		<div class="form-group">
			<label for="name">Navn</label>
			<input type="text" class="controle-group" value="<?php echo (isset($_POST['name']))?$_POST['name']:$user->name?>" name="name" placeholder="Indsæt brugerens navn...">
		</div>
		<div class="form-group">
			<label for="email">email</label>
			<input type="email" class="controle-group" value="<?php echo (isset($_POST['email']))?$_POST['email']:$user->email?>" name="email" placeholder="Indsæt brugerens navn...">
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
					//TODO - Make function that outputs a selectbox
					foreach($roller as $rolle){
						if( isset($user->rolle_id) )
							$selected = ($rolle->id == $user->rolle_id)? 'selected' : '';
						echo '<option '.$selected.' value="'.$rolle->id.'">'.$rolle->navn.'</option>';
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<?php echo (isset($user->avatar))?'<img src="uploads/avatars/'.$user->avatar.'" alt="" >':''?>
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