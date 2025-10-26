<?php
namespace Zkusebny\Component\Zkusebny\Site\Controller;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

class PushController extends BaseController
{
    public function register()
    {
        $input = Factory::getApplication()->input;
        $userId = Factory::getUser()->id;
        $playerId = $input->getString('player_id');

        if (!$userId || !$playerId) {
            $this->sendJson(['success' => false, 'error' => 'Chybí data']);
            return;
        }

        $db = Factory::getContainer()->get('DatabaseInterface');
        $db->setQuery("REPLACE INTO #__zkusebny_push (user_id, player_id) VALUES (" . (int)$userId . ", " . $db->q($playerId) . ")")->execute();

        $this->sendJson(['success' => true]);
    }

    private function sendJson(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        Factory::getApplication()->close();
    }
}
