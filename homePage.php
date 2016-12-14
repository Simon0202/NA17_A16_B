<?php
  session_start();
  $mailSession = $_SESSION["emailUtilisateurCourant"];
?>

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
    <div>Bienvenue
        <?php
          echo $mailSession;
          echo " !";
        ?>
    </div>
    <div id="infosPersoWidget">
      <h2>Informations personnelles</h2>
        <?php
          require_once ('connect.php');
          $result = pg_query($bddconn, "SELECT nom, prenom, entreprise, genre, pays, metier FROM utilisateur WHERE utilisateur.email='$mailSession';");
          $row = pg_fetch_row($result);
          echo "<div>Nom : $row[0] </div>";
          echo "<div>Prenom : $row[1] </div>";
          echo "<div>Entreprise : $row[2] </div>";
          echo "<div>Genre : $row[3] </div>";
          echo "<div>Pays : $row[4] </div>";
          echo "<div>Métier : $row[5] </div>";
        ?>
    </div>
    <div id="groupeWidget">
      <h2>Groupes auxquels vous appartenez</h2>
    </div>
    <?php


      $result = mysqli_query($link, "INSERT INTO Utilisateur (email, nom, prenom, entreprise, genre, pays, metier) VALUES ('$emailUtilisateur','$nomUtilisateur', '$prenomUtilisateur', '$entrepriseUtilisateur', '$genreUtilisateur', '$paysUtilisateur','$metierUtilisateur');");

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

