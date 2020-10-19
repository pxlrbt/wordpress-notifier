<div class="wrap">
    <h1>Einstellungen › <?php echo esc_html(get_admin_page_title()); ?></h1>

    <form action="options.php" method="post">
        <?php
            settings_fields($this->slug);
            do_settings_sections($this->slug);
            submit_button( __('Änderungen übernehmen'));
        ?>
    </form>
</div>
