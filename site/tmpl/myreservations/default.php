<?php
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

if (empty($this->items)) {
    echo '<p>' . Text::_('COM_ZKUSEBNY_NO_RESERVATIONS') . '</p>';
    return;
}
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo Text::_('COM_ZKUSEBNY_FIELD_SLOT_START'); ?></th>
            <th><?php echo Text::_('COM_ZKUSEBNY_FIELD_PAID_HOURS'); ?></th>
            <th><?php echo Text::_('COM_ZKUSEBNY_FIELD_STATE'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
            <tr>
                <td><?php echo HTMLHelper::_('date', $item->slot_start, 'd.m.Y H:i'); ?></td>
                <td><?php echo $item->paid_hours; ?></td>
                <td><?php echo $item->state ? Text::_('COM_ZKUSEBNY_ACTIVE') : Text::_('COM_ZKUSEBNY_ENDED'); ?></td>
                <td>
                    <?php if ($item->state): ?>
                        <a href="<?php echo Route::_('index.php?option=com_zkusebny&task=cancel.cancel&id=' . $item->id . '&' . HTMLHelper::_('form.token')); ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('<?php echo Text::_('COM_ZKUSEBNY_CONFIRM_CANCEL'); ?>');">
                            <?php echo Text::_('COM_ZKUSEBNY_CANCEL'); ?>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>