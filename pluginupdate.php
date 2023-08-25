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


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
