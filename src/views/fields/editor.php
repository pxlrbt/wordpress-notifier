
<?php wp_editor($value, $args['slug']); ?>

<?php if (isset($field->args['description'])) : ?>
    <p class="description">
        <?php echo $field->args['description']; ?>
    </p>
<?php endif; ?>
