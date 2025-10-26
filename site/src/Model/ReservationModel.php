<?php
namespace Zkusebny\Component\Zkusebny\Site\Model;

use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Factory;

class ReservationModel extends FormModel
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_zkusebny.reservation', 'reservation', ['control' => 'jform', 'load_data' => $loadData]);
        return empty($form) ? false : $form;
    }

    protected function loadFormData()
    {
        return [];
    }

    public function save($data)
    {
        $db = Factory::getContainer()->get('DatabaseInterface');
        $table = $this->getTable('Reservation', 'Administrator');

        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }
}
