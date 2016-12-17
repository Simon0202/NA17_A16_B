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
        <?php

            $emailUtilisateurExistant=$_POST['emailUtilisateurExistant'];
            if ($emailUtilisateurExistant){
                require_once ('connect.php');

                $result = pg_query($bddconn, "SELECT nom FROM utilisateur WHERE utilisateur.email='$emailUtilisateurExistant';");
                $row = pg_fetch_row($result);
                if (!$row) {
                    echo "<br/>Utilisateur inexistant.\n";
                    echo "<p>Veuillez entrer l'adresse mail d'un utilisateur enregistré (ex: bob@email.com)</p>";
                }
                else{       
                    echo "<br/>";            
                    echo "<b>Chargement de l'utilisateur: $row[0]<b>";
                    echo "<br />\n";

                    $_SESSION["emailUtilisateurCourant"] = $emailUtilisateurExistant;

                    echo "<meta http-equiv=Refresh content='0.5; url=homePage.php' />";
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
        <?php
            $emailUtilisateur=$_POST['emailUtilisateur'];
            $nomUtilisateur=$_POST['nomUtilisateur'];
            $prenomUtilisateur=$_POST['prenomUtilisateur'];
            $entrepriseUtilisateur=$_POST['entrepriseUtilisateur'];
            $genreUtilisateur=$_POST['genreUtilisateur'];
            $paysUtilisateur=$_POST['paysUtilisateur'];
            $metierUtilisateur=$_POST['metierUtilisateur'];
            if ($emailUtilisateur){
                require_once ('connect.php');

                $result = pg_query($bddconn, "INSERT INTO Utilisateur (email, nom, prenom, entreprise, genre, pays, metier) VALUES ('$emailUtilisateur','$nomUtilisateur', '$prenomUtilisateur', '$entrepriseUtilisateur', '$genreUtilisateur', '$paysUtilisateur', '$metierUtilisateur');");
                //TODO : test de confimation d'ajout à la base
                $testExist = pg_query($bddconn, "SELECT email FROM utilisateur WHERE utilisateur.email='$emailUtilisateur';");
                $row = pg_fetch_row($testExist);
                if (!$row) {
                    echo "<br/>L'utilisateur n'a pas pu être ajouté ou existe déjà.\n";
                }
                else{
                    echo "<br/>L'utilisateur a été enregistré. Utilisez son adresse mail pour se connecter.\n";
                }
                pg_close($bddconn);        
            }
            else{
                echo "<br/>Une adresse mail est nécessaire pour s'inscrire.\n";
            }
        ?>
      </div>
  </body>
</html>

