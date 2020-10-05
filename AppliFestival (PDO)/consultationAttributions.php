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

// CONSULTER LES ATTRIBUTIONS DE TOUS LES ÉTABLISSEMENTS

// IL FAUT QU'IL Y AIT AU MOINS UN ÉTABLISSEMENT OFFRANT DES CHAMBRES POUR  
// AFFICHER LE LIEN VERS LA MODIFICATION
$nbEtab=obtenirNbEtabOffrantChambres($connexion);
if ($nbEtab!=0) 
{
   echo "
   <table width='75%' cellspacing='0' cellpadding='0' align='center'
   <tr><td>
   <a href='modificationAttributions.php?action=demanderModifAttrib'>
   Effectuer ou modifier les attributions</a></td></tr></table><br><br>";
   
   // POUR CHAQUE ÉTABLISSEMENT : AFFICHAGE D'UN TABLEAU COMPORTANT 2 LIGNES 
   // D'EN-TÊTE ET LE DÉTAIL DES ATTRIBUTIONS
   $req=obtenirReqEtablissementsAyantChambresAttribuées();
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetchALL(PDO::FETCH_ASSOC);
   foreach ($lgEtab as $row) 
   {
      $idEtab=$row['idEtab'];
      $nomEtab=$row['nomEtab'];
      echo "<table width='75%' cellspacing='0' cellpadding='0' align='center' 
      class='tabQuadrille'>";
      $nbOffre=$row['nombreChambresOffertes'];
      $nbOccup=obtenirNbOccup($connexion, $idEtab);
      // Calcul du nombre de chambres libres dans l'établissement
      $nbChLib = $nbOffre - $nbOccup;
      // AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE 
      echo "
      <tr class='enTeteTabQuad'>
         <td colspan='2' align='left'><strong>$nomEtab</strong>&nbsp;
         (Offre : $nbOffre&nbsp;&nbsp;Disponibilités : $nbChLib)
         </td>
      </tr>"; 
      // AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE 
      echo "
      <tr class='ligneTabQuad'>
         <td width='65%' align='left'><i><strong>Nom Equipe</strong></i></td>
         <td width='35%' align='left'><i><strong>Chambres attribuées</strong></i>
         </td>
      </tr>";
      // AFFICHAGE DU DÉTAIL DES ATTRIBUTIONS : UNE LIGNE PAR Equipe AFFECTÉ 
      // DANS L'ÉTABLISSEMENT       
      $req=obtenirReqEquipesEtab($idEtab);
      $rsEquipe=$connexion->query($req);
      $lgEquipe=$rsEquipe->fetchALL(PDO::FETCH_ASSOC);
      foreach($lgEquipe as $row)
      {
         $idEquipe=$row['idEquipe'];
         $nomEquipe=$row['nomEquipe'];
         echo "
         <tr class='ligneTabQuad'>
            <td width='65%' align='left'>$nomEquipe</td>";
         // On recherche si des chambres ont déjà été attribuées à ce Equipe
         // dans l'établissement
         $nbOccupEquipe=obtenirNbOccupEquipe($connexion, $idEtab, $idEquipe);
         echo "
            <td width='35%' align='left'>$nbOccupEquipe</td>
         </tr>";
      }   
   }
   echo "</table><br>";
}
/*$nbEtab=obtenirNbEtabOffrantChambres($connexion);
if ($nbEtab!=0)
{
   echo "
   <table width='75%' cellspacing='0' cellpadding='0' align='center'
   <tr><td>
   <a href='modificationAttributions.php?action=demanderModifAttrib'>
   Effectuer ou modifier les attributions</a></td></tr></table><br><br>";
   
   // POUR CHAQUE ÉTABLISSEMENT : AFFICHAGE D'UN TABLEAU COMPORTANT 2 LIGNES 
   // D'EN-TÊTE ET LE DÉTAIL DES ATTRIBUTIONS
   $req=obtenirReqEtablissementsAyantChambresAttribuées();
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetchALL();
   // BOUCLE SUR LES ÉTABLISSEMENTS AYANT DÉJÀ DES CHAMBRES ATTRIBUÉES
   while($lgEtab!=FALSE)
   {
      $idEtab=$lgEtab['idEtab'];
      $nomEtab=$lgEtab['nomEtab'];
   
      echo "
      <table width='75%' cellspacing='0' cellpadding='0' align='center' 
      class='tabQuadrille'>";
      
      $nbOffre=$lgEtab["nombreChambresOffertes"];
      $nbOccup=obtenirNbOccup($connexion, $idEtab);
      // Calcul du nombre de chambres libres dans l'établissement
      $nbChLib = $nbOffre - $nbOccup;
      
      // AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE 
      echo "
      <tr class='enTeteTabQuad'>
         <td colspan='2' align='left'><strong>$nomEtab</strong>&nbsp;
         (Offre : $nbOffre&nbsp;&nbsp;Disponibilités : $nbChLib)
         </td>
      </tr>";
          
      // AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE 
      echo "
      <tr class='ligneTabQuad'>
         <td width='65%' align='left'><i><strong>Nom Equipe</strong></i></td>
         <td width='35%' align='left'><i><strong>Chambres attribuées</strong></i>
         </td>
      </tr>";
        
      // AFFICHAGE DU DÉTAIL DES ATTRIBUTIONS : UNE LIGNE PAR Equipe AFFECTÉ 
      // DANS L'ÉTABLISSEMENT       
      $req=obtenirReqEquipesEtab($idEtab);
      $rsEquipe=mysqli_query($connexion, $req);
      $lgEquipe=mysqli_fetch_array($rsEquipe);
               
      // BOUCLE SUR LES EquipeS (CHAQUE Equipe EST AFFICHÉ EN LIGNE)
      while($lgEquipe!=FALSE)
      {
         $idEquipe=$lgEquipe['idEquipe'];
         $nomEquipe=$lgEquipe['nomEquipe'];
         echo "
         <tr class='ligneTabQuad'>
            <td width='65%' align='left'>$nomEquipe</td>";
         // On recherche si des chambres ont déjà été attribuées à ce Equipe
         // dans l'établissement
         $nbOccupEquipe=obtenirNbOccupEquipe($connexion, $idEtab, $idEquipe);
         echo "
            <td width='35%' align='left'>$nbOccupEquipe</td>
         </tr>";
         $lgEquipe=mysqli_fetch_array($rsEquipe);
      } // Fin de la boucle sur les Equipes
      
      echo "
      </table><br>";
      $lgEtab=mysqli_fetch_array($rsEtab);
   } // Fin de la boucle sur les établissements
}*/

?>
