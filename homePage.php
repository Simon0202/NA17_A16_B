<?php
  session_start();
  $mailSession = $_SESSION["emailUtilisateurCourant"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Home Page</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>   
    <a href="dashboard.php">Dashboard</a>
    <a href="admin.php">Administration</a>
    <a href="createPublication.php">Créer une publication</a>
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

        <?php
            require_once('connect.php');
            echo "<h2>Informations du groupe</h2>";
            $query="select gu.nom, gu.email_admin from compo_groupe cg, groupe_utilisateur gu where gu.nom = cg.nom and (gu.email_admin='$mailSession' or cg.email ='$mailSession') group by gu.nom, gu.email_admin order by gu.email_admin, gu.nom";
            $result = pg_query($bddconn, $query);
            
            echo "<table>";
            echo "<tr><th>Nom du groupe</th><th>Responsable du groupe</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td>";
                echo "<td><form method='POST' action='homePage.php'>";
                echo "<button name='groupememberselect' value='$row[0]'>Ouvrir</button>";
                echo "</form></td>";
                echo "<tr>";
            }
            echo "</table>";
            ?>
    </div>

    <div id="listeMembre">
    <?php
 
        $groupememberselect=$_POST['groupememberselect'];
        if (isset($groupememberselect)){
            $_SESSION['groupememberselect'] = $groupememberselect;
        }
        $groupememberselect = $_SESSION['groupememberselect'];
 
        if(isset($groupememberselect)){
            echo "<h2>$groupememberselect</h2>";
 
            $query="SELECT gu.nom, gu.email_admin, u.nom, u.prenom, u.email  from compo_groupe cg, groupe_utilisateur gu, utilisateur u where gu.nom ='$groupememberselect' and gu.nom=cg.nom and cg.email=u.email order by gu.nom, gu.email_admin, u.nom, u.prenom, u.email;";
 
            $result = pg_query($bddconn, $query);
 
            echo "<table>";
            echo "<tr><th>Nom du groupe</th><th>responsable</th><th>Membre</th><th>E-mail</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[3],$row[2]</td><td>$row[4]</td></tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
<a href="index.php"><button>Deconnexion</button></a>
</body>
</html>



