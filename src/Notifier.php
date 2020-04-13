<?php

namespace pxlrbt\WordpressNotifier;

use pxlrbt\WordpressNotifier\Contract\Notification as NotificationContract;

/**
 * Notification helper for WordPress.
 *
 * Store and display notifications for admin panel.
 */
class Notifier
{
    private static $instance = null;
    private static $counter = 0;

    protected static $PREFIX = 'pxlrbt_';
    protected static $notificationClass = Notification::class;

    protected $transientName;
    protected $notifications;

    /**
     * Create a notifier
     */
    public function __construct()
    {
        $this->notifications = [];
        $this->transientName = static::$PREFIX . "notifications_" . static::$counter++;

        if (self::$instance === null) {
            self::$instance = $this;
        }

        $this->loadNotifications();
        $this->bindHooks();
    }

    /**
     * Get a singleton instance
     *
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Hook into WordPress
     *
     * @return void
     */
    public function bindHooks()
    {
        add_action('admin_notices', [$this, 'renderNotifications']);
        add_action('wp_ajax_dismiss_admin_notification', [$this, 'dismissNotification']);
        add_action('admin_footer', [$this, 'enqueueScript']);
    }

    /**
     * Dispatch a simple notification
     *
     * @param string $type notification type
     * @param string $message notification message
     * @return boolean
     */
    public static function notify(string $type, string $message)
    {
        return static::getInstance()->dispatch(
            static::$notificationClass::create($message)
                ->type($type)
        );
    }

    /**
     * Dispatch a simple info notificattion
     *
     * @param string $message
     * @return boolean
     */
    public static function info(string $message)
    {
        static::notify('info', $message);
    }

    /**
     * Dispatch a simple success notificattion
     *
     * @param string $message
     * @return boolean
     */
    public static function success(string $message)
    {
        static::notify('success', $message);
    }

    /**
     * Dispatch a simple warning notificattion
     *
     * @param string $message
     * @return boolean
     */
    public static function warning(string $message)
    {
        static::notify('warning', $message);
    }

    /**
     * Dispatch a simple error notificattion
     *
     * @param string $message
     * @return boolean
     */
    public static function error(string $message)
    {
        static::notify('error', $message);
    }

    /**
     * Dispatch a new notification
     *
     * @param NotificationContract $notification
     * @return boolean
     */
    public function dispatch(NotificationContract $notification)
    {
        $this->notifications[$notification->getId()] = $notification;
        $this->saveNotifications();
        return true;
    }

    /**
     * Whether notification with given ID already exists
     *
     * @param string $id
     * @return boolean
     */
    public function containsNotification(string $id)
    {
        return array_key_exists($id, $this->notifications);
    }

    /**
     * Remove a notification by its ID
     *
     * @param string $id
     * @return void
     */
    public function removeNotification(string $id)
    {
        if (!$this->containsNotification($id)) return;

        unset($this->notifications[$id]);
        $this->saveNotifications($this->notifications);
    }

    /**
     * WordPress endpoint for dismissing a notification
     *
     * @return void
     */
    public function dismissNotification()
    {
        $id = filter_input(INPUT_POST, 'id');
        $this->removeNotification($id);
        return;
    }

    /**
     * Enqueue script for dismissing notifications
     *
     * @return void
     */
    public function enqueueScript()
    {
        if (count($this->notifications) === 0) return;

        ?>
            <script>
                jQuery(document).ready(function($){
                    $('.notice').on('click','.notice-dismiss',function(e){
                        $.post(ajaxurl,{
                            action: 'dismiss_admin_notification',
                            id: $(this).parent().attr('id')
                        });
                    });
                });
            </script>
        <?php
    }

    /**
     * Load notifications from database
     *
     * @return void
     */
    public function loadNotifications()
    {
        $notifications = get_transient($this->transientName);

        if (is_array($notifications)) {
            return $this->notifications = $notifications;
        }

        $this->notifications = [];
    }

    /**
     * Save notifications to database
     *
     * @return void
     */
    public function saveNotifications()
    {
        return set_transient($this->transientName, $this->notifications);
    }

    /**
     * Clear notifications
     *
     * @return void
     */
    public function clearNotifications()
    {
        $this->notifications = [];
        $this->saveNotifications();
    }

    /**
     * Output notifications
     *
     * Collect notifications html, removes non-persistent notifications.
     *
     * @return void
     */
    public function renderNotifications()
    {
        if (empty($this->notifications)) return;

        $html = '';

        foreach ($this->notifications as $n) {
            $html .= $n->render();

            if (!$n->isPersistent()) {
                $this->removeNotification($n->getId());
            }
        }

        echo $html;
    }
}
