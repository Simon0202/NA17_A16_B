<?php
error_reporting(0);
    session_start();
    $mailSession = $_SESSION["emailUtilisateurCourant"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Administration</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>
    <a href="homePage.php">Page d'accueil</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="createPublication.php">Créer une publication</a>
    <a href="admin.php">Administration</a>
    <h1 id="leTitre">Administration</h1>

 <div id="vosGroupes">
      <h2>Parcourir vos groupes</h2>
      <div id="listeDesGroupes">
         
      <!--On affiche ici la liste des groupes dont l'utilisateur est responsable-->     
      <?php
        require_once ('connect.php');

        $query="SELECT nom FROM groupe_utilisateur where email_admin='$mailSession' ORDER BY nom;";  
        $result = pg_query($bddconn, $query);
        
        echo "<table>";
        echo "<tr><th>Titre</th></tr>";
        while($row=pg_fetch_array($result)){
            echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
            echo "<form method='POST' action='admin.php'>";
            echo "<button name='titreGroupeASupprimer' value='$row[0]'>-</button>";
            echo "</form>";
            echo "</td>";
            echo"<td><form method='POST' action='admin.php'>";
            echo "<button name='titreGroupeAModifier' value='$row[0]'>Ouvrir</button>";
            echo "</form></td>";
            echo "<tr>";
        }
        echo "<tr><td></td><td></td><td><form method='POST' action='admin.php'>";
        echo "<button name='titreGroupeAModifier' value='nouveau'>+</button>";
        echo "</form></td></tr>";
        echo "</table>";
      ?>
      </div>
      <!--La section qui permet de modifier le groupe dépend de la tâche ouvrir/créer un groupe-->          
      <?php

        $titreGroupeAModifier=$_POST['titreGroupeAModifier'];
        $mailSession = $_SESSION["emailUtilisateurCourant"];
        require_once ('connect.php');

        if (isset($titreGroupeAModifier)){
            $_SESSION['groupeSelectionne'] = $titreGroupeAModifier;
            if(strcmp($titreGroupeAModifier, "nouveau") ==0){
                echo "<form id='groupeModification' method='POST' action='admin.php'>
                <h3>Nouveau groupe</h3>
                <label for='titre'>Titre : </label>
                <input type='text' size='40' id ='titreGroupe' name='titreGroupe'><br/>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='40' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <input name='typeModif' value='Créer' type='submit'/>
                <input name='typeOp' value='CREATION_GROUPE' type='hidden'/>
                ";

            }
            else {
                $query="SELECT nom FROM groupe_utilisateur where createur='$mailSession' ORDER BY nom;";

                $result = pg_query($bddconn, $query);

                $row=pg_fetch_array($result);
                
                echo "<form id='groupeModification' method='POST' action='admin.php'>
                <h3>Modifier $titreGroupeAModifier</h3>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='40' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <input name='typeModif' value='Enregistrer' type='submit'/>
                <input name='typeOp' value='MODIFICATION_GROUPE' type='hidden'/>
                "; 
            }

            //Création du tableau regroupant les utilisateurs du groupe

            $titreGroupeAModifier=$_SESSION['groupeSelectionne'];
            $query="SELECT email FROM compo_groupe WHERE nom='$titreGroupeAModifier' ORDER BY email;";

            $result = pg_query($bddconn, $query);
            echo "<h3>Membres</h3>";
            echo "<table>";
            echo "<tr><th>Email</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
                echo "<form method='POST' action='admin.php'>";
                echo "<button name='emailMembreASupprimer' value='$row[0]'>-</button>";
                echo "</form>";
                echo "</td>";
                echo "<tr>";
            }
            echo "</table>";
            echo "<div id='listeMembresGroupe'>
            <label for='emailMembre'>Ajouter membre :</label><br/>
            <input type='text' size='40' id ='emailMembre' name='emailMembreAAjouter' value='Mail utilisateur'>
            <button name='ajouterMembre'>Ajouter</button>
            </div>";

        }
        ?>

        <!--On traite ensuite les données envoyées pour créer/modifier le groupe -->

        <?php
        $titreGroupe=$_POST['titreGroupe'];
        $titreGroupeASupprimer=$_POST['titreGroupeASupprimer'];
        $emailRespGroupe=$_POST['emailResponsable'];
        $titreGroupeAModifier=$_SESSION['groupeSelectionne'];
        $typeOp=$_POST['typeOp'];
        $emailMembreAAjouter=$_POST['emailMembreAAjouter'];
        $emailMembreASupprimer=$_POST['emailMembreASupprimer'];

        require_once ('connect.php');


        if(isset($titreGroupeASupprimer)){
            $result = pg_query($bddconn, "DELETE FROM groupe_utilisateur WHERE nom='$titreGroupeASupprimer';");
            if (!isset($row)) {
                echo "<br/>Le groupe n'a pu être supprimé.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }

        }

        if(strcmp($typeOp, 'MODIFICATION_GROUPE')==0){
            pg_query($bddconn, "UPDATE groupe_utilisateur SET email_admin='$emailRespGroupe' WHERE nom='$titreGroupeAModifier';");
            echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
        };

        if(strcmp($typeOp, 'CREATION_GROUPE')==0){
            $testExist = pg_query($bddconn, "SELECT nom FROM groupe_utilisateur WHERE groupe_utilisateur.nom='$titreGroupe';");
            $testFetched = pg_fetch_row($testExist);
            if(strcmp($row[0], $titreGroupe)==0){
                echo "<br/>Un groupe du même titre existe déjà.\n";
            }
            else{
                pg_query($bddconn, "INSERT INTO groupe_utilisateur (nom, email_admin) VALUES ('$titreGroupe','$emailRespGroupe');");  
                pg_query($bddconn, "INSERT INTO compo_groupe (email, nom) VALUES ('$emailRespGroupe','$titreGroupe');");     
            }
            $testExist = pg_query($bddconn, "SELECT nom FROM groupe_utilisateur WHERE groupe_utilisateur.nom='$titreGroupe';");
            $row = pg_fetch_row($testExist);
            if (!isset($row[0])) {
                echo "<br/>Le groupe n'a pas pu être ajouté.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }
        };

        if(isset($emailMembreAAjouter)){
            pg_query($bddconn, "INSERT INTO compo_groupe (email, nom) VALUES ('$emailMembreAAjouter','$titreGroupeAModifier');");
        }

        if(isset($emailMembreASupprimer)){
            pg_query($bddconn, "DELETE FROM compo_groupe WHERE email='$emailMembreASupprimer' AND nom='$titreGroupeAModifier';");
        }

        ?>
      </form>       
    </div>










    <div id="vosFluxDePublications" style="background-color:#eae8e4ff">
    	<h2>Vos flux de publications</h2>
      <div id="listeVosFluxDePublications">
   
      <!--On affiche ici la liste des flux dont l'utilisateur est responsable-->     
      <?php  
        require_once('connect.php');
        $query="SELECT titre, confidentialite FROM Flux where createur='$mailSession' ORDER BY titre;";

        $result = pg_query($bddconn, $query);
        
        echo "<table>";
        echo "<tr><th>Titre</th><th>Confidentialite</th></tr>";
        while($row=pg_fetch_array($result)){
            echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
            echo "<form method='POST' action='admin.php'>";
            echo "<button name='titreFluxASupprimer' value='$row[0]'>-</button>";
            echo "</form>";
            echo "</td>";
            echo"<td><form method='POST' action='admin.php'>";
            echo "<button name='titreFluxAModifier' value='$row[0]'>Ouvrir</button>";
            echo "</form></td>";
            echo "<tr>";
        }
        echo "<tr><td></td><td></td><td><form method='POST' action='admin.php'>";
        echo "<button name='titreFluxAModifier' value='nouveau'>+</button>";
        echo "</form></td></tr>";
        echo "</table>";
      ?>
      </div>

      
      <!--La section qui permet de modifier le flux dépend de la tâche ouvrir/créer un flux-->          
      <?php

        $titreFluxAModifier=$_POST['titreFluxAModifier'];
        
        if (isset($titreFluxAModifier)){
            $_SESSION['fluxSelectionne'] = $titreFluxAModifier;
            if(strcmp($titreFluxAModifier, "nouveau") ==0){
                echo "<form id='fluxModification' method='POST' action='admin.php'>
                <h3>Nouveau flux</h3>
                <label for='titre'>Titre : </label>
                <input type='text' size='40' id ='titreFlux' name='titreFlux'><br/>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='40' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <label for='confidentialiteFlux'>Confidentialité :</label>
                <select name='confidentialiteFlux'>
                    <option value='public'>Public</option>
                    <option value='prive'>Privé</option>
                </select>
                <input name='typeModif' value='Créer' type='submit'/>
                ";

            }
            else {
                $query="SELECT confidentialite FROM Flux WHERE titre='$titreFluxAModifier';";
                $result = pg_query($bddconn, $query);

                $row=pg_fetch_array($result);
                
                echo "<form id='fluxModification' method='POST' action='admin.php'>
                <h3>Modifier $titreFluxAModifier</h3>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='40' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <label for='confidentialiteFlux'>Confidentialité :</label>
                <select name='confidentialiteFlux'>";
                if (strcmp($row[0], 'public')==0){
                    echo "<option value='Public'>Public</option>
                    <option value='prive'>Privé</option>";
                }
                else{
                    echo "<option value='Privé'>Privé</option>
                    <option value='public'>Public</option>";
                    
                }  
                echo "</select>
                <input name='typeModif' value='Enregistrer' type='submit'/>"; 
            }

            //Création du tableau regroupant les groupes du flux
            $query="SELECT nom, redacteur FROM droits_groupes_flux WHERE flux='$titreFluxAModifier'  ORDER BY nom;";

            $result = pg_query($bddconn, $query);
            echo "<h3>Groupes associés</h3>";
            echo "<table>";
            echo "<tr><th>Nom groupe</th><th>Droits</th></tr>";
            while($row=pg_fetch_array($result)){
                if(strcmp($row[1], 'f')==0){
                    echo "<tr><td>$row[0]</td><td>Lecteur</td><td>";
                }
                if(strcmp($row[1], 't')==0){
                    echo "<tr><td>$row[0]</td><td>Redacteur</td><td>";
                }
                echo "<form method='POST' action='admin.php'>";
                echo "<button name='nomGroupeASupprimerDuFlux' value='$row[0]'>-</button>";
                echo "</form>";
                echo "</td>";
                echo "<tr>";
            }
            echo "</table>";
            echo "<div id='listeGroupesFlux'>
            <label for='emailResponsable'>Ajouter groupe :</label><br/>
            <input type='text' size='40' id ='emailResponsableGroupe' name='nomGroupeAAjouterFlux' value='Nom du groupe'>
            <button name='ajouterGroupeType' value='lecteur'>Lecteur</button>
            <button name='ajouterGroupeType' value='redacteur'>Redacteur</button>
            </div>";

        }
        ?>

        <!--On traite ensuite les données envoyées pour créer/modifier le flux -->

        <?php

        $titreFlux=$_POST['titreFlux'];
        $confidentialiteFlux=$_POST['confidentialiteFlux'];
        $titreFluxASupprimer=$_POST['titreFluxASupprimer'];
        $nomGroupeAAjouterFlux=$_POST['nomGroupeAAjouterFlux'];
        $nomGroupeASupprimerDuFlux=$_POST['nomGroupeASupprimerDuFlux'];
        $emailRespFlux=$_POST['emailResponsable'];
        $typeModif=$_POST['typeModif'];
        $ajouterGroupeType = $_POST['ajouterGroupeType'];
        $titreFluxAModifier=$_SESSION['fluxSelectionne'];

        require_once ('connect.php');


        if(isset($titreFluxASupprimer)){
            $result = pg_query($bddconn, "DELETE FROM flux WHERE titre='$titreFluxASupprimer';");
            if (!isset($row)) {
                echo "<br/>Le flux n'a pu être supprimé.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }

        }

        if(strcmp($typeModif, 'Enregistrer')==0){
            pg_query($bddconn, "UPDATE flux SET createur='$emailRespFlux' WHERE titre='$titreFluxAModifier';");
            pg_query($bddconn, "UPDATE flux SET confidentialite='$confidentialiteFlux' WHERE titre='$titreFluxAModifier';");
            echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
        };
        
        if(isset($titreFlux) && strcmp($typeModif, 'Créer')==0){
            $testExist = pg_query($bddconn, "SELECT titre FROM flux WHERE flux.titre='$titreFlux';");
            $testFetched = pg_fetch_row($testExist);
            if(strcmp($row[0], $titreFlux)==0){
                $result = pg_query($bddconn, "UPDATE flux SET confidentialite='$confidentialiteFlux' WHERE titre='$titreFlux';"); 
            }
            else{
                $result = pg_query($bddconn, "INSERT INTO flux (titre, confidentialite, createur) VALUES ('$titreFlux','$confidentialiteFlux', '$emailRespFlux');");       
            }
            $testExist = pg_query($bddconn, "SELECT titre FROM flux WHERE flux.titre='$titreFlux';");
            $row = pg_fetch_row($testExist);
            if (!isset($row[0])) {
                echo "<br/>Le flux n'a pas pu être ajouté.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }
        }


        if(isset($ajouterGroupeType) && strcmp($ajouterGroupeType, 'lecteur')==0){
            pg_query($bddconn, "INSERT INTO droits_groupes_flux (flux, nom, redacteur) VALUES ('$titreFluxAModifier','$nomGroupeAAjouterFlux', FALSE);");
        }
        if(isset($ajouterGroupeType) && strcmp($ajouterGroupeType, 'redacteur')==0){
            pg_query($bddconn, "INSERT INTO droits_groupes_flux (flux, nom, redacteur) VALUES ('$titreFluxAModifier','$nomGroupeAAjouterFlux', TRUE);");
        }

        if(isset($nomGroupeASupprimerDuFlux)){
            pg_query($bddconn, "DELETE FROM droits_groupes_flux WHERE nom='$nomGroupeASupprimerDuFlux' AND flux='$titreFluxAModifier';");
        }

        ?>
      </form>       
    </div>

    <!--Module d'affichage de la publication selectionnée-->
    <?php

    $titreFluxAModifier=$_SESSION['fluxSelectionne'];
    require_once ('connect.php');

    if(isset($titreFluxAModifier)){
        echo "<div id='vosPublications' style='background-color:#eae8e4ff'>
        <h2>$titreFluxAModifier</h2>
        <div id='listeDesPublications'>";

        $query="SELECT titre, lien, etat FROM publication where flux='$titreFluxAModifier' ORDER BY titre;";

        $result = pg_query($bddconn, $query);
        echo "<table>";
        echo "<tr><th>Titre</th><th>Lien</th><th>Etat</th><th>Score</th></tr>";
        while($row=pg_fetch_array($result)){
          echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td></td>"; 
          echo "<td><form method='POST' action='admin.php'>";
          echo "<button name='lienPublicationASupprimer' value='$row[1]'>-</button>";
          echo "</form>";
          echo "</td></td>";
          echo"<td><form method='POST' action='admin.php'>";
          echo "<button name='lienPubliAValider' value='$row[1]'>Valider/Rejeter</button>";
          echo "<input type='hidden' value='$row[2]' name='etatPubliAValider'>";
          echo "</form></td>";
          echo "<tr>";
        }
        echo "</table>";
        echo "</div></div>";
    }
    ?>

    <!--Gestion de la suppression et de la validation des publications-->
    <?php

    $lienPublicationASupprimer=$_POST['lienPublicationASupprimer'];
    $lienPubliAValider=$_POST['lienPubliAValider'];
    $etatPubliAValider=$_POST['etatPubliAValider'];
    
    require_once ('connect.php');

    if (isset($lienPublicationASupprimer)){
        $query = "DELETE FROM Publication WHERE lien='$lienPublicationASupprimer';";
        $resultComm = pg_query($bddconn, $query);
        echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
    }

    if (isset($lienPubliAValider) && strcmp($etatPubliAValider, 'rejete')==0){
        $query = "UPDATE Publication SET etat='valide' WHERE lien='$lienPubliAValider';";
        $result = pg_query($bddconn,$query);
        $resultComm = pg_query($bddconn, $query);
        echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
    }

    if (isset($lienPubliAValider) && strcmp($etatPubliAValider, 'valide')==0){
        $query = "UPDATE Publication SET etat='rejete' WHERE lien='$lienPubliAValider';";
        $result = pg_query($bddconn,$query);
        $resultComm = pg_query($bddconn, $query);
        echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
    }
    ?>

  </body>
</html>

