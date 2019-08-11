<?php

namespace App\Controllers;

use App\Components\Api\Api;
use App\Controllers\ControllerInterface;
use InvalidArgumentException;
use Exception;

class ContactController extends AppController implements ControllerInterface
{
    /** @var int $userId */
    protected $userId;

    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_SESSION['auth'])){
            $this->userId = $_SESSION['auth']['id'];
        }
        $this->loadModel('Contact');
    }

    /**
     * Affichage de la liste des contacts de l'utilisateur connecté
     */
    public function index()
    {
        $contacts = [];
        if (!empty($this->userId)) {
            $contacts = $this->Contact->getContactByUser($this->userId);
        }
        echo $this->twig->render('index.html.twig', ['contacts' => $contacts]);
    }

    /**
     * Methode pour page de creation
     */
    public function create()
    {}

    /**
     * Ajout d'un contact
     */
    public function add()
    {
        $error = false;
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if ($response["response"]) {
                $result = $this->Contact->create([
                    'nom'    => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email'  => $response['email'],
                    'userId' => $this->userId
                ]);
                if ($result) {
                    header('Location: index.php?p=contact.index');
                }
            } else {
                $error = true;
            }
        }
        echo $this->twig->render('add.html.twig', ['error' => $error]);
    }

    /**
     * Modification d'un contact
     */
    public function edit()
    {
        $error = false;
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if ($response["response"]) {
                $result = $this->Contact->setContact([
                    'nom'    => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email'  => $response['email'],
                    'id' => $_POST['id']
                ]);
                if ($result) {
                    header('Location: index.php?p=contact.index');
                }
            } else {
                $error = true;
            }
        }else{
            $idContact = $_GET["id"];
            $contact = $this->Contact->getContact($idContact);
        }
        echo $this->twig->render('edit.html.twig', ['data' => $contact, 'error' => $error]);
    }

    /**
     * Suppression d'un contact
     */
    public function delete()
    {
        $result = $this->Contact->delete($_GET['id']);
        if ($result) {
            header('Location: index.php?p=contact.index');
        }
    }

    /**
     * Check user
     */
    public function check()
    {
        $check = new Api();
        return $check;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function sanitize(array $data = []): array
    {
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $email = $data['email'];

        if (empty($nom)) {
            throw new Exception('Le nom est obligatoire');
        }

        if (empty($prenom)) {
            throw new Exception('Le prenom est obligatoire');
        }

        if (empty($email)) {
            throw new Exception('Le email est obligatoire');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Le format de l\'email est invalide');
        }

        $prenom = strtoupper($data['prenom']);
        $nom    = strtoupper($data['nom']);
        $email  = strtolower($data['email']);

        $isPalindrome = $this->apiClient(['name' => $nom, 'request' => 'palindrome']);
        $isEmail = $this->apiClient(['email' => $email, 'request' => 'email']);
        if ((!$isPalindrome->response) && $isEmail->response && $prenom) {
            return [
                'response' => true,
                'email'    => $email,
                'prenom'   => $prenom,
                'nom'      => $nom
            ];
        }
    }
}