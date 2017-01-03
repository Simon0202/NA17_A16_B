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
        require_once ('connect.php');
        $result = pg_query($bddconn, "SELECT email_admin, nom FROM groupe_utilisateur WHERE groupe_utilisateur.email_admin='$mailSession';");
        $row = pg_fetch_row($result);
        if($row[0] !== null){
            echo "<h2>Informations du groupe : </h2>";
            echo"<br/>";
            echo "<div>Nom du groupe : $row[1] </div>";
            echo "<div>E-mail de responsable du groupe : $mailSession</div>";
            echo "<div>List de membre du group : </div>";
            $a=$row[1];
            $result = pg_query($bddconn, "SELECT u.nom, u.prenom FROM compo_groupe cg, utilisateur u WHERE u.email= cg.email and cg.nom='$a';");
            $row = pg_fetch_row($result);
            if($row[0] == null){
                echo "<div>Vous n'avez aucun membre dans ce groupe : </div>";
            }else{
                echo "<div>                  $row[1], $row[0] </div>";
                while($row = pg_fetch_row($result)){
                    echo "<div>                  $row[1], $row[0] </div>";
                }
            }
        }else{
            $result = pg_query($bddconn, "SELECT nom FROM compo_groupe WHERE compo_groupe.email='$mailSession';");
            $row = pg_fetch_row($result);
            if($row[0] == null){
                echo "<h2>Vous n'appartenez aucun groupe : </h2>";
            }else{
                $nom_g=$row[0];
                $result = pg_query($bddconn, "SELECT gu.email_admin, u.nom, u.prenom FROM compo_groupe cg, utilisateur u, groupe_utilisateur gu WHERE u.email= cg.email and cg.nom = gu.nom and cg.nom='$nom_g';");
                $row = pg_fetch_row($result);
                echo "<h2>Informations du groupe : </h2>";
                echo"<br/>";
                echo "<div>Nom du groupe : $nom_g </div>";
                echo "<div>E-mail de responsable du groupe : $row[0]</div>";
                echo "<div>List de membre du group : </div>";
                echo "<div>$row[2], $row[1] </div>";
                while($row = pg_fetch_row($result)){
                    echo "<div>$row[2], $row[1] </div>";
                }
            }
        }
    ?>
    </div>
    <a href="index.php"><button>Deconnexion</button></a>
  </body>
</html>

