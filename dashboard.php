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
    <a href="homePage.php">Page d'accueil</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Dashboard</h1>
    <div id="fluxDePublications">
    	<h2>Flux de publications</h2>

    	<?php  
    		require_once('connect.php');

    		$query="SELECT titre, confidentialite FROM Flux where createur='$mailSession';";

    		$result = pg_query($bddconn, $query);
echo "<table>";
echo "<tr><th>Titre</th><th>Confidentialite</th></tr>";
    		while($row=pg_fetch_array($result)){
    			
    			echo "<tr><td>$row[0]<td><td>$row[1]</td><tr>";
    			
    		}
echo "</table>";


    	?>

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

    ?>
  </body>
</html>

