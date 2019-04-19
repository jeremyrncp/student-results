<?php

namespace App\Form\Handler;

use Symfony\Component\Form\FormInterface;

interface FormHandlerInterface
{
    public function handle(FormInterface $form): bool;
}
