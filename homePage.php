<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>   
    <a href="dashboard.php">Dashboard</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Page d'accueil</h1>
    <div id="infosPersoWidget">
    	<h2>Informations personnelles</h2>
      <div>Nom :</div>
      <div>Prénom :</div>
      <div>Age :</div>
      <div>Entreprise :</div> 
      <div>Genre :</div> 
      <div>Pays :</div> 
      <div>Métier :</div> 
    </div>
    <div id="groupeWidget">
      <h2>Groupes auxquels vous appartenez</h2>
      <div id="tabGroupes">Groupe 1 : Les auteurs engagés - Type Redacteur<br/>Groupe 2 : Bande de lecteurs - Type Lecteurs<br/></div>
    </div>
    <?php
        require_once ('connect.php');

        $result = pg_query($bddconn, "SELECT nom FROM utilisateur WHERE utilisateur.email='$emailUtilisateurExistant';");

      if (! $fetch =mysqli_fetch_row($result)) {
        echo "<div>Aucun enregistrement ne correspond\n</div>";
      }
      else {
        echo"<tr>$fetch[0] $fetch[1] $fetch[2] $fetch[3] $fetch[4] $fetch[5] </tr>";
      } 
      mysql_close();
    ?>
  </body>
</html>

