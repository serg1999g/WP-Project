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
// telegram id user
$user_id = 334077236;

// creating mail metabox
add_action('add_meta_boxes', 'init');
add_action('save_post', 'send', 10, 3);

function init() {
    add_meta_box('new_product', 'Email', 'metabox', 'product', 'side', 'high');
}

function metabox() {
    echo '<p>Введите email</p>
<input type="text" name="email_product" value="">';
    wp_nonce_field('metabox_action', 'metabox_nonce');
}

function send($post_id, $post, $update) {
    if (!isset($_POST['email_product'])) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    $update = $post->post_date === $post->post_modified;

    if (!$update) {
        return;
    }

    // request validation
    check_admin_referer('metabox_action', 'metabox_nonce');

    // sending letter
    $mail = $_POST['email_product'];
    wp_mail($mail, 'new product: ', 'goods arrived', 'content-type: text/html', '');

    global $telegramApi, $user_id;
    // sending notice
    $telegramApi->sendMessage($user_id, 'Доступен новый товар');
}
