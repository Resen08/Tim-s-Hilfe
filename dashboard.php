<?php
session_start();

include_once('class/HTMLPage.class.php');
include_once('class/News.class.php');

$html = new HTMLPage();
$news = new News();

print $html->head('Good News - Dashboard');

print $news->getHeader();
print $news->getUserInfoPanel();
print '<div class="posts">';
print $news->getPosts();
print '</div>';

print $html->foot();