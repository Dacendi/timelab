<?php

require_once 'Framework/Modele.php';

/**
 * Services liés aux types de machines
 * 
 * @author Baptiste Pesquet, Sébastien BAZAUD (alias Dacendi)
 */
class Typemachine extends Modele
{

    /**
     * Renvoie la liste des type de machines 
     * 
     * @param type $idGenre (idMenu)
     * @return type
     */
    public function getTypemachines()
    {
    	$sql = "select ID_type_machine as id, code_type_machine as nom, libelle_type_machine from type_machines order by code_type_machine";
    	return $this->executerRequete($sql);
    	ChromePhp::log('$this -> requete : ' , $this->executerRequete($sql));
    	
    }

    /**
     * Renvoie un type machine
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getTypemachine($id)
    {
        $sql = "select ID_type_machine as id, code_type_machine as nom, libelle_type_machine
                from type_machines where ID_type_machine=?";
        $typemachine = $this->executerRequete($sql, array($id));
        ChromePhp::log('$id : ' , $id);
        if ($typemachine->rowCount() == 1) {
            return $typemachine->fetch();  // Accès à la première ligne de résultat
        }
        else {
            throw new Exception("Aucun type machine ne correspond à l'identifiant '$id'");
        }
    }
    public function testExistTypemachine($typeToAdd)
    {
    	// on test si le type machine existe déjà
    	$exist = false;
    	$sql = 'select ID_type_machine from type_machines where code_type_machine=?';
    	$test = $this->executerRequete($sql, array($typeToAdd['code_type_machine']));
    	if ($test->rowCount() > 0) {
    		$exist = true;
    	}
    	return $exist;
    }
    public function ajouterTypemachine($typeToAdd)
    {
    	// on crée un nouveau type de machine
    	$error= false;
    	$sql = 'insert into type_machines(code_type_machine, libelle_type_machine) values (?, ?)';
    	try{
    	$this->executerRequete($sql, array($typeToAdd['code_type_machine'], $typeToAdd['libelle_type_machine']));
    	}catch(Exception $e){
    		$error = true;
    	}
    	return $error;
    }

}