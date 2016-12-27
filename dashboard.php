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


<?php
/*
************************************
*****Insertion des commentaires*****
************************************
*/
?>
        <!--Insertion commentaire1 relatif a ses propres articles-->
    <?php  
    $lien_publi1 = $_POST['lienPubli'];
    $personEmail = $mailSession;
    $personName = $_POST['nameGroup'];
    $commentaire1 = $_POST['commenter1'];

/*
    echo "<br/>";
    echo "'$lien_publi1'";
    echo "<br/>";
    echo "'$personEmail'";
    echo "<br/>";
    echo "'$personName'";
    echo "<br/>";
    echo "'$commentaire1'";
    echo "<br/>";
*/


    if(isset($commentaire1,$personName,$personEmail,$lien_publi1)){
      $queryComm = "INSERT INTO Lire(lienPubli, email, nom, commentaire) VALUES ('$lien_publi1','$personEmail','$personName','$commentaire1');";
      $resultComm = pg_query($bddconn,$queryComm);

      if($resultComm){
        echo "INSERTION DU COMMENTAIRE";
      }
      else{
        echo "ECHEC DU COMMENTAIRE";
      }
    }

    ?>






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
      echo "<tr><th>Liens</th><th>Titre</th><th>Date de publication</th><th>Etat</th><th>Derniere edition</th><th>Provenance</th></tr>";
        while($row=pg_fetch_array($result)){
          echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td>";
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='articlesToShow' value='$row[0]'>Articles</button>";
          echo "</form></td>";  
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='multimediaToShow' value='$row[0]'>Multimedia</button>";
          echo "</form></td></tr>";
        }
      echo "</table>";

      ?>
    </div>


    <!--La section qui affiche le contenu d'un article--> 
    <?php
      $linkOfArticles=$_POST['articlesToShow'];
      $linkOfMultimedia=$_POST['multimediaToShow'];       

        if ($linkOfArticles!='hide'&&isset($linkOfArticles)){
          $query="SELECT a.texte, a.url_piece_jointe from article a where a.lien ='$linkOfArticles';";
          $result = pg_query($bddconn,$query);
          $row=pg_fetch_array($result);

          $query2="SELECT g.nom from groupe_utilisateur g where g.email_admin='$mailSession';";
          $result2= pg_query($bddconn,$query2);
          $row2=pg_fetch_array($result2);

          echo "<h3>Contenu de l'article </h3>";
          echo "<p>$row[0]</p>";
          echo "<br/>";



          //Proposition de commentaire pour les articles
          echo "<h4>Deposer un commentaire</h4>";
          echo "<form method='POST' action='dashboard.php'>";
          echo "<input type='text' size='60' name='commenter1'>";
          echo "<input type='hidden' value='$linkOfArticles' name='lienPubli'>";
          echo "<input type='hidden' value='$row2[0]' name='nameGroup'>";
          echo "<input type='submit' value='commenter'>";
          echo "</form>";




          echo "<h3>URL relative a l'article</h3>";
          echo "<p>$row[1]</p>";
          echo "<br/>";
          echo "<br/>";
          echo "<br/>";
          echo "<form method='POST' action='dashboard.php'>";
          echo "<button name='articlesToShow' value='hide'>Cacher le contenu</button>";
          echo "</form>";

        }  
    ?>

        <!--La section qui affiche le contenu multimedia--> 
    <?php
        if ($linkOfMultimedia!='hide'&&isset($linkOfMultimedia)){
          $query="SELECT m.legende, m.url from multimedia m where m.lien = '$linkOfMultimedia';";

          $result = pg_query($bddconn,$query);
          $row=pg_fetch_array($result);

          echo "<h3>Legende</h3>";
          echo "<p>$row[0]</p>";
          echo "<br/>";  

          echo "<h3>URL</h3>";
          echo "<p>$row[1]</p>";       

 echo "<br/>";
        echo "<br/>";
        echo "<br/>";
        echo "<form method='POST' action='dashboard.php'>";
        echo "<button name='multimediaToShow' value='hide'>Cacher le contenu</button>";
        echo "</form>";

      }
/*
*********************************
*****Fin de mes publications*****
*********************************
*/
    ?>



    <!--La section qui affiche l'ensemble des publications visibles l'utilisateur autre que les siennes-->  
    <div id="autrePublications">
      <h2>Publications Visibles</h2>
      <?php
        $query1= "SELECT p.lien, p.flux, p.date_publi, p.etat, p.last_edit from Flux f, publication p where f.confidentialite='public' and f.createur<>'$mailSession' and f.titre = p.flux and p.etat='valide';";

        $result1 = pg_query($bddconn, $query1);

        echo "<table>";
        echo "<tr><th>Liens</th><th>Titre</th><th>Date de publication</th><th>Etat</th><th>Derniere edition</th></tr>";
        while($row1=pg_fetch_array($result1)){
          echo "<tr align='center'><td>$row1[0]</td><td>$row1[1]</td><td>$row1[2]</td><td>$row1[3]</td><td>$row1[4]</td>";
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='articlesToShow1' value='$row1[0]'>Articles</button>";
          echo "</form></td>";  
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='multimediaToShow1' value='$row1[0]'>Multimedia</button>";
          echo "</form></td>";
          echo "</tr>";
        }
      echo "</table>";

     

      ?>
      </div>


    <!--La section qui affiche le contenu d'un article--> 
    <?php
      $linkOfArticles=$_POST['articlesToShow1'];
      $linkOfMultimedia=$_POST['multimediaToShow1'];
        

        if ($linkOfArticles!='hide'&&isset($linkOfArticles)){
          $query="SELECT a.texte, a.url_piece_jointe from article a where a.lien = '$linkOfArticles';";

          $result = pg_query($bddconn,$query);
          $row=pg_fetch_array($result);

          echo "<h3>Contenu de l'article</h3>";
          echo "<p>$row[0]</p>";
          echo "<button value='like'>like</button> <button value='dislike'>dislike</button>";
          echo "<button value='commenter'>Deposer un commentaire</button>";
          echo "<br/>";
          echo "<h3>URL relative a l'article</h3>";
          echo "<p>$row[1]</p>";

          echo "<br/>";
        echo "<br/>";
        echo "<br/>";
        echo "<form method='POST' action='dashboard.php'>";
        echo "<button name='multimediaToShow' value='hide'>Cacher le contenu</button>";
        echo "</form>";
        }  
    ?>

        <!--La section qui affiche le contenu multimedia--> 
    <?php
        if ($linkOfMultimedia!='hide'&&isset($linkOfMultimedia)){
          $query="SELECT m.legende, m.url from multimedia m where m.lien = '$linkOfMultimedia';";

          $result = pg_query($bddconn,$query);
          $row=pg_fetch_array($result);

          echo "<h3>Legende</h3>";
          echo "<p>$row[0]</p>";      
          echo "<button value='like'>like</button> <button value='dislike'>dislike</button>";
          echo "<button value='commenter'>Deposer un commentaire</button>";
          echo "<br/>";
          echo "<h3>URL</h3>";
          echo "<p>$row[1]</p>";

          echo "<br/>";
          echo "<br/>";
          echo "<br/>";
          echo "<form method='POST' action='dashboard.php'>";
          echo "<button name='multimediaToShow' value='hide'>Cacher le contenu</button>";
          echo "</form>";
        }  
/*
***************************************
*****Fin des publications d'autrui*****
***************************************
*/
        
    ?>
    </div>


  </body>
</html>

