<?php

require_once 'Framework/Modele.php';

/**
 * Services liés aux albums
 * 
 * @author Baptiste Pesquet
 */
class Marque extends Modele
{

    /**
     * Renvoie la liste des fournisseur 
     * 
     * @param 
     * @return type
     */
    public function getMarquesPays()
    {
      //  Fabricants classés par "pays", "libellé court" 
    	$sql = "select ID_marque as id, libelle_court_marque as nom, ad_website, ad_pays from marques order by ad_pays, libelle_court_marque";
    	return $this->executerRequete($sql );
    	
    }
    public function getMarques()
    {
    	//  Fabricants classés par "libellé court" 
    	$sql = "select ID_marque as id, libelle_court_marque as nom from marques order by libelle_court_marque";
    	return $this->executerRequete($sql );
    	 
    }

    /**
     * Renvoie un fournisseur
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getMarque($id) // Renvoie un fournisseur donné...
    {
        $sql = "select ID_marque as id, libelle_court_marque as nom, libelle_long_marque, ad_1, ad_2, ad_num, ad_rue, ad_cp, ad_ville,
        		 ad_pays, ad_website, contact1, contact2, phone1, phone2, ad_mail1, ad_mail2
                from marques where ID_marque=?";
        $marque = $this->executerRequete($sql, array($id));
        ChromePhp::log('$id : ' , $id);
        if ($marque->rowCount() == 1) {
            return $marque->fetch();  // Accès à la première ligne de résultat
        }
        else {
            throw new Exception("Aucune marque ne correspond à l'identifiant '$id'");
        }
    }
    
    /**
     * Test l'existence d'un fabricant par son libelle court
     *
     * @param type $libelle_court_marque
     * @return type
     * @throws Exception
     */
    public function testExistMarque($marqueToAdd)
    {
    	// on test si le fournisseur existe déjà
    	$exist = false;
    	$sql = 'select ID_marque from marques where libelle_court_marque=?';
    	$test = $this->executerRequete($sql, array($marqueToAdd['libelle_court_marque']));
    	if ($test->rowCount() > 0) {
    		$exist = true;
    	}
    	return $exist;
    }
    public function ajouterMarque($marqueToAdd)
    {
    	// on crée un nouveau fournisseur
    	$error= false;
    	$sql = 'insert into marques(libelle_court_marque,libelle_long_marque,ad_1, ad_2,ad_num, ad_rue, 
		ad_cp, ad_ville, ad_etat, ad_pays,ad_website, contact1, contact2, phone1, phone2, ad_mail1, ad_mail2
    	) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    	try{
    		$this->executerRequete($sql, array($marqueToAdd['libelle_court_marque'], $marqueToAdd['libelle_long_marque'],
				$marqueToAdd['ad_1'], $marqueToAdd['ad_2'],$marqueToAdd['ad_num'], $marqueToAdd['ad_rue'],
				$marqueToAdd['ad_cp'], $marqueToAdd['ad_ville'],$marqueToAdd['ad_etat'],$marqueToAdd['ad_pays'], $marqueToAdd['ad_website'],
				$marqueToAdd['contact1'], $marqueToAdd['contact2'],$marqueToAdd['phone1'], $marqueToAdd['phone2'],
				$marqueToAdd['ad_mail1'], $marqueToAdd['ad_mail2']));
    	}catch(Exception $e){
    		$error = true;
    	}
    	return $error;
    }
}