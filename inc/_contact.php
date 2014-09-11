<?php if(!defined('BASE_URL')) die('No Script acces is allowed'); ?>
<h1>Kontakt os</h1>

<form role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">*Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Navn</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter name">
  </div>
  <div class="form-group">
    <label for="exampleInputFile">*Besked</label>
    <textarea name="exampleInputFile" class="form-control" id="exampleInputFile1" cols="30" rows="10"></textarea>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox"> Send kopi til min mail
    </label>
  </div>
  <button type="submit" class="btn btn-default">Send</button>
  <p>Vi prøver at bestræbe os på at svarer indenfor 24 timer & max. 3 arbejdsdage.</p>
</form>