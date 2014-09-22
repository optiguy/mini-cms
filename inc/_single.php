<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
	$article = ORM::for_table('content')->find_one($_GET['content_id']);
	if(!$article)
	{
		set_message('single','You messed up! Artiklen findes ikke!');
		redirect_to(BASE_URL.'?page=articles');
	}

	if( isset($_GET['comment_id']) and isset($_SESSION['user']) )
	{
		//Hvis de ikke er administratorer, skal der en where sætning på, så de kun kan slette deres eget indhold.
		if( check_min_role() )
			$comment = ORM::for_table('comments')->find_one($_GET['comment_id']);
		else
			$comment = ORM::for_table('comments')->where('users_id',$_SESSION['user']['id'])->find_one($_GET['comment_id']);
		
		if($comment->delete())
		{
			set_message('comment','Kommentaren blev slettet');
		} else {
			set_message('comment','Der skete en fejl! Kommentaren blev ikke slettet');
		}
		redirect_to(BASE_URL.'?page=single&content_id='.$_GET['content_id']);
	}

	if( isset($_POST['comment']) )
	{
		if( empty($_POST['comment']) )
			set_message('single','Udfyld venligst kommentarfeltet');
		else {
			$comment = ORM::for_table('comments')->create();
			$comment->comment = $_POST['comment'];
			$comment->content_id = $_GET['content_id'];
			$comment->users_id = $_SESSION['user']['id'];
			if($comment->save())
			{
				$subscriptions = ORM::for_table('subscribe_to_content')
				->select(array('users.name','users.email'))
				->inner_join('users','users_id = users.id')
				->where('content_id',$_GET['content_id'])
				->find_many();
				
				foreach($subscriptions as $subscription)
				{
					$message = "Hej ". $subscription->name ."/r/n
					Ny kommentar på artiklen: ".$article->title."/r/n
					Du kan læse det nye indhold her : ". BASE_URL."?page=single&content_id=".$article->id;
					mail($subscription->email, 'Nyt kommentar på: '.$article->title, $message, $headers);
				}
				set_message('comment','Din kommentar er blevet oprettet');	
			} else {
				set_message('comment','Der skete en alvorlig fejl!');
			}
		}
	}
?>
<div class="row">
	<div class="col-xs-12">
		<?php echo show_message('single') ?>

		<h1><?php echo $article->title ?></h1>
		<p><?php echo $article->content ?></p>
		<h2>Komemntarer</h2>
		<?php $comments = ORM::for_table('comments')->select(array('comments.id', 'comments.comment', 'comments.users_id', 'comments.date_created', 'users.name', 'users.avatar'))->inner_join('users','comments.users_id = users.id')->where('comments.content_id',$_GET['content_id'])->find_many() ?>
		<ul>
			<?php foreach($comments as $comment): ?>
				<li>
					<?php echo ($comment->avatar!="")? '<img width="50" src="uploads/avatars/'.$comment->avatar.'" alt="Billede af '.$comment->name.'" ><br>':'' ?>
					<strong><?php echo $comment->name ?></strong> - <?php echo date('d-M-Y H:i:s',strtotime($comment->date_created));?><br>
					<?php echo $comment->comment ?><br>
					<?php 
						if( isset($_SESSION['user']) and ($comment->users_id == $_SESSION['user']['id'] or $_SESSION['user']['rolle_id']<=2) )
							echo '<a href="'.BASE_URL.'?page=single&content_id='.$article->id.'&comment_id='.$comment->id.'" onclick="return confirm(\'er du sikker?\')")>Slet kommentar</a>';
					?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if( isset($_SESSION['user']) ): ?>
		<form action="" method="post" accept-charset="utf-8">
			<label for="comment">Kommentar</label><br>
			<textarea name="comment" class="form-control" id="" rows="5"><?php echo (isset($_POST['comment']))?$_POST['comment']:'' ?></textarea>
			<br>
			<input type="submit" class="btn btn-primary" value="Gem kommentar">
		</form>
		<?php endif; ?>
	</div>
</div>