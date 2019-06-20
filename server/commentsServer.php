<?php
require('config.php');
require('models/model_user.php');
require('models/model_comment.php');

if(isset($_POST['message'])) {
    $parent = (int)$_POST['parent'];
    $message = $_POST['message'];
    $user = $_POST['user'];
    $user = Model_User::getByName($user);

    $comment = new Model_Comment;
    $comment->parent = $parent;
    $comment->message = $message;
    $comment->user_id = $user->id;
    $comment->insert();
    populate_shoutbox();
}


if(isset($_POST['refresh'])) {
    populate_shoutbox();
}

function populate_shoutbox() {
    $parent = (int)$_POST['parent'];
    $comments = Model_Comment::getListByCarID($parent);
    $html = "";
    $html .= '<ul>';
    foreach ($comments['results'] as $comment) {
        if(!$comment->shown)
            continue;
        $user = Model_User::getById($comment->user_id);
        $comment = (array)$comment;
        $html .= '<li>';
        $html .= '<div class = "comment">';
        $html .= '<div>';
        $html .=  '<span class="name">'.$user->name.'</span>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<span class="message">'.$comment['message'].'</span>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<span class="date">'.date("d.m.Y H:i", strtotime($comment['date_time'])).'</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</li>';
    }
    $html .= '</ul>';
    echo $html;
}
?>