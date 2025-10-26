<?php
namespace Zkusebny\Component\Zkusebny\Api\Controller;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\Factory;

class ReservationController extends ApiController
{
    protected $contentType = 'reservations';
    protected $default_view = 'reservations';

    public function save()
    {
        $data = $this->input->json->getArray();
        $data['user_id'] = Factory::getUser()->id ?? 0;
        $data['slot_start'] = date('Y-m-d H:i:s', strtotime($data['slot_start']));
        $data['unlock_time'] = $data['slot_start'];

        $model = $this->getModel('Reservation', 'Administrator');
        if ($model->save($data)) {
            $this->sendResponse(['success' => true]);
        } else {
            $this->sendResponse(['success' => false, 'error' => $model->getError()], 400);
        }
    }

    private function sendResponse($data, $code = 200)
    {
        Factory::getApplication()->setHeader('Content-Type', 'application/json', true);
        http_response_code($code);
        echo json_encode($data);
        Factory::getApplication()->close();
    }
}
