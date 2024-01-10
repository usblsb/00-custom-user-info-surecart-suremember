<?php
/**
 * Plugin Name: 00 Informacion de usuario y membresia OFF IMPORTANTE NO BORRAR
 * Plugin URI: https://webyblog.es/
 * Description: Plugin de desarrollo para SureCart y SureMember que muestra en el hook: generate_before_header, información del usuario actual , si está logueado,su membresia, y su estado.
 * Version: 04-01-2024
 * Author: Juan Luis Martel
 * Author URI: https://webyblog.es/
 * License: GPL2
 */


if ( ! defined( 'ABSPATH' ) ) {
	die( 'No script kiddies please!' );
}

// El hook por defecto es 'generate_before_header', un hook del theme GENERATEPRESS pero puedes cambiarlo aquí si lo necesitas.
define('JLMR_HOOK_NAME', 'generate_before_header');

// Función para mostrar la información del usuario.
function jlmr_show_user_info() {

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        echo '<h2>Datos del usuario</h2>';
        echo "ID del Usuario: " . $current_user->ID . "<br/>";
        echo "Nombre del Usuario: " . $current_user->user_login . "<br/>";
        echo "Correo electrónico del Usuario: " . $current_user->user_email . "<br/>";

        $access_groups = get_user_meta($current_user->ID, 'suremembers_user_access_group', false);
        if (!empty($access_groups)) {
            foreach ($access_groups as $group) {
                foreach ($group as $group_id) {
                    echo "Grupo al que puede acceder el usuario: " . $group_id . "<br/>";
                    $access_group_meta = get_user_meta($current_user->ID, 'suremembers_user_access_group_' . $group_id, false);
                    if (!empty($access_group_meta)) {
                        foreach ($access_group_meta as $meta) {
                            foreach ($meta as $key => $value) {
                                if ($key === 'created') {
                                    echo "Fecha de Creación: " . date("d-m-Y H:i:s", $value) . "<br/>";
                                } else {
                                    echo ucfirst($key) . ": " . $value . "<br/>";
                                }
                            }
                        }
                    }
                }
            }
        }

        echo 'Roles del usuario:<br/>';
        foreach ($current_user->roles as $role) {
            echo $role . "<br/>";
        }

        $capabilities = $current_user->get_role_caps();
        if (isset($capabilities['suremember-usuario-activo'])) {
            echo 'Capacidad "suremember-usuario-activo": ' . ($capabilities['suremember-usuario-activo'] ? 'Sí' : 'No') . "<br/>";
        }
    } else {
        echo "El usuario no está logueado.";
    }
}

// Añadir la acción al hook especificado.
add_action(JLMR_HOOK_NAME, 'jlmr_show_user_info');
