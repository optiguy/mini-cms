<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
	$page_num = ( isset($_GET['page_num']) ) ? $_GET['page_num']-1 : 0 ; //Set page index - ex. First page 1-1= skip 0, 2-1= skip 1 page
	$offset = PER_PAGE*$page_num; //How many articles to skip. ex 5*1 = 5 (Page 2), 5*2=10 (Page 3), ...
	$total_articles = count(ORM::for_table('content')->find_result_set()); //Total number og articles in database
	$total_pages = ceil($total_articles/PER_PAGE); //Total number of page rounded up

	if( $total_pages <= $page_num and $total_pages > 0 ) //Check if page exist
		redirect_to(BASE_URL.'?page=articles'); //Redirect to first page

	//Get all articles limited to the page we're on
	$articles = ORM::for_table('content')->limit(PER_PAGE)->offset($offset)->order_by_desc('date_created')->find_many();

	//Rating
	if( isset($_GET['article']) and isset($_GET['rating']) )
	{
		$article_to_rate = ORM::for_table('content')->find_one($_GET['article']);
		$article_to_rate->votes = $article_to_rate->votes+1; //Update - Set new vote
		$article_to_rate->rating = $article_to_rate->rating+$_GET['rating']; //Update - Set new rating sum
		$article_to_rate->save(); //Save in database
		set_message('rating','Rating er gemt på artiklen:'.$article_to_rate->title); //Set message
		redirect_to(BASE_URL.'?page=articles'); //Redirect user to articles
	}

	//Subscribe
	if( isset($_GET['subscribe']) and ($_GET['subscribe']=='on' or $_GET['subscribe'] == 'off') )
	{
		$is_subscribed = ORM::for_table('subscribe_to_content')->where(array(
				'content_id'=> $_GET['content_id'],
				'users_id' => $_SESSION['user']['id']
				))->find_one();

		if($_GET['subscribe']=='on')
		{
			if( $is_subscribed )
			{
				set_message('article','Du er allerede tilmeldt.');
				redirect_to(BASE_URL.'?page=articles');
			} else {
				//Tilmeld bruger
				$tilmelding = ORM::for_table('subscribe_to_content')->create();
				$tilmelding->content_id = $_GET['content_id'];
				$tilmelding->users_id = $_SESSION['user']['id'];
				if($tilmelding->save())
				{
					set_message('article','Du er nu tilmeldt artiklen');
					redirect_to(BASE_URL.'?page=articles');
				}
			}
		} else {
			//afmeld bruger
			if($is_subscribed and $tilmelding->delete())
			{
				set_message('article','Du er nu afmeldt artiklen');
				redirect_to(BASE_URL.'?page=articles');
			}
		}
	}
?>
<div class="row">
	<div class="col-xs-12">
		<h1>Artikler</h1>
		
		<ul class="pagination centered">
			<?php for ($i=1; $i <= $total_pages; $i++){
				$active = ($i == ($page_num+1)) ? 'active' : '' ; //Set current page to active
				echo '<li class="'.$active.'"><a href="'.BASE_URL.'?page=articles&page_num='.$i.'">'.$i.'</a></li>';
			}?>
		</ul>

		<?php if($total_articles == 0): ?>
			<h2>Der er ingen artikler i øjeblikket.</h2>
		<?php else: ?>
			<?php foreach($articles as $article): ?>
				<?php $avg_rating = ($article->votes != 0)? number_format($article->rating/$article->votes,1,',','.') : 0; ?>
				<a href="<?php echo BASE_URL ?>?page=single&content_id=<?php echo $article->id ?>">
					<h2><?php echo $article->title ?></h2>
				</a>
				<p><?php echo $article->content ?></p>
				
				Antal stemmer: <?php echo $article->votes ?>
				Stem her : 
				<?php
				for ($i=0; $i < NUM_RATING; $i++) { 
					if($i < $avg_rating)
						echo '<a href="'.BASE_URL.'?page=articles&article='.$article->id.'&rating='.$i.'"><span class="glyphicon glyphicon-star"></span></a>';		
					else 
						echo '<a href="'.BASE_URL.'?page=articles&article='.$article->id.'&rating='.$i.'"><span class="glyphicon glyphicon-star-empty"></span></a>';
				}
				?>

				<?php echo $avg_rating ?>/<?php echo NUM_RATING ?>
				<?php if( isset($_SESSION['user']) ): ?>
					<?php
						$is_subscribed = ORM::for_table('subscribe_to_content')->where(array(
							'content_id'=> $article->id,
							'users_id' => $_SESSION['user']['id']
						))->find_one();
					?>					
					<?php if($is_subscribed){ ?>
						<a href="<?php echo BASE_URL ?>?page=articles&content_id=<?php echo $article->id ?>&subscribe=off">Afmeld artikel</a>
					<?php } else {?>
						<a href="<?php echo BASE_URL ?>?page=articles&content_id=<?php echo $article->id ?>&subscribe=on">Abboner på artikel</a>
					<?php } ?>
				<?php endif; ?>
				<br>
				<a href="<?php echo BASE_URL ?>?page=single&content_id=<?php echo $article->id ?>">Læs hele artiklen her</a>
				<hr>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>
</div>