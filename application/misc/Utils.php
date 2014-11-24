<?php



final class Misc_Utils {
    
    
    static public $moisAnnee = array(
            1 => 'Janvier', 2 => 'Fevrier', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
            7 => 'Juillet', 8 => 'Aout', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre'
    );
        
     

    private function __construct() {
    }

    # Entites OCIT
    public static function getEntite($entite='') {
        $list = array(1=>'OCI', 2=>'CIT', 3=>'CI2M');
        if($entite)
            return $list[$entite];
        return $list;
        
    }
    
    # Present Absent
    public static function getStatutPresence($statut='') {
        $list = array(1=>'Présent', 0=>'Absent');
        if($statut)
            return $list[$statut];
        return $list;
    }
    
    # Retourne les mois de l'annee
    public static function getMoisAnnee($mois = '')  {
        $s = ($mois == '') ? self::$moisAnnee : self::$moisAnnee[$mois];
        return $s; 
    }	
    
     # Type de cabinet
    public static function getNationaliteOrganisme($nationalite='') {
        $list = array(1=>'Ivoirien agrée FDFP', 2=>'Ivoirien non agrée FDFP',3=>'Etranger');
        if($nationalite)
            return $list[$nationalite];
        return $list;
    }
    
    
    public static function getCategorie() {
        $tab = array(
                'Cadres'=>array(1,2,3,5,7,9,10,11),
                'Maitrises'=>array(4),
                'Employes'=>array(8)
            );
        return $tab;
    }
    
    
    
    public static function getPerex($text) {
        if (mb_strlen($text) > 200) {
            $firstPor = mb_substr($text, 0, 200);
            $space = mb_strrpos($firstPor, " ");
            return mb_substr($firstPor, 0, $space) . " ...";
        }
        return $text;
    }
    
    
    
    public static function formatDateToFr($date) {
        return date('d.m.Y',strtotime($date));
    }

    /**
     * @return ArrayObject
     */
    public static function getFavorites() {
        $session = new Zend_Session_Namespace('favorites');
        if ($session->favorites === null) {
            $session->setExpirationSeconds(2592000);
            $session->favorites = new ArrayObject(array());
        }
        return $session->favorites;
    }

    public static function addToFavorites($id) {
        if (is_numeric($id)) {
            self::getFavorites()->offsetSet($id, $id);
        }
    }

    public static function removeFromFavorites($id) {
        if (self::isInFavorites($id)) {
            self::getFavorites()->offsetUnset($id);
        }
    }

    public static function isInFavorites($id) {
        if (is_numeric($id)) {
            return self::getFavorites()->offsetExists($id);
        }
        return false;
    }

}

