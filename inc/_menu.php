<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<?php
  if( isset($_POST['log_ud']) )
  {
      unset($_SESSION['user']);
  }

  if( isset($_POST['user_mail']) and isset($_POST['user_pass']) )
  {
    $user = ORM::for_table('users')->where(array(
      'email'=>$_POST['user_mail'],
      'password'=>$_POST['user_pass'],
      ))->find_one();

    if($user)
    {
      $_SESSION['user']['id'] = $user->id;
      $_SESSION['user']['name'] = $user->name;
      $_SESSION['user']['rolle_id'] = $user->rolle_id;
      header('location:'.BASE_URL.'?page=dashboard');
    } else {
      set_message('login', 'Brugernavn og password mathcer ikke.');
    }
  }
?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo BASE_URL ?>?page=home">MiniCms</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo BASE_URL ?>?page=home">Home</a></li>
        <li><a href="<?php echo BASE_URL ?>?page=contact">Kontakt</a></li>
        <?php echo (check_min_role())?'<li><a href="'.BASE_URL.'?page=dashboard">Administration</a></li>':''?>
      </ul>

      <form class="navbar-form navbar-right" method="post" role="form">  
        <?php if(!isset($_SESSION['user'])): ?>
          <div class="form-group">
            <input type="text" name="user_mail" placeholder="Email" class="form-control">
          </div>
          <div class="form-group">
            <input type="password" name="user_pass" placeholder="Password" class="form-control">
          </div>
          <button type="submit" class="btn btn-success">Log ind</button> 
        <?php else: ?>
          <button type="submit" name="log_ud" class="btn btn-success">Log ud, <?php echo $_SESSION['user']['name'] ?></button>
        <?php endif; ?>
      </form>
    </div><!--/.navbar-collapse -->
  </div>
</div>