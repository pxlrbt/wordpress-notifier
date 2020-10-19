<input type="text"
    class="regular-text"
    id="<?php echo $args['slug']; ?>"
    name="<?php echo $args['slug']; ?>"
    <?php if (isset($field->args['maxlength'])) : ?>    
        maxlength="<?php echo $field->args['maxlength']; ?>"
    <?php endif; ?>
    value="<?php echo isset($value) ? esc_attr($value) : ''; ?>">

<?php if (isset($field->args['description'])) : ?>
    <p class="description">
        <?php echo $field->args['description']; ?>
    </p>
<?php endif; ?>
