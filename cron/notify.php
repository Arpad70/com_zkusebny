<?php
define('_JEXEC', 1);
require_once __DIR__ . '/../includes/defines.php';
require_once __DIR__ . '/../includes/framework.php';

$db = Factory::getContainer()->get('DatabaseInterface');
$now = (new DateTime())->format('Y-m-d H:i:s');
$limit = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

$res = $db->setQuery("SELECT * FROM #__zkusebny_reservations WHERE real_end BETWEEN '$now' AND '$limit' AND notified = 0")->loadObjectList();

foreach ($res as $r) {
    $user = Factory::getUser($r->user_id);
    $mailer = Factory::getMailer();
    $mailer->setSubject('Vaše rezervace brzy končí');
    $mailer->setBody("Vaše rezervace končí v {$r->real_end}. Nezapomeňte uklidit.");
    $mailer->addRecipient($user->email);
    $mailer->Send();

    $db->setQuery("UPDATE #__zkusebny_reservations SET notified = 1 WHERE id = " . (int)$r->id)->execute();
}
