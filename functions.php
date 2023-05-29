// Aggiungi il campo al profilo utente
add_action('show_user_profile', 'add_abbonamento_field');
add_action('edit_user_profile', 'add_abbonamento_field');

function add_abbonamento_field($user) {
    $abbonamento = get_user_meta($user->ID, 'abbonamento', true);
    ?>
    <h3>Informazioni Extra</h3>
    <table class="form-table">
        <tr>
            <th><label for="abbonamento">Abbonamento</label></th>
            <td>
                <input type="checkbox" name="abbonamento" id="abbonamento" value="true" <?php checked($abbonamento, 'true'); ?> />
                <span class="description">Seleziona se l'utente ha un abbonamento attivo.</span>
            </td>
        </tr>
    </table>
    <?php
}

// Salva il campo personalizzato quando il profilo viene salvato
add_action('personal_options_update', 'save_abbonamento_field');
add_action('edit_user_profile_update', 'save_abbonamento_field');

function save_abbonamento_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'abbonamento', $_POST['abbonamento']);
}
add_filter('woocommerce_coupon_is_valid', 'check_abbonamento_status', 10, 2);
function check_abbonamento_status($valid, $coupon){
    $user_id = get_current_user_id(); 
    $abbonamento = get_user_meta($user_id, 'abbonamento', true);

    if($abbonamento != 'true') {
        $valid = false;
        wc_add_notice(__('Il codice sconto può essere utilizzato solo se l\'abbonamento è attivo.', 'woocommerce'), 'error');
    }
    return $valid;
}
