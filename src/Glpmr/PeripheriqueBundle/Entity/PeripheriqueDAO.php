<?php
namespace Glpmr\PeripheriqueBundle\Entity;

use Glpmr\AuthentificationBundle\Entity\CustomError;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of PeripheriqueDAO
 *
 * @author Jérôme
 */
class PeripheriqueDAO
{
    private $table_name = "radmacadd";
    private $connexion;
//    private $url_pfsense = "https://172.16.254.254/json-autoconfig/push.php";
    private $url_pfsense = "http://localhost";

    public function __construct($dbalConnection)
    {
        $this->connection = $dbalConnection;
    }

    public function ajouter(Peripherique $obj)
    {
        $sql = "Insert into " . $this->table_name . " values (:id, :hostname, :description, :type, :add_mac, :add_ip, :octet1, :octet2, :octet3, :octet4, :proprietaire, :proprietaire_classe)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $obj->getId());
        $stmt->bindValue("hostname", $obj->getHostname());
        $stmt->bindValue("description", $obj->getDescription());
        $stmt->bindValue("type", $obj->getType());
        $stmt->bindValue("add_mac", $obj->getAdd_mac());
        $stmt->bindValue("add_ip", $obj->getAdd_ip());
        $stmt->bindValue("octet1", $obj->getOctet1());
        $stmt->bindValue("octet2", $obj->getOctet2());
        $stmt->bindValue("octet3", $obj->getOctet3());
        $stmt->bindValue("octet4", $obj->getOctet4());
        $stmt->bindValue("proprietaire", $obj->getProprietaire());
        $stmt->bindValue("proprietaire_classe", $obj->getProprietaire_classe());
        $stmt->execute();
    }

    public function modifier(Peripherique $obj)
    {
        // Avant de modifier, il faut recupere o1, o2 et o3 pour reconstruire l'adresse ip à la main
        $sql = "SELECT o1, o2, o3 FROM " . $this->table_name . " WHERE `add_mac`=:add_mac";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("add_mac", $obj->getAdd_mac());
        $stmt->execute();
        $ips = $stmt->fetchAll();
        $obj->setAdd_ip($ips[0]["o1"] . "." . $ips[0]["o2"] . "." . $ips[0]["o3"] . "." . $obj->getOctet4());

        $sql = "UPDATE " . $this->table_name . " SET `hostname`=:hostname, `description`=:description, `type`=:type, `add_mac`=:add_mac, `add_ip`=:add_ip,
        `proprietaire`=:proprietaire, `proprietaire_classe`=:proprietaire_classe WHERE `id` = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $obj->getId());
        $stmt->bindValue("hostname", $obj->getHostname());
        $stmt->bindValue("description", $obj->getDescription());
        $stmt->bindValue("type", $obj->getType());
        $stmt->bindValue("add_mac", $obj->getAdd_mac());
        $stmt->bindValue("add_ip", $obj->getAdd_ip());
        $stmt->bindValue("proprietaire", $obj->getProprietaire());
        $stmt->bindValue("proprietaire_classe", $obj->getProprietaire_classe());
        $stmt->execute();
    }

    public function supprimer(Peripherique $obj)
    {
        $sql = "Delete FROM " . $this->table_name . " where `id` = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $obj->getId());
        $stmt->execute();

    }

    public function supprimerGroupe($classe)
    {
        $sql = "DELETE FROM " . $this->table_name . " WHERE `proprietaire_classe` = :promotion";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('promotion', $classe);
        $stmt->execute();
    }

    public function listerAll()
    {

    }

    public function listerOne(Peripherique $obj)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE `id` = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $obj->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function listerUser()
    {
        $session = new Session();
        $username = $session->get('username');
        $sql = "SELECT * FROM " . $this->table_name . " WHERE `proprietaire` = :proprietaire";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("proprietaire", $username);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function countMac($add)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE `add_mac` = :add_mac";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("add_mac", $add);
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
    }

    public function rechercheAdmin($username, $promotion, $add_ip, $add_mac)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE `proprietaire` = :proprietaire OR `proprietaire_classe` = :promotion OR `add_mac` = :add_mac OR `add_ip` = :add_ip";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("proprietaire", $username);
        $stmt->bindValue("promotion", $promotion);
        $stmt->bindValue("add_mac", $add_mac);
        $stmt->bindValue("add_ip", $add_ip);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTroisiemeOctet()
    {
        $session = new Session();
        $sql = "SELECT max(o3) FROM " . $this->table_name . " WHERE `proprietaire` = :proprietaire";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("proprietaire", $session->get('username'));
        $stmt->execute();
        $octet = $stmt->fetchAll();

        if (NULL == $octet[0]["max(o3)"]) {
            // L'utilisateur n'a pas encore de numéro étudiant, alors on va chercher le dernier octet et on l'incrémente de 1
            $sql = "SELECT max(o3) FROM " . $this->table_name . " WHERE `proprietaire_classe` = :promotion";
            $stmt = $this->connection->prepare($sql);
//            $stmt->bindValue("proprietaire", $session->get('username'));
            $stmt->bindValue("promotion", $session->get("promotion"));
            $stmt->execute();

            $octet = $stmt->fetchAll();


            if (NULL == $octet[0]["max(o3)"]) {
                $octet = 1;
            } else {
                $octet = $octet[0]["max(o3)"] + 1;
            }
        } else {
            $octet = $octet[0]["max(o3)"];
        }
        return $octet;
    }

    /**
     * Fonction qui récupère les IP disponibles pour l'étudiant
     * @return : $octet, tableau des IP dispos (tableau de 1 à 254 - celles déjà inscrites en base)
     */
    public function getIps($username)
    {
        $sql = "SELECT o4 FROM " . $this->table_name . " WHERE `proprietaire` = :proprietaire";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("proprietaire", $username);
        $stmt->execute();
        $ip_use = $stmt->fetchAll();

        $ips = array();
        foreach ($ip_use as $ip) {
            array_push($ips, $ip['o4']);
        }

        $ip_dispo = array();
        for ($i = 1; $i < 255; $i++) {
            array_push($ip_dispo, $i);
        }

        $ip_dispo = array_diff($ip_dispo, $ips);

        return $ip_dispo;

    }


    /**
     * Export mysql database to JSON
     * Pour inscription dans pfsense
     */
    public function exportToJson()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();


            $data["key"] = "@s12";
            $data["devices"] = $rows;

            $json = json_encode($data);

            //var_dump($json);

            // POST TO URL
            $ch = curl_init($this->url_pfsense);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
            );

            $result = curl_exec($ch);
            //echo "<br />";
            //echo "Error : " . curl_error($ch) . " | code err : " . curl_errno($ch);
            //echo "<br />";
            //var_dump(curl_getinfo($ch));
            curl_close($ch);

        } catch (Exception $e) {
            CustomError::showMessage($e->getMessage());
        }

    }
}
