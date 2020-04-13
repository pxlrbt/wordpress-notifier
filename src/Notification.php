<?php

namespace pxlrbt\WordpressNotifier;

use InvalidArgumentException;
use pxlrbt\WordpressNotifier\Contracts\Notification as NotificationContract;

/**
 * Notification for wordpress.
 */
class Notification implements NotificationContract
{
    protected $id;
    protected $classes = '';
    protected $type = 'info';
    protected $title = null;
    protected $message;
    protected $dismissible = false;
    protected $persistent = false;

    private static $TYPES = ['info', 'success', 'warning', 'error'];

    /**
     * Create a new notification
     *
     * @param string $message message
     * @param string $id unique id to identify notification
     */
    public function __construct(string $message, string $id = null)
    {
        $this->id = $id !== null ? $id : uniqid();
        $this->message = $message;
    }

    /**
     * Static helper for creating notification
     *
     * @param argi $args
     * @return static
     */
    public static function create(string $message, string $id = null)
    {
        return new static($message, $id);
    }

    /**
     * Set notification id
     *
     * @param string $value
     * @return static
     */
    public function id(string $value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Set whether notification can be dismissed by user
     *
     * @param boolean $value
     * @return static
     */
    public function dismissible(bool $value)
    {
        $this->dismissible = $value;
        return $this;
    }

    /**
     * Set whether notification will be showed more than once
     *
     * @param boolean $value
     * @return static
     */
    public function persistent(bool $value)
    {
        $this->persistent = $value;
        return $this;
    }

    /**
     * Set title of notification
     *
     * @param string $value
     * @return static
     */
    public function title(string $value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * Set message of notificatin
     *
     * @param string $value
     * @return static
     */
    public function message(string $value)
    {
        $this->message = $value;
        return $this;
    }

    /**
     * Set type of notification
     *
     * @param string $value
     * @return static
     */
    public function type(string $value)
    {
        if (!in_array($value, self::$TYPES)) {
            throw new InvalidArgumentException('Type must be of ' . implode(', ', self::$TYPES));
        }

        $this->type = $value;
        return $this;
    }

    /**
     * Set additional css classes
     *
     * @param string $value
     * @return static
     */
    public function classes(string $value)
    {
        $this->classes = $value;
        return $this;
    }

    /**
     * Get if of notification
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Whether notification can be dismissed by user
     *
     * @return boolean
     */
    public function isDismissible()
    {
        return $this->dismissible;
    }

    /**
     * Whether notification is shown more than once
     *
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * Render message html
     *
     * @return void
     */
    public function render()
    {
        printf(
            '<div class="notice notice-%s%s%s" id="%s"><p>%s%s</p></div>',
            $this->type,
            $this->isDismissible() ? ' is-dismissible' : '',
            $this->classes ? ' ' . $this->classes :  '',
            $this->id,
            !empty($this->title) ? '<strong>' . $this->title . '</strong>' : '',
            $this->message
        );
    }
}
