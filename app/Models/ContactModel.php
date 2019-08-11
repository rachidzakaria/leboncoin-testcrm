<?php

namespace App\Models;
use App\Database;
use App\Models\AbstractModel;

class ContactModel extends AbstractModel
{
    /** @var string  */
    protected $table = "contacts";

    /**
     * ContactModel constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        parent::__construct($database);

     //   $this->model = new AbstractModel();
    }

    /**
     * Méthode de récupération des contacts d'un utilisateur
     * @param $idUser
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function getContactByUser($idUser)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE userId = $idUser");
    }

    /**
     * Méthode de récupération d'un contact
     * @param $idContact
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function getContact($idContact)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = $idContact");
    }

    /**
     * Méthode de modification d'un contact
     * @param $data
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function setContact($data)
    {
        return $this->query("UPDATE {$this->table} SET nom = '{$data['nom']}',prenom = '{$data['prenom']}',email = '{$data['email']}' WHERE id = {$data['id']}");
    }
}