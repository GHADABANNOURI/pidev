<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;


class ChatbotController extends AbstractController
{
    #[Route('/publication', name: 'chatbot_publication', methods: ['POST'])]
    public function chat(Request $request): Response
    {
        // Créer une instance de BotMan
        $config = [
            'facebook' => [
                'token' => 'YOUR_FACEBOOK_PAGE_TOKEN',
                // Ajoutez d'autres configurations si nécessaire
            ],
            // Vous pouvez également configurer d'autres pilotes ici (Slack, Telegram, etc.)
        ];

        $botman = BotManFactory::create($config);

        // Définir la logique de conversation
        $botman->hears('Bonjour', function (BotMan $bot) {
            $bot->reply('Salut! Comment puis-je vous aider aujourd\'hui?');
        });

        // Ajoutez d'autres logiques de conversation selon vos besoins

        // Traiter la requête entrante
        $botman->listen();

        return new Response();
    }
    }
