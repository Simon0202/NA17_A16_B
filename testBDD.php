<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>
  <?php 
    require_once('connect.php');

    $query = "INSERT into...";
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    

    pg_close($link);
  ?>
  </body>
</html>

