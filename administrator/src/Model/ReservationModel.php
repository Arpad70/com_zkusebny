<?php
namespace Zkusebny\Component\Zkusebny\Administrator\Model;

use Joomla\CMS\MVC\Model\AdminModel;

class ReservationModel extends AdminModel
{
    public function getTable($name = '', $prefix = '', $options = [])
    {
        return $this->getMVCFactory()->createTable('Reservation', 'Administrator', $options);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_zkusebny.reservation', 'reservation', ['control' => 'jform', 'load_data' => $loadData]);
        return empty($form) ? false : $form;
    }
}
