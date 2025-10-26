<?php
/**  
 * @package     Joomla.Administrator  
 * @subpackage  com_reservation  
 *  
 * @copyright   (C) 2025 Open Source Matters, Inc.  
 * @license     GNU General Public License version 2 or later  
 */

namespace Reservation\Component\Reservation\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

class ReservationController extends FormController
{
    protected $text_prefix = 'COM_RESERVATION';


    public function cancel()
    {
        $this->checkToken();
        $id = $this->input->getInt('id');
        $db = Factory::getContainer()->get('DatabaseInterface');
        $db->setQuery('DELETE FROM #__zkusebny_reservations WHERE id = ' . $db->q($id))->execute();
        $this->setRedirect(Route::_('index.php?option=com_zkusebny&view=reservations', false), Text::_('COM_ZKUSEBNY_RESERVATION_CANCELLED'));
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId = isset($data[$key]) ? $data[$key] : 0;
        $user = Factory::getApplication()->getIdentity();

        return $user->authorise('core.edit', 'com_reservation.reservation.' . (int) $recordId);
    }

    protected function allowDelete($data = array(), $key = 'id')
    {
        $recordId = isset($data[$key]) ? $data[$key] : 0;
        $user = Factory::getApplication()->getIdentity();

        return $user->authorise('core.delete', 'com_reservation.reservation.' . (int) $recordId);
    }

    protected function allowCreate($data = array())
    {
        $user = Factory::getApplication()->getIdentity();

        return $user->authorise('core.create', 'com_reservation');
    }

    protected function getModel($name = 'Reservation', $prefix = 'ReservationComponent\Reservation\Administrator\Model\\', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}