
<select id="<?php echo $args['slug']; ?>" name="<?php echo $args['slug']; ?>">
    <?php foreach ($field->args['options'] as $key => $name): ?>
        <option value="<?php echo $key; ?>" <?php if ($key == $value): ?>selected<?php endif; ?>>
            <?php echo $name; ?>
        </option>
    <?php endforeach; ?>
</select>

<?php if (isset($field->args['description'])) : ?>
    <p class="description">
        <?php echo $field->args['description']; ?>
    </p>
<?php endif; ?>
