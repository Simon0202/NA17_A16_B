<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>   
    <a href="homePage.php">Page d'accueil</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Dashboard</h1>
    <div id="fluxDePublications">
    	<h2>Flux de publications</h2>
      <button>Score croissant/Decroissant</button>
      <button>Date croissant/Decroissant</button>
    </div>
    <div id="listePublications">
      <h2>Publications</h2>
    </div>
    <div id="contenuPublication">
      <h2>Contenu publication</h2>
      <button>Like/Dislike</button>
    </div>
    <div id="listeCommentaires">
      <h2>Commentaires</h2>
      <button>Date croissant/decroissant</button>
    </div>
    <div id="commentaire">
      <h2>Rediger un commentaire</h2>
      <button>OK</button>
    </div>

    <?php

      $link = mysqli_connect('localhost','root','***motDePasse***','dashboard') or die('Error connecting to MySQL server.');


      $emailUtilisateur=$_GET['emailUtilisateur'];
      $emailUtilisateur=mysqli_real_escape_string($link, $emailUtilisateur);
      $nomUtilisateur=$_GET['nomUtilisateur'];
      $nomUtilisateur=mysqli_real_escape_string($link, $nomUtilisateur);
      $prenomUtilisateur=$_GET['prenomUtilisateur'];
      $prenomUtilisateur=mysqli_real_escape_string($link, $prenomUtilisateur);
      $entrepriseUtilisateur=$_GET['entrepriseUtilisateur'];
      $entrepriseUtilisateur=mysqli_real_escape_string($link, $entrepriseUtilisateur);
      $genreUtilisateur=$_GET['genreUtilisateur'];
      $genreUtilisateur=mysqli_real_escape_string($link, $genreUtilisateur);
      $paysUtilisateur=$_GET['paysUtilisateur'];
      $paysUtilisateur=mysqli_real_escape_string($link, $paysUtilisateur);
      $metierUtilisateur=$_GET['metierUtilisateur'];
      $metierUtilisateur=mysqli_real_escape_string($link, $metierUtilisateur);

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

