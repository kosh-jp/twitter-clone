<?php

/** @var View $this */
$this->setLayoutVar('title', $status['user_name']);

echo $this->render('status/status', ['statuses' => [$status]]);
