<?php
namespace Zkusebny\Component\Zkusebny\Site\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;

class ReservationController extends FormController
{
    protected $view_list = 'reservation';

    public function save($key = null, $urlVar = null)
    {
        $app = Factory::getApplication();
        $data = $app->input->post->get('jform', [], 'array');
        $user = Factory::getUser();


        // Výchozí hodnoty
        $data['user_id'] = $user->id ?? 0;
        $data['slot_start'] = date('Y-m-d H:i:s', strtotime($data['slot_start']));
        $data['paid_hours'] = (int) $data['paid_hours'];
        $data['unlock_time'] = $data['slot_start'];

        parent::save($key, $urlVar);

        // Email uživateli  
        $this->sendUserEmail($user, $data);

        // Email adminovi  
        $this->sendAdminEmail($data);

        // SMS uživateli  
        $this->sendSms($user->id, 'Rezervace byla vytvořena: ' . HTMLHelper::_('date', $data['slot_start'], 'd.m.Y H:i'));

        // SMS adminovi  
        $adminPhone = $this->getAdminPhone();
        if ($adminPhone) {
            $this->sendSmsToNumber($adminPhone, 'Nová rezervace: ' . HTMLHelper::_('date', $data['slot_start'], 'd.m.Y H:i'));
        }
    }

    private function sendUserEmail($user, $data)
    {
        $mailer = Factory::getMailer();
        $mailer->setSubject(Text::_('COM_ZKUSEBNY_EMAIL_NEW_SUBJECT'));
        $mailer->setBody(sprintf(
            Text::_('COM_ZKUSEBNY_EMAIL_NEW_BODY'),
            $user->name,
            HTMLHelper::_('date', $data['slot_start'], 'd.m.Y H:i'),
            (int) $data['paid_hours']
        ));
        $mailer->addRecipient($user->email);
        $mailer->Send();
    }


    private function sendAdminEmail($data)
    {
        $adminEmail = Factory::getApplication()->get('mailfrom');
        $mailer = Factory::getMailer();
        $mailer->setSubject(Text::_('COM_ZKUSEBNY_EMAIL_ADMIN_SUBJECT'));
        $mailer->setBody(sprintf(
            Text::_('COM_ZKUSEBNY_EMAIL_ADMIN_BODY'),
            HTMLHelper::_('date', $data['slot_start'], 'd.m.Y H:i'),
            (int) $data['paid_hours']
        ));
        $mailer->addRecipient($adminEmail);
        $mailer->Send();
    }

}
