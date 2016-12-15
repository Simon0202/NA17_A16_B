<?php
        $link = mysqli_connect('localhost','root','qzekop489','dashboard') or die('Error connecting to MySQL server.');


        $emailUtilisateur=$_GET['emailUtilisateur'];
        $result = mysqli_query($link, "INSERT INTO Utilisateur (email, nom, prenom, entreprise, genre, pays, metier) VALUES ('$emailUtilisateur,$nomUtilisateur, $prenomUtilisateur, $entrepriseUtilisateur, $genreUtilisateur, $paysUtilisateur,$metierUtilisateur')");

        if (! $fetch =mysqli_fetch_row($result)) {
          echo "<div>Aucun enregistrement ne correspond\n</div>";
        }
        /*else {
          $i=0;
           while ($fetched=mysqli_fetch_row($result)){
              echo "<tr>";
              echo "<td>Session $fetched[$i]</td>";
              echo "</tr>";
              test lou/
          }
        }*/
        else {
          echo"<tr>$fetch[0]$fetch[1]$fetch[2]</tr>";
        } 
        mysql_close();
?>
