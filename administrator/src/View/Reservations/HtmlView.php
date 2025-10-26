<?php
namespace Zkusebny\Component\Zkusebny\Administrator\View\Reservations;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_ZKUSEBNY_RESERVATIONS_TITLE'), 'calendar');
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'reservations.delete');
    }
}
