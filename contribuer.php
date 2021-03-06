<!DOCTYPE html>
<html lang="fr">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Plaques du Matrimoine | Proposition de lieu</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <?php require 'include/__navbar.php' ?>
 <div class="container-contribution">
  <h1>Proposition d'un nouveau lieu</h1>
  <p>Vous souhaitez proposer un nom de lieu non répertorié ? N'hésitez pas, nous vous répondrons le plus rapidement
   possible !</p>
   <form action="sendmail-contribution.php" method="post">
   <p>Tous les champs sont <strong>obligatoires</strong></p>

   <label for="cityname">Ville</label> <br>
   <input type="text" name="cityname" required> <br><br>

   <label for="departement">Département</label> <br>
   <input list="list-departement" name="departement" id="departement" class="choose-dpt" value="Merci de sélectionner un département" required> <br><br>
   <datalist id="list-departement">
           <option value="01 Ain">
           <option value="02 Aisne">
           <option value="03 Allier">
           <option value="04 Alpes-de-Haute-Provence">
           <option value="05 Hautes-Alpes">
           <option value="06 Alpes-Maritimes">
           <option value="07 Ardèche">
           <option value="08 Ardennes">
           <option value="09 Ariège">
           <option value="10 Aube">
           <option value="11 Aude">
           <option value="12 Aveyron">
           <option value="13 Bouches-du-Rhône">
           <option value="14 Calvados">
           <option value="15 Cantal">
           <option value="16 Charente">
           <option value="17 Charente-Maritime">
           <option value="18 Cher">
           <option value="19 Corrèze">
           <option value="2A Corse-du-Sud">
           <option value="2B Haute-Corse">
           <option value="21 Côte-d’Or">
           <option value="22 Côtes-d'Armor">
           <option value="23 Creuse">
           <option value="24 Dordogne">
           <option value="25 Doubs">
           <option value="26 Drôme">
           <option value="27 Eure">
           <option value="28 Eure-et-Loir">
           <option value="29 Finistère">
           <option value="30 Gard">
           <option value="31 Haute-Garonne">
           <option value="32 Gers">
           <option value="33 Gironde">
           <option value="34 Hérault">
           <option value="35 Ille-et-Vilaine">
           <option value="36 Indre">
           <option value="37 Indre-et-Loire">
           <option value="38 Isère">
           <option value="39 Jura">
           <option value="40 Landes">
           <option value="41 Loir-et-Cher">
           <option value="42 Loire">
           <option value="43 Haute-Loire">
           <option value="44 Loire-Atlantique">
           <option value="45 Loiret">
           <option value="46 Lot">
           <option value="47 Lot-et-Garonne">
           <option value="48 Lozère">
           <option value="49 Maine-et-Loire">
           <option value="50 Manche">
           <option value="51 Marne">
           <option value="52 Haute-Marne">
           <option value="53 Mayenne">
           <option value="54 Meurthe-et-Moselle">
           <option value="55 Meuse">
           <option value="56 Morbihan">
           <option value="57 Moselle">
           <option value="58 Nièvre">
           <option value="59 Nord">
           <option value="60 Oise">
           <option value="61 Orne">
           <option value="62 Pas-de-Calais">
           <option value="63 Puy-de-Dôme">
           <option value="64 Pyrénées-Atlantiques">
           <option value="65 Hautes-Pyrénées">
           <option value="66 Pyrénées-Orientales">
           <option value="67 Bas-Rhin">
           <option value="68 Haut-Rhin">
           <option value="69 Rhône">
           <option value="70 Haute-Saône">
           <option value="71 Saône-et-Loire">
           <option value="72 Sarthe">
           <option value="73 Savoie">
           <option value="74 Haute-Savoie">
           <option value="75 Paris">
           <option value="76 Seine-Maritime">
           <option value="77 Seine-et-Marne">
           <option value="78 Yvelines">
           <option value="79 Deux-Sèvres">
           <option value="80 Somme">
           <option value="81 Tarn">
           <option value="82 Tarn-et-Garonne">
           <option value="83 Var">
           <option value="84 Vaucluse">
           <option value="85 Vendée">
           <option value="86 Vienne">
           <option value="87 Haute-Vienne">
           <option value="88 Vosges">
           <option value="89 Yonne">
           <option value="90 Territoire de Belfort">
           <option value="91 Essonne">
           <option value="92 Hauts-de-Seine">
           <option value="93 Seine-Saint-Denis">
           <option value="94 Val-de-Marne">
           <option value="95 Val-d’Oise">
         </datalist>

   <label for="nom">Nom du lieu</label><br>
   <input type="text" name="nom" id="nom" required aria-required="true"><br><br>

   <label for="topic">Type de lieu</label><br>
   <input type="text" name="topic" id="topic" required aria-required="true"><br><br>

   <label for="email">Votre adresse e-mail</label> <br>
   <input type="email" name="sender" id="email" required aria-required="true"> <br><br>

   <div class="choose-gender">
   <p>Merci de définir le genre de la personne</p>
    <div>
     <input id="féminin" type="radio" name="gender" id="" value="féminin" required aria-required="true">
     <label id="féminin" for="féminin">Féminin</label>
    </div>

    <div>
     <input id="femme transgenre" type="radio" name="gender" id="femme transgenre" value="femme transgenre" aria-required="true">
     <label for="femme transgenre">Femme transgenre</label>
    </div>

    <div>
     <input id="masculin" type="radio" name="gender" id="masculin" value="masculin">
     <label for="masculin">Masculin</label>
    </div>

    <div>
     <input id="homme transgenre" type="radio" name="gender" id="" value="masculin">
     <label for="homme transgenre">Homme transgenre</label>
    </div>
   </div>

   <label for="message">Informations sur la personne</label><br>
   <textarea name="message" id="" cols="30" rows="10" placeholder="" id="message" required aria-required="true"></textarea> <br>

   <label for="result">8 + 9</label><br>
   <input type="number" name="result" id="result" required aria-required="true"><br>

   <button type="submit">Envoyer</button>
  </form>
 </div>
 <?php require 'include/__footer.php' ?>
</body>

</html>