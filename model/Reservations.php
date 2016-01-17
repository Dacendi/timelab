<?php

require_once 'Framework/Modele.php';

/**
 * Services liés aux réservations
 * 
 * @author Baptiste Pesquet
 */
class Reservation extends Modele
{

    /**
     * Renvoie la liste des réservations
     * 
     * @param 
     * @return type
     */
    public function getReservations()
    {
      //  $sql = "select ALB_ID as id, ALB_NOM as nom, ALB_IMAGE as image from T_ALBUM where GEN_ID=? order by ALB_NOM";
   /** 	$sql = "select ID_resa as id, R.id_machine, type_resa, frequence_resa, date_deb_resa, date_fin_resa, 
    			commentaire_resa, id_membre from reservations R join machines M on R.id_machine = M.ID_machine 
    			left join membres MB on R.id_membre = MB_ID_membre order by date_deb_resa"; **/
    	$sql = "select ID_resa as id, R.id_machine, type_resa, frequence_resa, date_deb_resa, date_fin_resa,
    			commentaire_resa from reservations R join machines M on R.id_machine = M.ID_machine
    			order by date_deb_resa";
    	return $this->executerRequete($sql );
    	
    }

    /**
     * Renvoie une reservation
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getReservation($id)
    {
        $sql = "select ID_machine as id, code_machine as nom,
                TYP.ID_type_machine as idTypeMachine, TYP.libelle_type_machine, ST.ID_sous_type_machine, libelle_sous_type_machine , libelle_machine, image_machine as image,
                date_entree as date, commentaire, lien_consigne as consigne, M.ID_marque , M.libelle_long_marque as marque
                from machines A left join type_machines TYP on A.id_type_machine=TYP.ID_type_machine
                left join sous_type_machines ST on A.id_sous_type_machine=ST.ID_sous_type_machine left join marque M on A.id_marque = M.ID_marque
                where ID_machine=?";
        $reservation = $this->executerRequete($sql, array($id));
        ChromePhp::log('$id : ' , $id);
        if ($reservation->rowCount() == 1) {
            return $reservation->fetch();  // Accès à la première ligne de résultat
        }
        else {
            throw new Exception("Aucune machine ne correspond à l'identifiant '$id'");
        }
    }
    public function testExistReservation($machineToAdd)
    {
    	// on test si le type machine existe déjà
    	$exist = false;
    	$sql = 'select ID_machine libelle_machine from machines where code_machine=?';
    	$test = $this->executerRequete($sql, array($machineToAdd['code_machine']));
    	if ($test->rowCount() > 0) {
    		$exist = true;
    	}
    	return $exist;
    }
    public function ajouterReservation()
    {
    	// on crée une nouvelle machine
    	ChromePhp::log('$machineToAdd (Machines) : ', $machineToAdd );
    	$error= false;
    	$sql = 'insert into machines(code_machine,libelle_machine,image_machine, id_type_machine,id_sous_type_machine, id_marque,
		serial, date_entree, commentaire, lien_consigne
    	) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    	try{
    		$this->executerRequete($sql, array($machineToAdd['code_machine'], $machineToAdd['libelle_machine'],
    				$machineToAdd['image_machine'], $machineToAdd['id_type_machine'],$machineToAdd['id_sous_type_machine'], $machineToAdd['id_marque'],
    				$machineToAdd['serial'], $machineToAdd['date_entree'],$machineToAdd['commentaire'],$machineToAdd['lien_consigne']));
    	}catch(Exception $e){
    		$error = true;
    		ChromePhp::log('$machineToAdd (erreur) : ', $e );
    	}
    	return $error;
    }
}