<form method="post">
    <div class="form-group">
        <label for="user_to_add" class="font-weight-bold">Agregar usuario habilitado:</label>
        <select class="form-control" name="user_to_add" id="user_to_add" size="10">
            <?php foreach ($all_user_vendor as $user) : ?>
                <option value="<?php echo esc_attr($user->user_login); ?>"><?php echo esc_html($user->user_login); ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-lg" name="add_user">Agregar</button>
</form>
