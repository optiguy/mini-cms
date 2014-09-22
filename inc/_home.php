<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>

<?php
  if( isset($_POST['n_mail']) )
  {
      //Valider
      GUMP::set_field_name("n_mail", "e-mail");
      GUMP::set_field_name("n_name", "navn");
      $validation = GUMP::is_valid($_POST, array(
          'n_mail' => 'required|valid_email',
          'n_name' => 'valid_name'
      ));

      //Gem i database
      if($validation === true) {
        
        if( isset($_POST['newsletter_add']) )
        {
          try{
            //Tilføj person til nyhedsbrev
            $news_user = ORM::for_table('newsletter')->create();
            $news_user->email = $_POST['n_mail'];
            $news_user->name = $_POST['n_name'];
            $news_user->save();
            
            //Send mail til bruger
            mail($_POST['n_mail'], 'Tilmeldning til nyhedsbrev', 'Tusind tak dit dejlige menneske \n\n
              Du er nu tilmeldt vores nyhedsbrev. Mvh Mini-cms');
            
            //Sæt besked til 
            set_message('newsletter', 'Du er nu tilmeldt dig vores nyhedsbrev');
          } catch (Exception $e) {
            //F.eks $e->getMessage()
            set_message('newsletter', 'Emailen findes allerede eller der skete en fejl');
          }
        }
        elseif( isset($_POST['newsletter_no']) )
        {
          //Afmeld person fra nyhedsbrev
          $news_user = ORM::for_table('newsletter')->where('email',$_POST['n_mail'])->find_one();
          if($news_user){
            $news_user->set_expr('date_deleted','NOW()');
            $news_user->email = RandomString(10);
            $news_user->save();
            set_message('newsletter', 'Ej for helvede, '.$news_user->name.'. Du har nu afmeldt dig vores nyhedsbrev. Det er vi kede af.');
          } else {
            set_message('newsletter','Email adressen findes ikke');
          }  
        }        

      } else {
          //Sæt fejl besked til 
          set_message('newsletter',$validation);
      }
  }
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h1>Vores eget mini CMS</h1>
    <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
    <p><a class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
  </div>
</div>

<!-- row of columns -->
<div class="row">
  <div class="col-md-4">
    <h2>Nyhedsbrev</h2>
    <?php echo show_message('newsletter'); ?>
    <form role="form" method="post">
      <div class="form-group">
        <label for="InputEmail1">Email addresse</label>
        <input type="email" class="form-control" name="n_mail" value="<?php echo(isset($_POST['n_mail']))?$_POST['n_mail']:'' ?>" placeholder="Enter email">
      </div>
      <div class="form-group">
        <label for="InputPassword1">Dit navn</label>
        <input type="text" class="form-control" name="n_name" value="<?php echo(isset($_POST['n_name']))?$_POST['n_name']:'' ?>" placeholder="Skriv dit navn">
      </div>
      <button type="submit" name="newsletter_add" class="btn btn-primary">Tilmeld</button>
      <button type="submit" name="newsletter_no" class="btn btn-danger">Afmeld</button>
    </form>
  </div>
  <div class="col-md-4">
    <h2>Heading</h2>
    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
 </div>
  <div class="col-md-4">
    <h2>Heading</h2>
    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
    <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
  </div>
</div>