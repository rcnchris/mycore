<?php
$flash = new \Rcnchris\Core\Session\FlashService(new \Rcnchris\Core\Session\PHPSession());
r($flash);
$flash
    ->add('success', 'Ola les gens')
    ->add('error', 'Problème...')
    ->add('success', 'bien !');

r($flash->getSession()->get('flash')->toArray());
r($flash->get('error'));
r($flash->getSession()->get('flash')->toArray());
r($flash->getMessages()->toArray());