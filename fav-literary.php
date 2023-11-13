<?php
/*
Plugin Name: Twoje Ulubione 
Description: Dodawaj/twórz listy ulubionych tekstów na blogu
Version: 1.0
Author: Ja Kamil
*/

class UlubioneSettings
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_filter('the_content', array($this, 'ifWrap'));


        add_action('wp_enqueue_scripts', array($this, 'add_scripts'));
    }

    function adminPage()
    {
        add_options_page('Ulubione', 'Ulubione Ustawienia', 'manage_options', 'ulubione-ustawienia', array($this, 'basicHTML'));
    }

    function basicHTML()
    { ?>
        <div class="wrap">
            <h1> Ulubione - Ustawienia Wtyczki</h1>
            <form method="POST" action="options.php ">
                <?php
                settings_fields('ulubioneplugin');
                do_settings_sections('ulubione-ustawienia');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    function registerSettings()
    {
        add_settings_section('ulub_location_section', 'Gdzie mają znajdować się Ulubione?', null, 'ulubione-ustawienia');
        add_settings_field('ulub_location', 'Wyświetl lokalizację', array($this, 'locationHtml'), 'ulubione-ustawienia', 'ulub_location_section');
        register_setting('ulubioneplugin', 'ulub_location', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
        // Checkbox "Ulubione"
        add_settings_field('ulub_dodaj', 'Ulubione', array($this, 'loveCheckbox'), 'ulubione-ustawienia', 'ulub_location_section');
        register_setting('ulubioneplugin', 'ulub_dodaj', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
        // Checkbox "Chcę Przeczytać"
        add_settings_field('ulub_chce', 'Chcę Przeczytać', array($this, 'chceCheckbox'), 'ulubione-ustawienia', 'ulub_location_section');
        register_setting('ulubioneplugin', 'ulub_chce', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
        // Checkbox "Przeczytane"
        add_settings_field('ulub_przeczytane', 'Przeczytane', array($this, 'przeczytaneCheckbox'), 'ulubione-ustawienia', 'ulub_location_section');
        register_setting('ulubioneplugin', 'ulub_przeczytane', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    function locationHtml()
    { ?>
        <select name="ulub_location">
            <option value="0" <?php selected(get_option('ulub_location', '0')) ?>> Na końcu wpisu</option>
            <option value="1" <?php selected(get_option('ulub_location', '1')) ?>> Na początku wpisu</option>
        </select>
    <?php }

    function loveCheckbox()
    { ?>
        <input name="ulub_dodaj" type="checkbox" value="1" <?php checked(get_option('ulub_dodaj', '1')) ?>>
    <?php }

    function chceCheckbox()
    { ?>
        <input name="ulub_chce" type="checkbox" value="1" <?php checked(get_option('ulub_chce', '1')) ?>>
    <?php }

    function przeczytaneCheckbox()
    { ?>
        <input name="ulub_przeczytane" type="checkbox" value="1" <?php checked(get_option('ulub_przeczytane', '1')) ?>>
    <?php }

    function ifWrap($content)
    {
        if (is_main_query() and is_single() and (get_option('ulub_dodaj', '1') or get_option('ulub_chce', '1') or get_option('ulub_przeczytane', '1'))) {
            return $this->displayHTML($content);
        }
        return $content;
    }


    function displayHTML($content)
    {
        $html = '<div class="ulubione-wrapper">';
        $html .= '<i class="fa fa-star" id="icon-star"></i>';
        $html .= '<i class="fa fa-heart" id="icon-heart"></i>';
        $html .= '<i class="fa fa-check" id="icon-check"></i>';
        $html .= '</div>';

        return $content . $html;

    }

    function add_scripts()
    {
        wp_enqueue_script('ulubionejs', plugins_url('/assets/main.js', __FILE__));
        wp_enqueue_style('ulubionestyle', plugins_url('/assets/style.css', __FILE__));
    }

}

$ulubioneSettings = new UlubioneSettings();

class TemplateManager
{
    public function templateArray($templates)
    {
        $templates['ulubione.php'] = 'ulubione-page';
        return $templates;
    }

    public function templateRegister($page_templates)
    {
        $templates = $this->templateArray($page_templates);
        foreach ($templates as $tk => $tv) {
            $page_templates[$tk] = $tv;
        }
        return $page_templates;
    }

    public function templateSelect($template)
    {
        $templates      = $this->templateArray([]);
        $page_temp_slug = get_page_template_slug(get_the_ID());
        if (isset($templates[$page_temp_slug])) {
            $template = plugin_dir_path(__FILE__) . "templates/" . $page_temp_slug;
        }
        return $template;
    }

    public function init()
    {
        add_filter('theme_page_templates', array($this, 'templateRegister'));
        add_filter('template_include', array($this, 'templateSelect'), 99);
    }
}

$templateManager = new TemplateManager();
$templateManager->init();
