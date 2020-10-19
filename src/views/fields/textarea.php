<textarea
    class="regular-text"
    id="<?php echo $args['slug']; ?>"
    name="<?php echo $args['slug']; ?>"
    cols="30"
    rows="10"><?php echo isset($value) ? esc_attr($value) : ''; ?></textarea>

<?php if (isset($field->args['description'])) : ?>
    <p class="description">
        <?php echo $field->args['description']; ?>
    </p>
<?php endif; ?>
