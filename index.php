<?php
    session_start();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>
    <h1 id="leTitre">Dashboard</h1>
    <div id="connexionWidget">
    	<h2>Connexion</h2>
	    <form method="POST" action="index.php">
	    	<label for="emailUtilisateurExistant">Email : </label>
	        <input type="text" size="20" id ="emailUtilisateurExistant" name="emailUtilisateurExistant">
	        <input type="submit"/>
	    </form>
	    <p>Entrer l'adresse mail d'un utilisateur enregistré (ex: paul@email.com)</p>
        <?php

            $emailUtilisateurExistant=$_POST['emailUtilisateurExistant'];
            if ($emailUtilisateurExistant){
                require_once ('connect.php');

                $result = pg_query($bddconn, "SELECT nom FROM utilisateur WHERE utilisateur.email='$emailUtilisateurExistant';");
                $row = pg_fetch_row($result);
                if (!$row) {
                    echo "<br/>Utilisateur inexistant.\n";
                }
                else{
                    echo "$row[0]";
                    echo "<br />\n";
                    $_SESSION["emailUtilisateurCourant"] = $emailUtilisateurExistant;

                    header ("Location: homePage.php");
                }
                pg_close($bddconn);        
            }
        ?>
    </div>
    <div id="inscriptionWidget">
     	<h2 id="leTitreModule">Inscription</h2>
	    <form method="POST" action="index.php">
	        <label for="emailUtilisateur">Email : </label>
	        <input type="text" size="20" id ="emailUtilisateur" name="emailUtilisateur"><br/>
	        <label for="emailUtilisateur">Nom : </label>
	        <input type="text" size="20" id ="nomUtilisateur" name="nomUtilisateur"/><br/>
	        <label for="emailUtilisateur">Prenom : </label>
	        <input type="text" size="20" id="prenomUtilisateur" name="prenomUtilisateur"/><br/>
	        <label for="emailUtilisateur">Entreprise : </label>
	        <input type="text" size="20" id="entrepriseUtilisateur" name="entrepriseUtilisateur"/><br/>
	        <label for="emailUtilisateur">Genre : </label>
	        <input type="text" size="20" id="genreUtilisateur" name="genreUtilisateur"/><br/>
	        <label for="emailUtilisateur">Pays : </label>
	        <input type="text" size="20" id="paysUtilisateur" name="paysUtilisateur"/><br/>
	        <label for="emailUtilisateur">Metier : </label>
	        <input type="text" size="20" id="metierUtilisateur" name="metierUtilisateur"/><br/>
	        <input type="submit"/>
	    </form>
      </div>
  </body>
</html>

