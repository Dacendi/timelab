<?php

require_once 'Framework/Modele.php';

/**
 * Services liés aux sous-types de machines
 * 
 * @author Baptiste Pesquet
 */
class Soustypemachine extends Modele
{

    /**
     * Renvoie la liste des sous-types de machines 
     * 
     * @param type
     * @return type
     */
    public function getSoustypemachines()
    {
      //  $sql = "select ALB_ID as id, ALB_NOM as nom, ALB_IMAGE as image from T_ALBUM where GEN_ID=? order by ALB_NOM";
    	$sql = "select ID_sous_type_machine as id, T.ID_type_machine as ID_type_machine, libelle_type_machine, code_type_machine, code_sous_type_machine as nom,  libelle_sous_type_machine 
    			from sous_type_machines ST right join type_machines T on ST.id_type_machine = T.ID_type_machine order by code_type_machine, code_sous_type_machine";
    //	return $this->executerRequete($sql, array($idMenu));
    	return $this->executerRequete($sql );
    	
    }
    /**
     * Renvoie la liste des sous-types de machines
     *
     * @param type
     * @return type
     */
    public function getSoustypemachinesBis()
    {
    	//  $sql = "select ALB_ID as id, ALB_NOM as nom, ALB_IMAGE as image from T_ALBUM where GEN_ID=? order by ALB_NOM";
    	$sql = "select ID_sous_type_machine as id, T.ID_type_machine as ID_type_machine,  code_type_machine, code_sous_type_machine as nom,  libelle_sous_type_machine
    			from sous_type_machines ST right join type_machines T on ST.id_type_machine = T.ID_type_machine order by ID_type_machine, ID_sous_type_machine, code_sous_type_machine";
    	//	return $this->executerRequete($sql, array($idMenu));
    	return $this->executerRequete($sql );
    	 
    }

    /**
     * Renvoie un sous type machine
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getSoustypemachine($id)
    {
        $sql = "select ID_sous_type_machine as id, code_type_machine, T.ID_type_machine, libelle_type_machine, code_sous_type_machine as nom, libelle_sous_type_machine
                from sous_type_machines ST right join type_machines T on ST.id_type_machine = T.ID_type_machine where ID_sous_type_machine=?";
        $soustypemachine = $this->executerRequete($sql, array($id));
        ChromePhp::log('$id : ' , $id);
        if ($soustypemachine->rowCount() == 1) {
            return $soustypemachine->fetch();  // Accès à la première ligne de résultat
        }
        else {
            throw new Exception("Aucun sous-type machine ne correspond à l'identifiant '$id'");
        }
    }
    public function testExistStypemachine($stypeToAdd)
    {
    	// on test si le type machine existe déjà
    	$exist = false;
    	$sql = 'select ID_sous_type_machine from sous_type_machines where code_sous_type_machine=?';
    	$test = $this->executerRequete($sql, array($stypeToAdd['code_stype_machine']));
    	if ($test->rowCount() > 0) {
    		$exist = true;
    	}
    	return $exist;
    }
    public function ajouterStypemachine($stypeToAdd)
    {
    	// on crée un nouveau type de machine
    	$error= false;
    	$sql = 'insert into sous_type_machines(id_type_machine, code_sous_type_machine, libelle_sous_type_machine) values (?, ?, ?)';
    	try{
    		$this->executerRequete($sql, array($stypeToAdd['ID_type_machine'],$stypeToAdd['code_stype_machine'], $stypeToAdd['libelle_stype_machine']));
    	}catch(Exception $e){
    		$error = true;
    	}
    	return $error;
    }
}