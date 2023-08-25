<form method="post">
			<label for="user_to_add">Agregar usuario habilitado: </label>
			<select name="user_to_add" id="user_to_add" size="10">
				<?php foreach ($all_user_vendor as $user) : ?>
					<option value="<?php echo esc_attr($user->user_login); ?>"><?php echo esc_html($user->user_login); ?></option>
				<?php endforeach?>
			</select>
			<input type="submit" name="add_user" value="Agregar">
		</form>
