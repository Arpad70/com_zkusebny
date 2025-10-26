<?php
defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
?>
<form action="<?php echo Route::_('index.php?option=com_zkusebny&task=reservation.save'); ?>" method="post" class="form-validate">
    <?php echo $this->form->renderFieldset(); ?>
    <button type="submit" class="btn btn-primary"><?php echo Text::_('COM_ZKUSEBNY_RESERVE'); ?></button>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "VASE_ONESIGNAL_APP_ID",
    });
    OneSignal.getUserId().then(function(playerId) {
      if (playerId) {
        fetch('/index.php?option=com_zkusebny&task=push.register&format=json', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'player_id=' + encodeURIComponent(playerId)
        });
      }
    });
  });
</script>
