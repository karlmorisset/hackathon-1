<?php

namespace App\Controller;

use App\Model\ApiManager;

class GameController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();

        session_start();
        $_SESSION['max_questions'] = 5;

        if (!isset($_SESSION['current_question'])) {
            $_SESSION['current_question'] = 1;
        }

        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }
    }


    /**
     * Gère la création et l'affichage d'une question
     *
     * @return string
     */
    public function index(): string
    {
        $dataSource = new ApiManager();

        return $this->twig->render('Game/index.html.twig', [
            'question' => $this->getQuestion($dataSource),
            'session' => $_SESSION
        ]);
    }


    /**
     * Permet de générer une question avec la réponse associée
     *
     * @param ApiManager $source
     * @return array
     */
    public function getQuestion($source): array
    {
        $data = $source->getGameCharacters();

        return [
            "img" => $data['answer']->img,
            "answer_hash" => password_hash($data['answer']->char_id, PASSWORD_DEFAULT),
            "suggestions" => $data['suggestions']
        ];
    }


    /**
     * Permet de vérifier si une réponse est bonne
     *
     * @return void
     */
    public function check()
    {
        if (password_verify($_POST['reponse'], $_POST['answer'])) {
            $_SESSION['score']++;
        }

        if (intval($_SESSION['current_question']) >= intval($_SESSION['max_questions'])) {
            header('Location:/resultat');
            return;
        }

        $_SESSION['current_question']++;
        header("Location:/");
    }


    /**
     * Permet d'afficher la page des scores obtenus
     *
     * @return string
     */
    public function resultat(): string
    {
        if (!isset($_SESSION['score'])) {
            header('Location:/');
        }
        $msg = "Bravo vous avez obtenu : " . $_SESSION['score'] . " points";
        unset($_SESSION['score']);
        unset($_SESSION['current_question']);

        return $this->twig->render('Game/result.html.twig', ['msg' => $msg]);
    }
}
