<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Dashboard</title>
    <style type="text/css" media="screen"></style>
  </head>
  <body>
    <a href="homePage.php">Page d'accueil</a>
    <a href="dashboard.php">Dashboard</a>
    <h1 id="leTitre">Administration</h1>
    <div id="vosFluxDePublications" style="background-color:#eae8e4ff">
    	<h2>Parcourir vos flux de publications</h2>
      <div id="listeVosFluxDePublications">
        Vos flux de publications apparaissent ici.
      </div>
      <form method="GET" action="admin.php">
        <h3>Modifier/Créer flux</h3>
        <label for="titre">Titre : </label>
        <input type="text" size="20" id ="titreFlux" name="titreFlux"><br/>
        <label for="confidentialité">Confidentialité :</label>
        <input type="text" size="20" id ="confidentialitéFlux" name="confidentialitéFlux">
        <input type="submit"/>
      </form>       
    </div>
    <div id="vosPublications" style="background-color:#eae8e4ff">
      <h2>Parcourir les publications du flux</h2>
      <div id="listeDesPublications">
        Les publications apparaissent ici.
      </div>
      <form method="GET" action="admin.php">
        <h3>Modifier/ Créer publication</h3>
        <label for="titre">Titre : </label>
        <input type="text" size="20" id ="titrePublication" name="titrePublication"><br/>
        <label for="titre">Lien : </label>
        <input type="text" size="20" id ="lienPublication" name="lienPublication">
        <input type="submit"/>
        <div id="scorePublication">
        Le score de la publication apparait ici.
        </div>
        <button>Valider/Dévalider</button>
      </form>       
    </div>
    <div id="vosGroupes">
      <h2>Parcourir vos groupes</h2>
      <div id="listeDesGroupes">
        Les groupes apparaissent ici.
      </div>
      <form method="GET" action="admin.php">
        <h3>Modifier/Créer groupe</h3>
        <label for="mailAdmin">Mail administrateur : </label>
        <input type="text" size="20" id ="mailAdmin" name="mailAdmin">
        <input type="submit"/>
      </form> 
      <div id="listeUtilisateurs">
        <h3>Liste des utilisateurs</h3>
        <div id="listeDesGroupes">
          Les utilisateurs apparaissent ici.
        </div>
        <form method="GET" action="admin.php">
        <h3>Ajouter/Supprimer utilisateurs</h3>
          <label for="mailAdmin">Mail utilisateur : </label>
          <input type="text" size="20" id ="mailAdmin" name="mailAdmin">
          <input type="submit"/>
        </form>        
      </div>
    </div>
    <?php

      $link = mysqli_connect('localhost','root','MOTDEPASSE','dashboard') or die('Error connecting to MySQL server.');


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

