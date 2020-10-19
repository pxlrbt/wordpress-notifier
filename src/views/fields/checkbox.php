
<input type="checkbox"
   id="<?php echo $args['slug']; ?>"
   name="<?php echo $args['slug']; ?>"
   value="1"
   <?php echo checked(1, $value, false) ?>$value


<?php if (isset($field->args['description'])): ?>
    <label for="<?php echo $args['slug']; ?>">
        <?php echo $field->args['description']; ?>
    </label>
<?php endif; ?>
