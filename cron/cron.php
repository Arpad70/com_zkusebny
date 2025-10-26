<?php
define('_JEXEC', 1);
define('JPATH_BASE', __DIR__ . '/../');
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

$app = \Joomla\CMS\Factory::getApplication('administrator');
$db  = \Joomla\CMS\Factory::getContainer()->get('DatabaseInterface');

$now = (new DateTime())->format('Y-m-d H:i:s');
$config = $db->setQuery("SELECT * FROM #__zkusebny_config")->loadAssocList('key', 'value');
$packup = (int) $config['packup_minutes']['value'];
$shelly = $config['shelly_ip']['value'];

$res = $db->setQuery("SELECT * FROM #__zkusebny_reservations WHERE real_end <= '$now' AND state = 1")->loadObjectList();

foreach ($res as $r) {
    $url = "http://{$shelly}/relay/0?turn=off";
    file_get_contents($url);
    usleep(500000);
    $url = "http://{$shelly}/relay/0?turn=on&timer=" . ($packup * 60);
    file_get_contents($url);

    $db->setQuery("UPDATE #__zkusebny_reservations SET state = 0 WHERE id = " . (int)$r->id)->execute();
}
