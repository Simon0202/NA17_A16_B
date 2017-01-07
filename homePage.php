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
        require_once ('connect.php');
        echo "<h2>Informations du groupe : </h2>";
        $result=pg_query($bddconn, "select gu.nom, gu.email_admin, u.nom, u.prenom, cg.email from compo_groupe cg, groupe_utilisateur gu, utilisateur u where gu.nom = cg.nom and cg.email=u.email order by gu.nom, u.nom,u.prenom;");
        echo "<table>";
        echo "<tr><th>  Nom du groupe  </th><th>  E-mail de responsable  </th><th>  Prenom,Nom  </th><th> E-mail </th> </tr>";
        while($row=pg_fetch_row($result)){
            if($row[1] = $mailSession){
                echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[3],$row[2]</td><td>$row[4]</td></tr>";
            }
        }
        $result=pg_query($bddconn, "select gu.nom, gu.email_admin, u.nom, u.prenom, cg.email from compo_groupe cg, groupe_utilisateur gu, utilisateur u where gu.nom = cg.nom and cg.email=u.email order by gu.nom, u.nom,u.prenom;");
        //echo "<tr align='center'><td>0</td><td>0</td><td>0</td><td>0</td></tr>";
        while($row=pg_fetch_row($result)){
            if($row[4] = $mailSession){
                $nom_g=$row[0];
                $result2=pg_query($bddconn, "select gu.nom, gu.email_admin, u.nom, u.prenom, cg.email from compo_groupe cg, groupe_utilisateur gu, utilisateur u where gu.nom = cg.nom and cg.email=u.email order by gu.nom, u.nom,u.prenom;");
                while($row=pg_fetch_array($result2)){
                    if($row[0]==$nom_g and $row[1] != $mailSession ){
                        echo "<tr align='center'><td>$row[0]</td><td>$row[1]</td><td>$row[3],$row[2]</td><td>$row[4]</td></tr>";
                    }
                }
            }
        }
        echo "</table>";
        
        /*
        $result = pg_query($bddconn, "SELECT email_admin, nom FROM groupe_utilisateur WHERE groupe_utilisateur.email_admin='$mailSession';");
        $row = pg_fetch_row($result);
        if($row[0] !== null){
            echo "<h2>Informations du groupe : </h2>";
            echo"<br/>";
            $nom_g=$row[1];
            $result = pg_query($bddconn, "SELECT u.nom, u.prenom FROM compo_groupe cg, utilisateur u WHERE u.email= cg.email and cg.nom='$nom_g';");
            $row = pg_fetch_row($result);
            if($row[0] == null){
                echo "<div>Vous n'avez aucun membre dans ce groupe : </div>";
            }else{
                echo "<table>";
                echo "<tr><th>  Nom du groupe  </th><th>  E-mail de responsable  </th><th>  Nom de la membre du groupe  </th></tr>";
                echo "<tr align='center'><td>$nom_g</td><td>$mailSession</td><td>$row[1],$row[0]</td></tr>";
                while($row=pg_fetch_array($result)){
                    echo "<tr align='center'><td>$row[1]</td><td>$mailSession</td><td>$row[1],$row[0]</td></tr>";
                }
                echo "</table>";
            }
        }else{
            $result = pg_query($bddconn, "SELECT nom FROM compo_groupe WHERE compo_groupe.email='$mailSession';");
            $row = pg_fetch_row($result);
            echo "<h2>Informations du groupe : </h2>";
            if($row[0] == null){
                echo "<div>Vous n'appartenez aucun groupe : </div>";
            }else{
                $nom_g=$row[0];
                $result = pg_query($bddconn, "SELECT gu.email_admin, u.nom, u.prenom FROM compo_groupe cg, utilisateur u, groupe_utilisateur gu WHERE u.email= cg.email and cg.nom = gu.nom and cg.nom='$nom_g';");
                $row = pg_fetch_row($result);
                echo "<table>";
                echo "<tr><th>  Nom du groupe  </th><th>  E-mail de responsable  </th><th>  Nom de la membre du groupe  </th></tr>";
                echo "<tr align='center'><td>$nom_g</td><td>$row[0]</td><td>$row[2], $row[1]</td></tr>";
                while($row=pg_fetch_array($result)){
                    echo "<tr align='center'><td>$nom_g</td><td>$row[0]</td><td>$row[2], $row[1]</td></tr>";
                }
                echo "</table>";
            }
        }*/
    ?>
    </div>
    <a href="index.php"><button>Deconnexion</button></a>
  </body>
</html>

