<?php
namespace Zkusebny\Component\Zkusebny\Site\Controller;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

class CancelController extends BaseController
{
    public function cancel()
    {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        $id = $app->input->getInt('id');

        if (!$user->id) {
            $app->enqueueMessage(Text::_('COM_ZKUSEBNY_NOT_LOGGED_IN'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_zkusebny&view=myreservations', false));
            return;
        }

        $db = Factory::getContainer()->get('DatabaseInterface');
        $query = $db->getQuery(true)
            ->delete('#__zkusebny_reservations')
            ->where('id = ' . $db->q($id))
            ->where('user_id = ' . $db->q($user->id))
            ->where('state = 1');
        $db->setQuery($query)->execute();

        if ($db->getAffectedRows()) {
            $this->sendCancellationEmail($id);
            $app->enqueueMessage(Text::_('COM_ZKUSEBNY_RESERVATION_CANCELLED'), 'success');
        } else {
            $app->enqueueMessage(Text::_('COM_ZKUSEBNY_RESERVATION_NOT_FOUND'), 'error');
        }

        $this->setRedirect(Route::_('index.php?option=com_zkusebny&view=myreservations', false));
    }

    private function sendCancellationEmail(int $reservationId)
    {
        $db = Factory::getContainer()->get('DatabaseInterface');
        $res = $db->setQuery('SELECT * FROM #__zkusebny_reservations WHERE id = ' . $db->q($reservationId))->loadObject();
        if (!$res)
            return;

        $user = Factory::getUser($res->user_id);

        // Email
        $mailer = Factory::getMailer();
        $mailer->setSubject(Text::_('COM_ZKUSEBNY_EMAIL_CANCEL_SUBJECT'));
        $mailer->setBody(sprintf(
            Text::_('COM_ZKUSEBNY_EMAIL_CANCEL_BODY'),
            $user->name,
            HTMLHelper::_('date', $res->slot_start, 'd.m.Y H:i')
        ));
        $mailer->addRecipient($user->email);
        $mailer->Send();

        // Email adminovi  
        $adminEmail = Factory::getApplication()->get('mailfrom');
        $mailer = Factory::getMailer();
        $mailer->setSubject(Text::_('COM_ZKUSEBNY_EMAIL_ADMIN_CANCEL_SUBJECT'));
        $mailer->setBody(sprintf(
            Text::_('COM_ZKUSEBNY_EMAIL_ADMIN_CANCEL_BODY'),
            $user->name,
            HTMLHelper::_('date', $res->slot_start, 'd.m.Y H:i')
        ));
        $mailer->addRecipient($adminEmail);
        $mailer->Send();

        // Push notifikace
        sendPushNotification($user->id, 'Vaše rezervace byla zrušena: ' . HTMLHelper::_('date', $res->slot_start, 'd.m.Y H:i'));
    }

}
