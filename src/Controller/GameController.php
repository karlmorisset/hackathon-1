<?php

namespace App\Controller;

use App\Model\ApiManager;

class GameController extends AbstractController
{
    public const MAX_QUESTIONS = 5;

    public function __construct()
    {
        parent::__construct();

        session_start();
        if (!isset($_SESSION['current_question'])) {
            $_SESSION['current_question'] = 1;
        }

        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }
    }

    public function index()
    {
        $source = new ApiManager();
        $data = $source->getGameCharacters();

        $answer = $data['answer'];
        $hash = password_hash($answer['char_id'], PASSWORD_DEFAULT);
        $characters = $data['characters'];

        return $this->twig->render('Game/index.html.twig', [
            'answer' => $answer,
            'hash' => $hash,
            'score' => $_SESSION['score'],
            'current_question' =>  $_SESSION['current_question'],
            'max_questions' =>  self::MAX_QUESTIONS,
            'characters' => $characters
        ]);
    }


    public function check()
    {
        if(password_verify($_POST['reponse'], $_POST['answer'])) {
            $_SESSION['score']++;
        }

        if (intval($_SESSION['current_question']) >= self::MAX_QUESTIONS) {
            header('Location:/resultat');
            exit();
        }

        $_SESSION['current_question']++;
        header("Location:/");
    }

    public function resultat()
    {
        if (!isset($_SESSION['score'])) {
            header('Location:/');
        }
        $msg = "Bravo vous avez obtenu : " . $_SESSION['score']. " points";
        unset($_SESSION['score']);
        unset($_SESSION['current_question']);


        return $this->twig->render('Game/result.html.twig', ['msg' => $msg]);
    }
}
