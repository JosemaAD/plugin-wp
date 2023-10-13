<?php
/*
Plugin Name: ZeMobile - CPT Project
Description: Este plugin registra un custom post type llamado 'project'.
Version: 1.0
Author: Jose Aranda Delgado
*/


require 'assets/custom-post-type.php';
require 'assets/js/jquery.php';

//Registrar el custom post type
add_action('init', 'registrar_tipo_de_contenido_project');
//Crear los 30 posts de prueba con contenido lorem ipsum
register_activation_hook( __FILE__, 'crear_30_posts_lorem_ipsum');
//Carga jQuery
add_action('wp_enqueue_scripts', 'load_jquery');

// Función que maneja la solicitud AJAX
add_action('wp_ajax_search_posts', 'search_posts');
add_action('wp_ajax_nopriv_search_posts', 'search_posts');

function search_posts() {
    $keyword = sanitize_text_field($_GET['keyword']);
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
    $args = array(
        'posts_per_page' => 6,
        'post_type' => 'project',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $page,
        's' => $keyword, 
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
                <div class="row mb-3 mt-3 mx-2 bg-white rounded-1 position-relative overflow-hidden">
                    <div class="col-12 px-4 py-2">
                        <h2 class="subtitle_home"><a href="<?php echo get_permalink(); ?>"><?php echo the_title(); ?></a></h2>
                        <p><?php the_excerpt() ?></p>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
    else :
        echo 'No se encontraron resultados.';
    endif;

    wp_reset_postdata();
    die(); 
}

// Shortcode 
add_shortcode('reel-posts_home', 'reel_posts_home_shortcode');

function reel_posts_home_shortcode($atts, $content = null) {
    ob_start();
    extract(shortcode_atts(array(
        'excerpt' => '',
    ), $atts));

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'posts_per_page' => 6,
        'post_type' => 'project',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
    ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <div class="container" id="my-posts-container">
            <div class="row mt-3 mb-3 justify-content-between">
                <!-- Agregar un campo de búsqueda -->
                <div class="col-md-6 mb-3">
                    <input type="text" id="keyword" placeholder="Palabra clave">
                    <button id="search-button">Buscar</button>
                </div>
            </div>
            <div class="row mt-3 mb-3 justify-content-between ">
                <?php
                while ($the_query->have_posts()) : $the_query->the_post();
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
                        <div class="row mb-3 mt-3 mx-2 bg-white rounded-1 position-relative overflow-hidden">
                            <div class="col-12 px-4 py-2">
                                <h2 class="subtitle_home"><a href="<?php echo get_permalink(); ?>"><?php echo the_title(); ?></a></h2>
                                <p><?php the_excerpt() ?></p>
                            </div>
                        </div>
                    </article>
                <?php
                endwhile;
                ?>
            </div><!-- END ROW -->
        </div><!-- END CONTAINER -->

        <?php
            // Paginación
            $total_pages = $the_query->max_num_pages;
            if ($total_pages > 1) {
                echo '<div class="pagination">';
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $total_pages,
                    'prev_text' => '&laquo; Anterior',
                    'next_text' => 'Siguiente &raquo;',
                ));
                echo '</div>';
            }
        ?>
        <style>
            .page-numbers {
                margin: 0 5px;
            }
        </style>

        <script>
            jQuery(function ($) {
                function loadPosts(keyword, page) {
                    var data = {
                        action: 'search_posts', // Nombre de la acción AJAX
                        keyword: keyword,
                        page: page
                    };

                    // solicitud AJAX
                    $.get('<?php echo admin_url('admin-ajax.php'); ?>', data, function (response) {
                        $('#my-posts-container').html(response);
                    });
                }

                $('#search-button').on('click', function () {
                    loadPosts($('#keyword').val(), 1); 
                });
            });
        </script>

    <?php
    endif;

    return ob_get_clean();
}
