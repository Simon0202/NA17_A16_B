<?php
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
    <h1 id="leTitre">Administration</h1>
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
                <input type='text' size='20' id ='titreFlux' name='titreFlux'><br/>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='20' id ='emailResponsable' name='emailResponsable' value=$mailSession>
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
                <input type='text' size='20' id ='emailResponsable' name='emailResponsable' value=$mailSession>
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
            $query="SELECT nom FROM droits_groupes_flux WHERE nom='$titreFluxAModifier'  ORDER BY nom;";

            $result = pg_query($bddconn, $query);
            echo "<h3>Groupes associés</h3>";
            echo "<table>";
            echo "<tr><th>Nom groupe</th><th>Droits</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
                echo "<form method='POST' action='admin.php'>";
                echo "<button name='nomGroupeASupprimerDuFlux' value='$row[0]'>-</button>";
                echo "</form>";
                echo "</td>";
                echo "<tr>";
            }
            echo "</table>";
            echo "<div id='listeGroupesFlux'>
            <label for='emailResponsable'>Ajouter groupe :</label><br/>
            <input type='text' size='20' id ='emailResponsableGroupe' name='nomGroupeAAjouterFlux' value='Nom du groupe'>
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
        $emailRespFlux=$_POST['emailResponsable'];
        $typeModif=$_POST['typeModif'];
        $ajouterGroupeType = $_POST['ajouterGroupeType'];
        $titreFluxAModifier=$_SESSION['fluxSelectionne'];


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

        ?>
      </form>       
    </div>

    <!--Module d'affichage et de création de flux-->
    <?php

    $titreFluxAModifier=$_SESSION['fluxSelectionne'];

    if(isset($titreFluxAModifier)){
        echo "<div id='vosPublications' style='background-color:#eae8e4ff'>
        <h2>$titreFluxAModifier</h2>
        <div id='listeDesPublications'>";

        $query="SELECT titre, lien, etat FROM Publication where createur='$titreFluxAModifier' ORDER BY titre;";

        $result = pg_query($bddconn, $query);
        echo "<table>";
        echo "<tr><th>Titre</th><th>Lien</th><th>Etat</th></tr>";
        while($row=pg_fetch_array($result)){
          echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
          echo "<form method='POST' action='admin.php'>";
          echo "<button name='titreFluxASupprimer' value='$row[0]'>Supprimer</button>";
          echo "</form>";
          echo "</td>";
          echo"<td><form method='POST' action='admin.php'>";
          echo "<button name='titreFluxAModifier' value='$row[0]'>Selectionner</button>";
          echo "</form></td>";
          echo "<tr>";
        }
        echo "</table>";


        echo"</div>
          <form method='POST' action='admin.php'>
            <h3>Modifier / Créer publication</h3>
            <label for='titre'>Titre : </label>
            <input type='text' size='20' id ='titrePublication' name='titrePublication'><br/>
            <label for='titre'>Lien : </label>
            <input type='text' size='20' id ='lienPublication' name='lienPublication'>
            <input type='submit'/>
            <div id='scorePublication'>
            Le score de la publication apparait ici.
            </div>
            <button>Valider/Dévalider</button>
          </form>       
        </div>";
    }
    ?>


    <div id="vosGroupes">
      <h2>Parcourir vos groupes</h2>
      <div id="listeDesGroupes">
         
      <!--On affiche ici la liste des groupes dont l'utilisateur est responsable-->     
      <?php

        $query="SELECT titre FROM Flux where createur='$mailSession' ORDER BY titre;";  
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

        if (isset($titreGroupeAModifier)){
            $_SESSION['groupeSelectionne'] = $titreGroupeAModifier;
            if(strcmp($titreFluxAModifier, "nouveau") ==0){
                echo "<form id='groupeModification' method='POST' action='admin.php'>
                <h3>Nouveau groupe</h3>
                <label for='titre'>Titre : </label>
                <input type='text' size='20' id ='titreGroupe' name='titreFlux'><br/>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='20' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <input name='typeModif' value='Créer' type='submit'/>
                ";

            }
            else {
                $query="SELECT titre FROM Flux where createur='$mailSession' ORDER BY titre;";

                $result = pg_query($bddconn, $query);

                $row=pg_fetch_array($result);
                
                echo "<form id='groupeModification' method='POST' action='admin.php'>
                <h3>Modifier $titreGroupeAModifier</h3>
                <label for='emailResponsable'>Email responsable :</label>
                <input type='text' size='20' id ='emailResponsable' name='emailResponsable' value=$mailSession>
                <br/>
                <input name='typeModif' value='Enregistrer' type='submit'/>
                "; 
            }

            //Création du tableau regroupant les utilisateurs du groupe
            $query="SELECT groupe_utilisateur.email_admin, droits_groupe.redacteur FROM droits_groupe, groupe_utilisateur WHERE droits_groupe_flux.flux='$titreFluxAModifier' AND droits_groupe.id_utilisateur= groupe_utilisateur ORDER BY titre;";

            $result = pg_query($bddconn, $query);
            echo "<h3>Membres</h3>";
            echo "<table>";
            echo "<tr><th>Email</th></tr>";
            while($row=pg_fetch_array($result)){
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>"; 
                echo "<form method='POST' action='admin.php'>";
                echo "<button name='titreMembreASupprimer' value='$row[0]'>-</button>";
                echo "</form>";
                echo "</td>";
                echo "<tr>";
            }
            echo "</table>";
            echo "<div id='listeGroupesFlux'>
            <label for='emailMembre'>Ajouter membre :</label><br/>
            <input type='text' size='20' id ='emailMembre' name='emailMembre' value='Mail utilisateur'>
            <button name='ajouterLecteur'>Ajouter</button>
            </div>";

        }
        ?>

        <!--On traite ensuite les données envoyées pour créer/modifier le groupe -->

        <?php

        $titreGroupe=$_POST['titreGroupe'];
        $titreGroupeASupprimer=$_POST['titreGroupeASupprimer'];
        $emailRespGroupe=$_POST['emailResponsable'];
        $typeModif=$_POST['typeModif'];
        $titreGroupeAModifier=$_SESSION['groupeSelectionne'];

        if(isset($titreGroupeASupprimer)){
            $result = pg_query($bddconn, "DELETE FROM flux WHERE titre='$titreGroupeASupprimer';");
            if (!isset($row)) {
                echo "<br/>Le groupe n'a pu être supprimé.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }

        }

        /*if(strcmp($typeModif, 'Enregistrer')==0){
            $result = pg_query($bddconn, "UPDATE flux SET confidentialite='$confidentialiteFlux' WHERE titre='$titreGroupeAModifier' AND createur='$emailRespGroupe';");
            echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
        };*/

        if(isset($titreGroupe)){
            $testExist = pg_query($bddconn, "SELECT titre FROM flux WHERE flux.titre='$titreGroupe';");
            $testFetched = pg_fetch_row($testExist);
            if(strcmp($row[0], $titreGroupe)==0){
                $result = pg_query($bddconn, "UPDATE flux SET confidentialite='$confidentialiteFlux' WHERE titre='$titreGroupe' AND createur='$emailRespGroupe';"); 
            }
            else{
                $result = pg_query($bddconn, "INSERT INTO flux (titre, confidentialite, createur) VALUES ('$titreGroupe','$confidentialiteFlux', '$emailRespGroupe');");       
            }
            $testExist = pg_query($bddconn, "SELECT titre FROM flux WHERE flux.titre='$titreGroupe';");
            $row = pg_fetch_row($testExist);
            if (!isset($row[0])) {
                echo "<br/>Le groupe n'a pas pu être ajouté.\n";
            }
            else{
                echo "<meta http-equiv=Refresh content='0; url=admin.php' />";
            }
        }

        ?>
      </form>       
    </div>


    </div>
  </body>
</html>

