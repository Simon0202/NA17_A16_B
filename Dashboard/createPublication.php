<?php
error_reporting(0);
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
    <h1 id="leTitre">Créer une publication</h1>

    <!--La section qui affiche l'ensemble du flux de l'utilisateur-->  
    <div id="fluxDePublications">
        <h2>Flux de création</h2>
        <?php  
            require_once('connect.php');
            $personEmail = $mailSession;

            $query="SELECT f.titre, f.confidentialite FROM Flux f, droits_groupes_flux dgf, compo_groupe cg where (cg.nom=dgf.nom AND dgf.flux=f.titre AND cg.email='$personEmail' AND dgf.redacteur=TRUE AND f.confidentialite='public') OR f.createur='$personEmail' GROUP BY titre ORDER BY titre;";

            $result = pg_query($bddconn, $query);
            
        echo "<table>";
        echo "<tr><th>Titre</th><th>Confidentialite</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td>";
          echo "<td><form method='POST' action='createPublication.php'>";
          echo "<button name='fluxSelectionneCreationPublication' value='$row[0]'>Ouvrir</button>";
          echo "</form></td>";
          echo "<tr>";  
            }
        echo "</table>";
        ?>
    </div>


    <!--La section qui affiche l'ensemble des publications relatives au flux de l'utilisateur-->  
    <div id="listePublications">
      <?php

      $fluxSelectionneCreationPublication=$_POST['fluxSelectionneCreationPublication'];
      if (isset($fluxSelectionneCreationPublication)){
            $_SESSION['fluxSelectionneCreationPublication'] = $fluxSelectionneCreationPublication;
      }
      $fluxSelectionneCreationPublication = $_SESSION['fluxSelectionneCreationPublication'];

      if(isset($fluxSelectionneCreationPublication)){
        echo "<h2>$fluxSelectionneCreationPublication</h2>";
        
        $query="SELECT p.lien, p.titre, p.date_publi, p.etat, p.last_edit FROM publication p WHERE p.flux='$fluxSelectionneCreationPublication' ORDER BY p.date_publi, p.titre;";

        $result = pg_query($bddconn, $query);

        echo "<table>";
        echo "<tr><th>Liens</th><th>Titre</th><th>Date de publication</th><th>Etat</th><th>Derniere edition</th><th></th></tr>";
          while($row=pg_fetch_array($result)){
            echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td>";
            if (strcmp($row[4], $mailSession)==0){
              echo "<td><form method='POST' action='createPublication.php'>";
              echo "<button name='publicationToDelete' value='$row[0]'>-</button>";
              echo "</form></td>";
            }
            else{
              echo "<td></td>";
            }

            echo "<td><form method='POST' action='createPublication.php'>";
            echo "<button name='publicationToModify' value='$row[0]'>Modifier</button>";
            echo "</form></td></tr>";
          }
            echo "<tr><td></td><td></td><td></td><td></td><td></td><td><form method='POST' action='createPublication.php'>";
            echo "<button name='creerPublication' value='new'>+</button>";
            echo "</form></td></tr>";

        echo "</table>";
      }


      ?>
    </div>


    <!--La section qui permet de créer/modifier un article--> 
    <?php
      $creerPublication=$_POST['creerPublication'];  
      $publicationToModify=$_POST['publicationToModify'];
      $fluxSelectionneCreationPublication = $_SESSION['fluxSelectionneCreationPublication']; 

      if (isset($creerPublication)) {
        echo "<form id='publicationCreation' method='POST' action='createPublication.php'>
        <h3>Nouvelle publication</h3>
        <label for='titre'>Titre : </label>
        <input type='text' size='20' id ='titrePublication' name='titrePublication'><br/>
        <label for='lienPublication'>Lien :</label>
        <input type='text' size='20' id ='lienPublication' name='lienPublication'>
        <br/>
        <input name='typeModif' value='Créer' type='submit'/>
        ";

      }

      if (isset($publicationToModify)) {
        $query="SELECT p.lien, p.titre, p.date_publi, p.etat, p.last_edit FROM publication p WHERE p.flux='$fluxSelectionneCreationPublication' AND p.lien='$publicationToModify' ORDER BY p.date_publi, p.titre;";
        $result = pg_query($bddconn,$query);
        $row = pg_fetch_array($result);

        echo "<form id='publicationCreation' method='POST' action='createPublication.php'>
        <h3>$row[1]</h3>
        <label for='titre'>Titre : </label>
        <input type='text' size='20' id ='titrePublication' name='titrePublication' value='$row[1]'><br/>
        <input name='typeModif' value='Modifier' type='submit'/>
        <input type='hidden' value='$row[0]' name='lienPublication'>
        </form>";

      }

    ?>
    <!--on traite les données traitées pour la création et la modification de publication-->
    <?php
      $titrePublication=$_POST['titrePublication']; 
      $lienPublication=$_POST['lienPublication'];  
      $typeModif=$_POST['typeModif'];
      $publicationToDelete=$_POST['publicationToDelete'];
      $fluxSelectionneCreationPublication = $_SESSION['fluxSelectionneCreationPublication'];

      if (isset($typeModif) && strcmp($typeModif, 'Créer')==0){
        $queryComm = "INSERT INTO Publication(lien, flux, titre, date_publi, etat, last_edit) VALUES ('$lienPublication','$fluxSelectionneCreationPublication', '$titrePublication', current_date, 'valide', '$mailSession');";
        $resultComm = pg_query($bddconn,$queryComm);
        $testComm = "SELECT lien FROM Publication WHERE lien='$lienPublication'";
        $resultComm = pg_query($bddconn,$testComm);
        var_dump($resultComm);
        $row = pg_fetch_array($resultComm);
        if(strcmp($row[0], $lienPublication)==0){
          echo "<meta http-equiv=Refresh content='0; url=createPublication.php' />";
        }
        else{
          echo "ECHEC DE L INSERTION DE LA PUBLICATION";
        }
      }

      if (isset($typeModif) && strcmp($typeModif, 'Modifier')==0){


        $query = "UPDATE Publication SET titre='$titrePublication', date_publi=current_date, last_edit='$mailSession' WHERE lien='$lienPublication';";
        $result = pg_query($bddconn,$query);
        $test = "SELECT lien, titre FROM Publication WHERE lien='$lienPublication'";
        $result = pg_query($bddconn,$test);
        $row = pg_fetch_array($result);
        if(strcmp($row[1], $titrePublication)==0){
          echo "<meta http-equiv=Refresh content='0; url=createPublication.php' />";
        }
        else{
          echo "ECHEC DE LA MODIFICATION DE LA PUBLICATION";
        }
      }

      if (isset($publicationToDelete)){
        $query = "DELETE FROM Publication WHERE lien='$publicationToDelete';";
        $resultComm = pg_query($bddconn, $query);
        echo "<meta http-equiv=Refresh content='0; url=createPublication.php' />";
      }


    ?>
  </body>
</html>

