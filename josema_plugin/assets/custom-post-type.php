<?php

// Registro del tipo de contenido personalizado 'project'
function registrar_tipo_de_contenido_project() {
    $labels = array(
        'name'               => 'Proyectos',
        'singular_name'      => 'Proyecto',
        'menu_name'          => 'Proyectos',
        'add_new'            => 'Agregar Nuevo Proyecto',
        'add_new_item'       => 'Agregar Nuevo Proyecto',
        'edit_item'          => 'Editar Proyecto',
        'new_item'           => 'Nuevo Proyecto',
        'view_item'          => 'Ver Proyecto',
        'search_items'       => 'Buscar Proyectos',
        'not_found'          => 'No se encontraron proyectos',
        'not_found_in_trash' => 'No se encontraron proyectos en la papelera',
        'parent_item_colon'  => 'Proyecto Padre:',
        'menu_icon'          => 'dashicons-portfolio', // Ícono para el menú
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'project'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
    );

    register_post_type('project', $args);
}

//add_action('init', 'registrar_tipo_de_contenido_project');


function crear_30_posts_lorem_ipsum() {
    for ($i = 1; $i <= 30; $i++) {

        $post_data = array(
            'post_title'    => 'Proyecto de Ejemplo #' . $i,
            'post_content'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec est vel libero bibendum fringilla ac sit amet dui. Integer sit amet risus et dui lacinia vehicula. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. Nulla facilisi.',
            'post_excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'post_status'   => 'publish',
            'post_type'     => 'project',
        );
        wp_insert_post($post_data);
    }
}

//register_activation_hook( __FILE__, 'crear_30_posts_lorem_ipsum');

