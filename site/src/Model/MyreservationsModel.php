<?php
namespace Zkusebny\Component\Zkusebny\Site\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class MyreservationsModel extends BaseDatabaseModel
{
    public function getUserReservations(int $userId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__zkusebny_reservations')
            ->where('user_id = ' . $db->q($userId))
            ->order('slot_start DESC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
