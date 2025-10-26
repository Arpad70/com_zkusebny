<?php
define('_JEXEC', 1);
require_once __DIR__ . '/../includes/defines.php';
require_once __DIR__ . '/../includes/framework.php';

use Joomla\CMS\Factory;
use Joomla\CMS\Mail\MailHelper;
use Joomla\CMS\Language\Text;

$db = Factory::getContainer()->get('DatabaseInterface');
$now = (new DateTime())->format('Y-m-d H:i:s');
$remind = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

$res = $db->setQuery("SELECT * FROM #__zkusebny_reservations WHERE slot_start BETWEEN '$now' AND '$remind' AND reminded = 0")->loadObjectList();

foreach ($res as $r) {
    $user = Factory::getUser($r->user_id);
    if (empty($user->email)) continue;

    // Email
    $mailer = Factory::getMailer();
    $mailer->setSubject(Text::_('COM_ZKUSEBNY_EMAIL_REMIND_SUBJECT'));
    $mailer->setBody(sprintf(
        Text::_('COM_ZKUSEBNY_EMAIL_REMIND_BODY'),
        $user->name,
        HTMLHelper::_('date', $r->slot_start, 'd.m.Y H:i')
    ));
    $mailer->addRecipient($user->email);
    $mailer->Send();

    // Push notifikace
    sendPushNotification($user->id, 'Za 30 min začíná vaše rezervace v zkušebně.');

    $db->setQuery("UPDATE #__zkusebny_reservations SET reminded = 1 WHERE id = " . (int)$r->id)->execute();
}
function sendPushNotification(int $userId, string $message)
{
    $db = Factory::getContainer()->get('DatabaseInterface');
    $player = $db->setQuery("SELECT player_id FROM #__zkusebny_push WHERE user_id = " . (int)$userId)->loadResult();

    if (!$player) return;

    $apiKey = 'VASE_ONESIGNAL_API_KLIC';
    $appId = 'VASE_ONESIGNAL_APP_ID';

    $http = \Joomla\CMS\Http\HttpFactory::getHttp();
    $response = $http->post('https://onesignal.com/api/v1/notifications', json_encode([
        'app_id' => $appId,
        'include_player_ids' => [$player],
        'contents' => ['en' => $message],
    ]), [
        'Authorization: Basic ' . $apiKey,
        'Content-Type: application/json'
    ]);

    return $response->code === 200;
}

