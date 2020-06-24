<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
// header('content-type:application/json');
require './include/connexion.php';

// Connection to database
try {
  $db = new PDO("mysql:host=$hote; dbname=$base;", $utilisateur, $motdepasse);
  $db->query('SET NAMES utf8');
} catch (PDOException $e) {
  echo "Erreur!: " . $e->getMessage() . "<br/>";
  die();
}

// Write mode
if($_GET["action"] == "write"){
  
  $message = "";
  
  // Insertion of data from Wikidata
  
  //  Check if potential name is in "personne" table
  $sqlCheckPersonne = "SELECT COUNT(id) AS nombre_personne FROM `personne` WHERE id_wikidata = :id_wikidata GROUP BY id_wikidata " ;
  $requestCheckPersonne = $db -> prepare($sqlCheckPersonne);
  $attributesCheckPersonne = array(
    ':id_wikidata' => $_GET['id_wikidata']);
    $requestCheckPersonne -> execute($attributesCheckPersonne);
    
    $dataCheckPersonne = $requestCheckPersonne -> fetch();
    
    
    
    //  Check if 1 or more name appear in the "Personne" table
    if(intval($dataCheckPersonne['nombre_personne'])>= 1){
      
      // If true, this message will be return
      //  $message = "Person already found in table";
      $message = '{"message":"Alias already found in table"}';
      
      $newPerson = false;
    }else{
      
      // If false, the data are stocked in database
      
      // First in "personne" table
      $sqlInsertWritePersonne = "INSERT INTO `personne` (`id_wikidata`, `alias`, `nom_complet`, `genderLabel`, `personDescription`, `sitelink`, `lemma`,`picture`) VALUES (:id_wikidata, :alias, :nom_complet, :genderLabel, :personDescription, :sitelink, :lemma, :picture)";
      $requestInsertPersonne = $db -> prepare($sqlInsertWritePersonne);
      $attributesPersonne = array(
        ':id_wikidata' => $_GET['id_wikidata'], 
        ':alias' => $_GET['alias'], 
        ':nom_complet' => $_GET['nom_complet'], 
        ':genderLabel' => $_GET['genderLabel'], 
        ':personDescription' => $_GET['personDescription'], 
        ':sitelink' => $_GET['sitelink'], 
        ':lemma' => $_GET['lemma'],
        ':picture' => $_GET['picture']);
        $requestInsertPersonne -> execute($attributesPersonne);
        $newPerson = true;
      }
      
      //  Retrieve id from "personne"
      $sqlCheckPersonne = "SELECT id FROM `personne` WHERE id_wikidata = :id_wikidata" ;
      $requestCheckPersonne = $db -> prepare($sqlCheckPersonne);
      $attributesCheckPersonne = array(
        ':id_wikidata' => $_GET['id_wikidata']);
        $requestCheckPersonne -> execute($attributesCheckPersonne);
        
        $dataCheckPersonne = $requestCheckPersonne -> fetch();
        
        $idPersonne = $dataCheckPersonne['id'];
        
        if($newPerson){
          //  And this message will be returned
          // $message =  "New person #'.$idPersonne.' in the table";
          $message =  '{"message":"New person #'.$idPersonne.' in the table"}';
        }
        
        
        
        //  Check if potential name is in "alias" table
        $sqlCheckAlias = "SELECT COUNT(id) AS nombre_alias FROM `alias` WHERE nom_potentiel = :nom_potentiel GROUP BY nom_potentiel" ;
        $requestCheckAlias = $db -> prepare($sqlCheckAlias);
        $attributesCheckAlias = array(
          ':nom_potentiel' => $_GET['nom_potentiel']);
          $requestCheckAlias -> execute($attributesCheckAlias);
          
          $dataCheckAlias = $requestCheckAlias -> fetch();
          
          //  Check if 1 or more name appear in the "alias" table
          if(intval($dataCheckAlias['nombre_alias']) >= 1){
            // If true, this message will be return
            // $message .= ". Alias already found in table";
            $message .= '{"message":". Alias already found in table"}';
            $newAlias = false;
          }else{
            // In "alias" table
            $sqlInsertWriteAlias = "INSERT INTO `alias` (`id`,`nom_potentiel`) VALUES (NULL, :nom_potentiel)";
            $requestInsertAlias = $db -> prepare($sqlInsertWriteAlias);
            $attributeAlias = array(
              ':nom_potentiel' => $_GET['nom_potentiel']);     
              $requestInsertAlias -> execute($attributeAlias);
              $newAlias = true;
            }
            
            
    //  Retrieve id from "alias"
    $sqlCheckAlias = "SELECT id FROM `alias` WHERE nom_potentiel = :nom_potentiel" ;
    $requestCheckAlias = $db -> prepare($sqlCheckAlias);
    $attributesCheckAlias = array(
      ':nom_potentiel' => $_GET['nom_potentiel']);
      $requestCheckAlias -> execute($attributesCheckAlias);
      
      $dataCheckAlias = $requestCheckAlias -> fetch();
      
      $idAlias = $dataCheckAlias['id'];
      
      if($newAlias){
        //  And this message will be returned
        if(strlen($message>0)){
          $message.='. ';
        }
        //  $message .='New alias'.$idAlias.' in the table';
        $message .='{"message":"New alias'.$idAlias.' in the table"}';
      }
      
      
      //  Check if potential name is in "correspondance" table
      $sqlCheckCorresp = "SELECT COUNT(id) AS nombre_correspondance FROM `correspondance` WHERE id_alias = :id_alias AND id_personne = :id_personne GROUP BY id_alias, id_personne " ;
   $requestCheckCorresp = $db -> prepare($sqlCheckCorresp);
   $attributesCheckCorresp = array(
     ':id_personne' => $idPersonne,
     ':id_alias' => $idAlias);
   $requestCheckCorresp -> execute($attributesCheckCorresp);
   
   $dataCheckCorresp = $requestCheckCorresp -> fetch();
   
   //  Check if 1 or more name appear in the "alias" table
   if(intval($dataCheckCorresp['nombre_correspondance'])>= 1){
     
     // If true, this message will be return
     //  $message .= ". Correspondance already found in table";
     $message .= '{"message":". Correspondance already found in table"}';
     $newCorresp = false;

    }else{
   //  Add in "correspondance" table
   $sqlInsertCorresp = "INSERT INTO `correspondance` (`id_personne`, `id_alias`) VALUES (:id_personne, :id_alias)";
   $requestInsertCorresp = $db -> prepare($sqlInsertCorresp);
   $attributesCorresp = array(
     ':id_personne' => $idPersonne,
     ':id_alias' => $idAlias);
     $requestInsertCorresp -> execute($attributesCorresp);
     
    }
    
    echo $message;
    
  }
  
  // Read mode
  else if($_GET["action"] == "read"){
    header('content-type:application/json');
    
    $nomPotentiel = $_GET['nom_potentiel'];
    
    //  Check if the name is in the database
    $sqlCheckNameInAlias = "SELECT * FROM alias, correspondance, personne WHERE nom_potentiel = :nom_potentiel AND correspondance.id_alias = alias.id AND correspondance.id_personne = personne.id";
    $requestCheckNameInAlias = $db->prepare($sqlCheckNameInAlias);
    $attributesCheckAlias = array(
      ':nom_potentiel' => $_GET['nom_potentiel']);
      $requestCheckNameInAlias -> execute($attributesCheckAlias);
      
      $dataCheckNameInDbAlias = $requestCheckNameInAlias -> fetchAll();

   echo (json_encode($dataCheckNameInDbAlias, JSON_UNESCAPED_UNICODE));

 }

 //  Contribution mode
 else if($_GET["action"] == "contribution"){
   $sendFrom = htmlspecialchars($_GET['sender']);
   $subject = 'Contribution de '.$sendFrom.' pour la ville de ';
   $message = htmlspecialchars($_GET['message']) ;
   $to = 'newsletter@alanakra.fr';
   mail($to,$subject,$message,'From:'.$sendFrom.'');
 }
  /**url write mode
  *http://localhost/plaquesdumatrimoine/proto_front/api.php?action=write&id_wikidata=&alias=&nom_complet=&genderLabel=&personDescription=&sitelink=&lemma=&nom_potentiel=&picture= 
  *
  *
  *http://localhost/plaquesdumatrimoine/proto_front/api.php?action=write&id_wikidata=Q535&alias=Victor%20Hugo&genderLabel=masculin&personDescription=%C3%A9crivain,%20po%C3%A8te%20et%20homme%20politique%20fran%C3%A7ais&sitelink=https://fr.wikipedia.org/wiki/Victor_Hugo&lemma=Victor%20Hugo&nom_potentiel=Test&nom_complet=Victor Hugo&picture=https://upload.wikimedia.org/wikipedia/commons/e/e6/Victor_Hugo_by_%C3%89tienne_Carjat_1876_-_full.jpg
  */ 

  /**url read mode
   * http://localhost/plaquesdumatrimoine/proto_front/api.php?action=read&nom_potentiel=
   * http://localhost/plaquesdumatrimoine/proto_front/api.php?action=read&nom_potentiel=Gustave Eiffel
   */

   /**url contribution mode
    * http://localhost/plaquesdumatrimoine/proto_front/api.php?action=contribution&sender=&message=
    */
?>