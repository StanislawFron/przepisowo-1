<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package przepisy
 */
get_header();

if (is_front_page()) {
    require 'tpl-home.php';
}
else {
    if(get_field("typ_strony")) {
        if(get_field("typ_strony") == "Strona główna") {
            require 'tpl-home.php';
        }
        else if(get_field("typ_strony") == "Przepis") {
            require 'tpl-przepis.php';
        } 
        else if(get_field("typ_strony") == "Login") {
            require 'tpl-login.php';
        } 
        else if(get_field("typ_strony") == "Logout") {
            require 'logout.php';
        }
        else if(get_field("typ_strony") == "User") {
            require 'user.php';
        } 
        else if(get_field("typ_strony") == "Kalendarz") {
            require 'user-calendar.php';
        }
        else if(get_field("typ_strony") == "Dodaj przepis") {
            require 'user-addRecipe.php';
        }
        else if(get_field("typ_strony") == "Lista zakupów") {
            require 'user-shoppingList.php';
        }
        else if(get_field("typ_strony") == "Moje przepisy") {
            require 'user-myRecipes.php';
        }
        else if(get_field("typ_strony") == "Edytuj przepis") {
            require 'user-editRecipe.php';
        }
         else if(get_field("typ_strony") == "Dodaj składnik") {
            require 'user-addIngredient.php';
        }
        else {
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', 'page' );
            endwhile;
        }
    }
    else {
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'page' );
        endwhile;
    }
}

get_footer();
