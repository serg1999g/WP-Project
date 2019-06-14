<?php
/* 
* Plugin Name: New product
Description: New product notification
Vervion: 1.0
Author: Sergeant
*/


include('vendor/autoload.php');
include('TelegramBot.php');

$telegramApi = new TelegramBot();
$user_id = 334077236;


add_action('add_meta_boxes', 'init');
add_action('save_post', 'send');

function init() {
    add_meta_box('new_product','Email', 'metabox', 'product', 'side', 'high');
}

function metabox() {
    echo '<p>Введите email</p>
<input type="text" name="email_product" value="">';
    wp_nonce_field('metabox_action', 'metabox_nonce');
}

function send() {
    // пришло ли поле с почтой?
    if (!isset($_POST['email_product']))
        return;

    // не происходит ли автосохранение?
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // не ревизию ли сохраняем?
    if (wp_is_post_revision($postID))
        return;

    // проверка достоверности запроса
    check_admin_referer('metabox_action', 'metabox_nonce');

    $mail = $_POST['email_product'];

    wp_mail($mail, 'new product: ', 'goods arrived', 'content-type: text/html', '');




    

    global $telegramApi, $user_id;

    $telegramApi->sendMessage($user_id, 'Доступен новый товар');
}
