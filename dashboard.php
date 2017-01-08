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
    <a href="dashboard.php">Dashboard</a>
    <a href="createPublication.php">Créer une publication</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Dashboard</h1>


<?php
/*
************************************
*****Insertion des commentaires*****
************************************
*/
?>
    <!--Insertion commentaire1 -->
<?php  
  //Variables pour commenter
    $lien_publi1 = $_POST['lienOnReview'];
    $dateOfReview = $_POST['dateOfReview'];
    $emailReviewer = $_POST['emailReviewer'];
    $commentaire1 = $_POST['commenter1'];

    //Variable pour faire un vote
      $vote = $_POST['vote'];
      $emailVoter = $_POST['emailVoter'];
      $lienVoter = $_POST['lienVoter'];
    /*echo "<br/>";
    echo "'$lien_publi1'";
    echo "<br/>";
    echo "'$personEmail'";
    echo "<br/>";
    echo "'$personName'";
    echo "<br/>";
    echo "'$commentaire1'";
    echo "<br/>";*/

    require_once ('connect.php');
    if(isset($commentaire1,$dateOfReview,$emailReviewer,$lien_publi1)){
      $queryComm = "INSERT INTO commentaire(lien_publi, email, datecomm, comm) VALUES ('$lien_publi1','$emailReviewer','$dateOfReview', '$commentaire1');";
      $resultComm = pg_query($bddconn,$queryComm) or die('Échec de la requête : ' . pg_last_error());

    echo "<br/>";
    echo "<br/>";
    echo "<i>Ajout reussi</i>";
    }

    if(isset($lienVoter,$emailVoter,$vote)) {

      echo "$lienVoter";
      echo "<br>";
      echo "$emailVoter";
      echo "<br>";
      echo "$vote";
      echo "<br>";


        $queryComm = "INSERT INTO lire VALUES ('$lien','$email','$vote');";
      $resultComm = pg_query($bddconn,$queryComm) or die('Échec de la requête : ' . pg_last_error());

    echo "<br/>";
    echo "<br/>";
    echo "<i>Vote reussi</i>";
    }
?>






    <!--La section qui affiche l'ensemble du flux de l'utilisateur-->  
    <div id="fluxDePublications">
    	<h2>Flux accessibles</h2>
    	<?php  
    		require_once('connect.php');

    		$query="SELECT f.titre, f.confidentialite FROM Flux f, droits_groupes_flux dgf, compo_groupe cg where cg.nom=dgf.nom AND dgf.flux=f.titre AND cg.email='$mailSession' GROUP BY titre ORDER BY titre;";

    		$result = pg_query($bddconn, $query);
    		
        echo "<table>";
        echo "<tr><th>Titre</th><th>Confidentialite</th></tr>";
    		while($row=pg_fetch_array($result)){
    			echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td>";
          echo "<td><form method='POST' action='dashboard.php'>";
          echo "<button name='fluxSelectionneDashboard' value='$row[0]'>Ouvrir</button>";
          echo "</form></td>";
          echo "<tr>";	
    		}
        echo "</table>";
    	?>
    </div>
    <!--Fin de l'affichage des Flux accessibles-->


    <!--La section qui affiche l'ensemble des publications relatives au flux de l'utilisateur-->  
    <div id="listePublications">
      <?php

      $fluxSelectionneDashboard=$_POST['fluxSelectionneDashboard'];
      if (isset($fluxSelectionneDashboard)){
            $_SESSION['fluxSelectionneDashboard'] = $fluxSelectionneDashboard;
      }
      $fluxSelectionneDashboard = $_SESSION['fluxSelectionneDashboard'];

      if(isset($fluxSelectionneDashboard)){

        echo "<h2>$fluxSelectionneDashboard</h2>";
        
        $query="SELECT p.lien, p.titre, p.date_publi, p.last_edit FROM publication p WHERE p.flux='$fluxSelectionneDashboard' AND p.etat<>'rejete' ORDER BY p.date_publi, p.titre;";

        $result = pg_query($bddconn, $query);

        echo "<table>";
        echo "<tr><th>Liens</th><th>Titre</th><th>Date de publication</th><th>Derniere edition</th></tr>";
          while($row=pg_fetch_array($result)){
            echo "<tr align='center'> <td><a href='$row[0]' target='_blank'>$row[0]</a></td> <td>$row[1]</td> <td>$row[2]</td> <td>$row[3]</td> <td>$row[4]</td>";
            echo "<td><form method='POST' action='dashboard.php'>";
            echo "<button name='reviews' value='$row[0]'>Reviews</button>"; 
            echo "</form></td></tr>";
          }
        echo "</table>";
      }

      ?>
    </div>
    <!--Fin des publications d'un Flux accessibles-->

    <!--La section qui affiche les commentaires et les likes/dislikes--> 
    <?php
      $reviews=$_POST['reviews'];      

        if ($reviews!='hide'&&isset($reviews)){


          echo "<h3>Score</h3>";

          $query = "SELECT ps.sum from publi_score ps where ps.lien = '$reviews';";
          $result = pg_query($bddconn,$query);
          while($row=pg_fetch_array($result)){
            echo "<p>Le score de la publication est : <b>$row[0]</b></p>";
          }

          echo "<table>";
          echo "<tr> <form method='POST' action='dashboard.php'> ";
          echo "<td> <button name='vote' value='1'>like</button>  </td>";
          echo "<td> <button name='vote' value='-1'>dislike</button> </td>";
          echo "<input type='hidden' value='$reviews' name='lienVoter'>";
          echo "<input type='hidden' value='$mailSession' name='emailVoter'>";
          echo "</form> </tr>";
          echo "</table>";







          echo "<h3>Commentaires liés</h3>";

          $query="SELECT c.email, c.datecomm, c.comm from commentaire c where c.lien_publi ='$reviews';";
          $result = pg_query($bddconn,$query);

          echo "<table>";
          echo "<tr><th>Email</th><th>Date de publication</th><th>Commentaires</th></tr>";
          while($row=pg_fetch_array($result)){
            echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>";
            echo "<td><form method='POST' action='dashboard.php'>";
            echo "<button name='modifier' value='$row[1]'>Modifier</button>"; 
            echo "</form></td></tr>";
          }
          echo "</table>";

   
          //Proposition de commentaire pour les articles
          echo "<br/>";
          echo "<br/>";
          echo "<br/>";
          echo "<h3>Deposer un commentaire</h3>";
          $today = date('Y-m-d G:i:s');
          echo "<form method='POST' action='dashboard.php'>";
          echo "<input type='text' size='60' name='commenter1'>";
          echo "<input type='hidden' value='$reviews' name='lienOnReview'>";
          echo "<input type='hidden' value='$mailSession' name='emailReviewer'>";
          echo "<input type='hidden' value='$today' name='dateOfReview'>";
          echo "<input type='submit' value='commenter'>";
          echo "</form>";



          //Boutton pour cacher les commentaires
          echo "<br/>";
          echo "<br/>";
          echo "<br/>";
          echo "<form method='POST' action='dashboard.php'>";
          echo "<button name='reviews' value='hide'>Cacher le contenu</button>";
          echo "</form>";
        }
    ?>
 </div>
  </body>
</html>

