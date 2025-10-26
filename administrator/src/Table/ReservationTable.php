<?php
namespace Zkusebny\Component\Zkusebny\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;

class ReservationTable extends Table
{
    public function __construct(DatabaseInterface $db)
    {
        parent::__construct('#__zkusebny_reservations', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        if (empty($this->real_end) && !empty($this->unlock_time) && $this->paid_hours > 0) {
            $unlock = strtotime($this->unlock_time);
            $totalMinutes = $this->paid_hours * 60;
            $rawEnd = $unlock + ($totalMinutes * 60);
            $this->real_end = date('Y-m-d H:i:s', floor($rawEnd / 300) * 300);
        }

        return parent::store($updateNulls);
    }
}
