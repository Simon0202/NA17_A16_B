<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	    <form method="GET" action="index.php">
	    	<label for="emailUtilisateur">Email : </label>
	        <input type="text" size="20" id ="emailUtilisateur" name="emailUtilisateur">
	        <input type="submit"/>
	    </form>
	    <a href="homePage.php">Page principale du dashboard - DEV</a>
    </div>
    <div id="inscriptionWidget">
     	<h2 id="leTitreModule">Inscription</h2>
	    <form method="GET" action="index.php">
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
      <?php

        $link = mysqli_connect('localhost','root','***motDePasse***','dashboard') or die('Error connecting to MySQL server.');


        $emailUtilisateur=$_GET['emailUtilisateur'];
        $emailUtilisateur=mysqli_real_escape_string($link, $emailUtilisateur);
        $nomUtilisateur=$_GET['nomUtilisateur'];
        $nomUtilisateur=mysqli_real_escape_string($link, $nomUtilisateur);
        $prenomUtilisateur=$_GET['prenomUtilisateur'];
        $prenomUtilisateur=mysqli_real_escape_string($link, $prenomUtilisateur);
        $entrepriseUtilisateur=$_GET['entrepriseUtilisateur'];
        $entrepriseUtilisateur=mysqli_real_escape_string($link, $entrepriseUtilisateur);
        $genreUtilisateur=$_GET['genreUtilisateur'];
        $genreUtilisateur=mysqli_real_escape_string($link, $genreUtilisateur);
        $paysUtilisateur=$_GET['paysUtilisateur'];
        $paysUtilisateur=mysqli_real_escape_string($link, $paysUtilisateur);
        $metierUtilisateur=$_GET['metierUtilisateur'];
        $metierUtilisateur=mysqli_real_escape_string($link, $metierUtilisateur);

        $result = mysqli_query($link, "INSERT INTO Utilisateur (email, nom, prenom, entreprise, genre, pays, metier) VALUES ('$emailUtilisateur','$nomUtilisateur', '$prenomUtilisateur', '$entrepriseUtilisateur', '$genreUtilisateur', '$paysUtilisateur','$metierUtilisateur');");

        if (! $fetch =mysqli_fetch_row($result)) {
          echo "<div>Aucun enregistrement ne correspond\n</div>";
        }
        else {
          echo"<tr>$fetch[0] $fetch[1] $fetch[2] $fetch[3] $fetch[4] $fetch[5] </tr>";
        } 
        mysql_close();
      ?>
  </body>
</html>

