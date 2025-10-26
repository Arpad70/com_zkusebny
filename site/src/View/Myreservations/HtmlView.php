<?php
namespace Zkusebny\Component\Zkusebny\Site\View\Myreservations;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;

class HtmlView extends BaseHtmlView
{
    public function display($tpl = null)
    {
        $user = Factory::getUser();
        if ($user->guest) {
            throw new \Exception('Přihlaste se prosím.');
        }

        $model = $this->getModel('Myreservations');
        $this->items = $model->getUserReservations($user->id);

        parent::display($tpl);
    }
}
