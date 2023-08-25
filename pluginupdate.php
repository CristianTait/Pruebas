<form method="post">
    <div class="form-group">
        <label for="user_to_remove" class="font-weight-bold">Eliminar usuario autorizado:</label>
        <select class="form-control" name="user_to_remove" id="user_to_remove">
            <?php foreach ($authorized_users as $user) : ?>
                <option value="<?php echo esc_attr($user); ?>"><?php echo esc_html($user); ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <button type="submit" class="btn btn-danger btn-lg" name="remove_user">Eliminar</button>
</form>
