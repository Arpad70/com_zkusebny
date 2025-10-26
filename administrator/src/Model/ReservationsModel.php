<?php
namespace Zkusebny\Component\Zkusebny\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class ReservationsModel extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'slot_start', 'a.slot_start',
                'state', 'a.state',
                'user_id', 'a.user_id'
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery(): QueryInterface
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select(['a.*', 'u.name AS user_name'])
            ->from('#__zkusebny_reservations AS a')
            ->leftJoin('#__users AS u ON u.id = a.user_id');

        return $query;
    }
}
