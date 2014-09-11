<?php
	$page_num = ( isset($_GET['page_num']) ) ? $_GET['page_num']-1 : 0 ;
	$offset = PER_PAGE*$page_num;
	$total_articles = count(ORM::for_table('content')->find_result_set());
	$total_pages = ceil($total_articles/PER_PAGE);

	if( $total_pages <= $page_num )
	{
		set_message('page','Der er flere sider!');
		redirect_to(BASE_URL.'?page=articles');
	}

	$articles = ORM::for_table('content')->limit(PER_PAGE)->offset($offset)->order_by_desc('date_created')->find_many();

	if( isset($_GET['article']) and isset($_GET['rating']) )
	{
		$article_to_rate = ORM::for_table('content')->find_one($_GET['article']);
		$article_to_rate->votes = $article_to_rate->votes+1;
		$article_to_rate->rating = $article_to_rate->rating+$_GET['rating'];
		$article_to_rate->save();
		set_message('rating','Rating er gemt på artiklen:'.$article_to_rate->title);
		redirect_to(BASE_URL.'?page=articles');
	}
?>
<div class="row">
	<div class="col-xs-12">
		<h1>Artikler</h1>

		<?php
			for ($i=1; $i <= $total_pages; $i++) { 
				echo '<a href="'.BASE_URL.'?page=articles&page_num='.$i.'">Side '.$i.'</a>';
			}
		?>

		<?php foreach($articles as $article): ?>
			<h2><?php echo $article->title ?></h2>
			<p><?php echo $article->content ?></p>
			<p><?php echo ($article->votes != 0)? number_format($article->rating/$article->votes,1,',','.') : 0; ?>/10<br>
			Antal stemmer: <?php echo $article->votes ?></p>
			Stem her :
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=1"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=2"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=3"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=4"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=5"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=6"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=7"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=8"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=9"><span class="glyphicon glyphicon-heart"></span></a>
			<a href="<?php echo BASE_URL ?>?page=articles&article=<?php echo $article->id ?>&rating=10"><span class="glyphicon glyphicon-heart"></span></a>
			<hr>
		<?php endforeach; ?>
	</div>
</div>