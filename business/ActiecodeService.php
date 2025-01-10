<?php
declare(strict_types=1);
require_once('data/autoloader.php');
class ActiecodeService
{
    public function getActieCode($naam)
    {
        $actiecodeDAO = new ActiecodeDAO;
        return $actiecodeDAO->getActieCodeByNaam($naam);
    }

    public function valideerActieCode(Actiecode $actiecode_object) : bool 
    {

        $today = date('Y-m-d');
        $today = date('Y-m-d', strtotime($today));
        
        $geldigVanDatum = date('Y-m-d', strtotime($actiecode_object->getActiecodeGeldigVanDatum()));
        $geldigTotDatum = date('Y-m-d', strtotime($actiecode_object->getActiecodeGeldigTotDatum()));
            
        if (($today >= $geldigVanDatum) && ($today <= $geldigTotDatum)){
            return true;
        }else{
            return false;
        }

    }

    public function verwijderActiecode(Actiecode $actiecode) {
        $actiecodeDAO = new ActiecodeDAO();
        $actiecodeDAO->verwijderActiecode($actiecode);
    }

}