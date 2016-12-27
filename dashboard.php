<?php
  session_start();
  $mailSession = $_SESSION["emailUtilisateurCourant"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen">
      h3 {
        color : red;
      }
    </style>
  </head>

  <body>   
    <!--En tete de la page-->  
    <a href="homePage.php">Page d'accueil</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Dashboard</h1>


    <!--La section qui affiche l'ensemble du flux de l'utilisateur-->  
    <div id="fluxDePublications">
    	<h2>Mon Flux</h2>
    	<?php  
    		require_once('connect.php');

    		$query="SELECT titre, confidentialite FROM Flux where createur='$mailSession' ORDER BY titre;";

    		$result = pg_query($bddconn, $query);
    		
			echo "<table>";
			echo "<tr><th>Titre</th><th>Confidentialite</th></tr>";
    		while($row=pg_fetch_array($result)){
    			echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td></tr>";	
    		}
			echo "</table>";
    	?>
    </div>


    <!--La section qui affiche l'ensemble des publications relatives au flux de l'utilisateur-->  
    <div id="listePublications">
      <h2>Mes Publications</h2>
      <?php
        $query="SELECT p.lien, p.titre, p.date_publi, p.etat, p.last_edit, f.titre FROM publication p, flux f WHERE p.flux=f.titre and f.createur = '$mailSession' ORDER BY p.date_publi, f.titre;";

        $result = pg_query($bddconn, $query);

      echo "<table>";
      echo "<tr><th>Lien</th><th>Titre</th><th>Date de publication</th><th>Etat</th><th>Derniere edition</th><th>Provenance</th></tr>";
        while($row=pg_fetch_array($result)){
          echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td>";
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='articlesToShow' value='$row[0]'>Articles</button>";
          echo "</form></td></tr>";  
        }
      echo "</table>";

      ?>
    </div>


    <!--La section qui affiche le contenu d'un article--> 
    <?php
      $linkOfArticles=$_POST['articlesToShow'];
        

        if (isset($linkOfArticles)){
          $query="SELECT a.texte from article a where a.lien = '$linkOfArticles';";

          $result = pg_query($bddconn,$query);
          $row=pg_fetch_array($result);

          echo "<h3 color='red'>Contenu de l'article</h3>";
          echo "<p>$row[0]</p>";
        }
    ?>



    <!--La section qui affiche l'ensemble des publications visibles l'utilisateur autre que les siennes-->  
    <div id="autrePublications">
      <h2>Publications Visibles</h2>


      <button value='like'>like</button> <button value='dislike'>dislike</button>
      <button value='commenter'>Deposer un commentaire</button>
    </div>


  </body>
</html>

