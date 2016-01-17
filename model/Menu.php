<?php

require_once 'Framework/Modele.php';

/**
 * Services liés aux Menus
 *  *
 * @author Baptiste Pesquet
 */
class Menu extends Modele
{
    /**
     * Renvoie le menu
     * 
     * @return type
     */

	
    public function getMenus()
    {
        $sql = "select ID_menu as id, libelle_menu, ordre_menu, code_menu  " .
                "from menus where affichage = 1 order by ordre_menu";
        return $this->executerRequete($sql);
        
    }

    /**
     * Renvoie les infos sur un menu
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getMenu($id)
    {
    	
      //  $sql = "select ID_menu as id, libelle_menu from menus where ID_menu=?";
        $sql = "select code_menu, libelle_menu from menus where code_menu=?";
        $menu = $this->executerRequete($sql, array($id));
      //  $menu = $this->executerRequete($sql, array($codeMenu));
        ChromePhp::log('$menu : ' , $menu);
        ChromePhp::log('$id : ' , $id);
        if ($menu->rowCount() == 1) {
            return $menu->fetch();  // Accés à la première ligne de résultat
        }
        else {
            throw new Exception("La page '$id' est introuvable !");
        }
    }
    


}

