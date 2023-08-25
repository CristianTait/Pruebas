<form method="post">
			<label for="user_to_remote">Eliminar usuario autorizado:</label>
			<select name="user_to_remove" id="user_to_remove" >
				<?php foreach ($authorized_users as $user) : ?>
					<option value="<?php echo esc_attr($user); ?>"><?php echo esc_html($user); ?></option>
				<?php endforeach?>
			</select>
			<input type="submit" name="remove_user" value="eliminar">
		</form>
