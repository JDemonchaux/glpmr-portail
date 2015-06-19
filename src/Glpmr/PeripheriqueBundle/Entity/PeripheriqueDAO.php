<?php
namespace Glpmr\PeripheriqueBundle\Entity;
/**
 * Description of PeripheriqueDAO
 *
 * @author Jérôme
 */
class PeripheriqueDAO {
    private $table_name = "radmacadd";
    private $connexion;

    public function __construct(Connection $dbalConnection)
    {
        $this->connection = $dbalConnection;
    }
    
    public function ajouter(Peripherique $obj) {
        $sql = "Insert into " . $this->table_name . " values (``, :hostname, :description, :type, :add_mac, )"
        $stmt = $this->connection->prepare($sql);
        //$stmt->bindValue("foo", $foo);
        $stmt->execute();
    }
    
    public function modifier(Peripherique $obj) {
        
    }
    
    public function supprimer(Peripherique $obj) {
        
    }

    public function supprimerGroupe($classe)
    {
        
    }
    
    public function listerAll() {
        
    }
    
    public function listerUser() {
        
    }
}
