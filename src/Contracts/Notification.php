<?php

namespace pxlrbt\WordpressNotifier\Contract;

interface Notification
{
    public function id();
    public function type();
    public function title();
    public function message();
    public function dismissible();
    public function persistent();
    public function classes();

    public function getId();
    public function isDismissible();
    public function isPersistent();
    public function render();
}
