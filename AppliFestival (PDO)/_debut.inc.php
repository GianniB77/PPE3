<?php
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<!-- TITRE ET MENUS -->
<html lang="fr">
<head>
<title>Festival</title>
<meta http-equiv="Content-Language" content="fr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="cssGeneral.css" rel="stylesheet" type="text/css">
</head>
<body class="basePage">        
<!--  Tableau contenant le titre -->
<table width="100%" cellpadding="0" cellspacing="0">
   <tr> 
      <td class="titre">
      <img src="IMAGE/Logo.png" alt="Logo du site" id="Logo"/>
      <br>
      Festival de sports de nature <br>
      <span id="texteNiveau2" class="texteNiveau2">
      H&eacute;bergement des participants</span><br>&nbsp;
      </td>
   </tr>
</table>
<!--  Tableau contenant les menus -->
<table width="100%" cellpadding="0" cellspacing="0" class="tabMenu" align="center">
   <tr>
      <td class="menu"><a href="index.php" class="fe">Accueil</a></td>
      <td class="menu"><a href="listeEtablissements.php" class="fe">
      Gestion établissements</a></td>
      <td class="menu"><a href="consultationAttributions.php" class="fe">
      Attributions chambres</a></td>
   </tr>
</table>
<br>';