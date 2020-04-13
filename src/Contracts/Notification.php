<?php

namespace pxlrbt\WordpressNotifier\Contracts;

interface Notification
{
    public function id(string $value);
    public function type(string $value);
    public function title(string $value);
    public function message(string $value);
    public function dismissible(bool $value);
    public function persistent(bool $value);
    public function classes(string $value);

    public function getId();
    public function isDismissible();
    public function isPersistent();
    public function render();
}
