<?php

include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");
include ("_fin.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

$connexion=Connect();
if (!$connexion)
{
   ajouterErreur("Echec de la connexion au serveur MySql");
   afficherErreurs();
   exit();
}
if (!selectBase($connexion))
{
   ajouterErreur("La base de données festival est inexistante ou non accessible");
   afficherErreurs();
   exit();
}

// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT


echo "
<table width='80%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Etablissements</td>
   </tr>";

   $req = obtenirReqEtablissements();
   $rsEtab = $connexion->query($req);
   $lgEtab = $rsEtab->fetchALL(PDO::FETCH_ASSOC);
   // BOUCLE SUR LES ÉTABLISSEMENTS
   foreach ($lgEtab as $row) 
   {
      $nom = $row['nomEtab'];
      $id = $row['idEtab'];
      echo "<tr class='ligneTabNonQuad'>";
      echo "<td width='52%'>".$nom."</td>";
      echo "<td width='16%' align='center'> ";
      echo "<a href='detailEtablissement.php?idEtab=".$id."'>";
      echo "Voir détail</a></td>";
      echo "<td width='16%' align='center'>";
      echo "<a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=".$id."'>";
      echo "Modifier</a></td>";
      $attrib = obtenirNbOccup($connexion,$id);
      echo "<td width='16%'>Total attributions :".$attrib."</td>";
      $req=obtenirReqEtablissementsAyantChambresAttribuées();
      $rsEtab=$connexion->query($req);
      $lgEtab=$rsEtab->fetchALL(PDO::FETCH_ASSOC);
      foreach ($lgEtab as $row) 
      {
         $nbOffre=$row['nombreChambresOffertes'];
      }
      if ($attrib == $nbOffre) 
      {
         echo "<td width='16%' align='center'>";
         echo "Complet";
         echo "</td>";
      }
      else
      {
         echo "<td width='16%' align='center'>";
         echo "Non Complet";
         echo "</td>";
      }
      if (!existeAttributionsEtab($connexion, $id)) 
      {
         echo "<td width='16%' align='center'>";
         echo "<a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=".$id."'>";
         echo "Supprimer</a></td>";
      }
      else
      {
         echo "<td width='16%'>&nbsp; </td>";
      }
   }
   echo "</tr>";
   echo "<tr class='ligneTabNonQuad'>";
   echo "<td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'> Création d'un établissement</a ></td>";
      echo "</tr>";
   echo "</table>";

   /*while ($lgEtab!=FALSE)
   {
      $id=$lgEtab['idEtab'];
      $nom=$lgEtab['nomEtab'];
      echo "
      <tr class='ligneTabNonQuad'>
         <td width='52%'>$nom</td>
         
         <td width='16%' align='center'> 
         <a href='detailEtablissement.php?idEtab=$id'>
         Voir détail</a></td>
         
         <td width='16%' align='center'> 
         <a href='modificationEtablissement.php?action=demanderModifEtab&amp;idEtab=$id'>
         Modifier</a></td>";
         
         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement
         if (!existeAttributionsEtab($connexion, $id))
         {
            echo "
            <td width='16%' align='center'> 
            <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;idEtab=$id'>
            Supprimer</a></td>";
         }
         else
         {
            echo "
            <td width='16%'>&nbsp; </td>";          
         }
         echo "
      </tr>";
      $lgEtab=mysqli_fetch_array($rsEtab);
   }   
   echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'>
      Création d'un établissement</a ></td>
  </tr>
</table>";*/

?>