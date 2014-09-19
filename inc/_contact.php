<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
    if( isset($_POST['email']) ):

      $validation = GUMP::is_valid($_POST, array(
          'email' => 'required|valid_email',
          'name' => 'valid_name',
          'message' => 'required|min_len,20'
      ));

      if($validation === true)
      {
        $html = '<html>
                  <head>
                    <title>Ny besked fra kontaktformular</title>
                  </head>
                  <body>
                    <h1>Ny besked fra hjemmesidens kontaktformular!</h1>
                    <p>Du har modtaget en ny besked fra '.$_POST['name'].': '.$_POST['email'].'</p>
                    <p>'.$_POST['message'].'</p>
                  </body>
                  </html>';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To: Minicms admin\'s <'.ADMIN_MAIL.'>, Minicms support <support@minicms.dk>' . "\r\n";
        $headers .= 'From: '.$_POST['name'].' <'.$_POST['email'].'>' . "\r\n";
        
        if( isset($_POST['own_copy']) )
        {
            $headers .= 'Cc:'. $_POST['email'] . "\r\n";
            //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        }

        // Mail it
        try {
          mail(ADMIN_MAIL, 'Ny besked fra kontaktformular', $html, $headers);
          set_message('contact','Mailen er afsendt med success. YES!!!!');
          redirect_to(BASE_URL.'?page=contact');
        } catch (Exception $e) {
          set_message('contact','Mailen kunne ikke sendes! ØV!');
        }
      } else {
        set_message('contact',$validation);
      }

    endif;
?>
<h1>Kontakt os</h1>
<?php echo show_message('contact') ?>
<form role="form" method="post">
  <div class="form-group">
    <label for="email">*Email address</label>
    <input type="email" class="form-control" required name="email" value="<?php echo (isset($_POST['email']))?$_POST['email']:'' ?>" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="name">Navn</label>
    <input type="text" class="form-control" name="name" value="<?php echo (isset($_POST['name']))?$_POST['name']:'' ?>" placeholder="Enter name">
  </div>
  <div class="form-group">
    <label for="message">*Besked</label>
    <textarea name="message" class="form-control" cols="30" rows="10"><?php echo (isset($_POST['message']))?$_POST['message']:'' ?></textarea>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" <?php echo (isset($_POST['own_copy']))?'checked':'' ?> name="own_copy"> Send kopi til min mail
    </label>
  </div>
  <button type="submit" class="btn btn-default">Send</button>
  <p>Vi prøver at bestræbe os på at svarer indenfor 24 timer & max. 3 arbejdsdage.</p>
</form>