<?php
/**
 * Plugin Name: Student Manager
 * Description: Manage student and teacher profiles for Aisha Academy and Hifzul Quran.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: student-manager
 */

defined('ABSPATH') || exit;

if (!class_exists('Student_Manager')) {

    class Student_Manager {
        private static $debug_log;
        private static $instance;
        
        /**
         * Plugin initialization
         */
        public static function init() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$debug_log = plugin_dir_path(__FILE__) . 'debug.log';
                
                self::setup_hooks();
                self::log('Plugin initialized');
            }
            return self::$instance;
        }
        
        
        public static function add_custom_capabilities() {
    $admin = get_role('administrator');
    if ($admin && !$admin->has_cap('edit_instructors')) {
        $admin->add_cap('edit_instructors');
    }

    // Optional: for other roles like 'tutor_instructor'
    $instructor = get_role('tutor_instructor'); // replace with your custom role if needed
    if ($instructor && !$instructor->has_cap('edit_instructors')) {
        $instructor->add_cap('edit_instructors');
    }
}

        
        
        /**
         * Register all plugin hooks
         */
        private static function setup_hooks() {
            // Admin menus
            add_action('admin_menu', [__CLASS__, 'register_menus']);
            
            // Shortcodes
            add_shortcode('student_profile_form', [__CLASS__, 'render_student_profile_form_shortcode']);
            add_shortcode('instructor_career_form', [__CLASS__, 'render_instructor_career_form_shortcode']);
            
            register_activation_hook(__FILE__, [__CLASS__, 'activate']);
            register_deactivation_hook(__FILE__, [__CLASS__, 'deactivate']);
            
            // Load text domain for translations
            add_action('plugins_loaded', [__CLASS__, 'load_textdomain']);
        }
        
        /**
         * Load plugin textdomain for translations
         */
        public static function load_textdomain() {
            load_plugin_textdomain('student-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public static function activate() {
            self::log('Plugin activated');
            self::add_custom_capabilities();
            // Setup default options or database tables if needed
        }
        
        public static function deactivate() {
            self::log('Plugin deactivated');
            // Cleanup if needed
        }
        
        /**
         * Log messages to debug file
         */
        private static function log($message) {
            if (!defined('WP_DEBUG') || !WP_DEBUG) return;
            
            $timestamp = date('Y-m-d H:i:s');
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $backtrace[1]['function'] ?? 'unknown';
            
            $entry = sprintf(
                "[%s] %s - %s\n",
                $timestamp,
                $caller,
                is_string($message) ? $message : print_r($message, true)
            );
            
            file_put_contents(self::$debug_log, $entry, FILE_APPEND);
        }
        
        /**
         * Register admin menus
         */
        public static function register_menus() {
            // Main menu items
            $main_menus = [
                [
                    'page_title' => __('Aisha Academy Management', 'student-manager'),
                    'menu_title' => __('Aisha Academy', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'aisha-academy-management',
                    'function' => [__CLASS__, 'render_aisha_academy_page'],
                    'icon' => 'dashicons-welcome-learn-more'
                ],
                [
                    'page_title' => __('Hifzul Quran Management', 'student-manager'),
                    'menu_title' => __('Hifzul Quran', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'hifzul-quran-management',
                    'function' => [__CLASS__, 'render_hifzul_quran_page'],
                    'icon' => 'dashicons-book-alt'
                ],
                [
                    'page_title' => __('Teacher Management', 'student-manager'),
                    'menu_title' => __('Teacher Management', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'teacher-management',
                    'function' => [__CLASS__, 'render_teacher_page'],
                    'icon' => 'dashicons-businessperson'
                ]
            ];
            
            foreach ($main_menus as $menu) {
                add_menu_page(
                    $menu['page_title'],
                    $menu['menu_title'],
                    $menu['capability'],
                    $menu['menu_slug'],
                    $menu['function'],
                    $menu['icon']
                );
            }
            
            // Submenu items
            $submenus = [
                [
                    'parent_slug' => 'aisha-academy-management',
                    'page_title' => __('Edit Aisha Academy Student', 'student-manager'),
                    'menu_title' => __('Edit Student', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'edit-aisha-academy-student',
                    'function' => [__CLASS__, 'render_edit_aisha_academy_student']
                ],
             

                [
                    'parent_slug' => 'teacher-management',
                    'page_title'  => __('Edit Instructor', 'student-manager'),
                    'menu_title'  => __('Edit Instructor', 'student-manager'),
                    'capability'  => 'edit_instructors',
                    'menu_slug'   => 'edit-instructor-management',
                    'function'    => [__CLASS__, 'render_instructor_edit_managment']
                ],
                          
                [
                    'parent_slug' => 'aisha-academy-management',
                    'page_title' => __('Aisha Academy Student detail', 'student-manager'),
                    'menu_title' => __('Student Details', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'aisha-academy-student-details',
                    'function' => [__CLASS__, 'render_aisha_academy_student_detail']
                ],
                [
                    'parent_slug' => 'teacher-management',
                    'page_title' => __('instructor profile', 'student-manager'),
                    'menu_title' => __('instructor profile', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'instructor-profile',
                    'function' => [__CLASS__, 'render_Instructor_profile_detail']
                ],
                [
                    'parent_slug' => 'hifzul-quran-management',
                    'page_title' => __('Edit Hifzul Quran Student', 'student-manager'),
                    'menu_title' => __('Edit Student', 'student-manager'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'hifzul-quran-academy-edit',
                    'function' => [__CLASS__, 'render_edit_hizful_quran_student']
                ]
            ];
            
            foreach ($submenus as $submenu) {
                add_submenu_page(
                    $submenu['parent_slug'],
                    $submenu['page_title'],
                    $submenu['menu_title'],
                    $submenu['capability'],
                    $submenu['menu_slug'],
                    $submenu['function']
                );
            }
        }

        /**
         * Render admin pages
         */
        public static function render_aisha_academy_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/students-aisha.php';
        }

        public static function render_edit_aisha_academy_student() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/aisha-academy-edit.php';
        }
        

        
        
        public static function render_aisha_academy_student_detail() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/all_student-details.php';
        }
    
        public static function render_Instructor_profile_detail() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/instructor-profile.php';
        }

        public static function render_edit_hizful_quran_student() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/hifzul-quran-academy-edit.php';
        }

        public static function render_hifzul_quran_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/students-hifz.php';
        }

        public static function render_teacher_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
            }
            include plugin_dir_path(__FILE__) . 'includes/views/teachers.php';
        }

     public static function render_instructor_edit_managment() {
    if (!current_user_can('edit_instructors')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'student-manager'));
    }
    include plugin_dir_path(__FILE__) . 'includes/views/instructor-edit.php';
}

        


        /**
         * Render student profile form shortcode
         */
   
        public static function render_student_profile_form_shortcode() {
            ob_start();
            include plugin_dir_path(__FILE__) . 'includes/views/student-profile.php';
            return ob_get_clean();
        }
   
   
   
   
   
        public static function render_instructor_career_form_shortcode() {
            ob_start();
            include plugin_dir_path(__FILE__) . 'includes/views/instructor-career.php';
            return ob_get_clean();
        }
        
        
        
        
        /**
         * Handle Aisha Academy admission form submission
         */
         
         


    }

    /**
     * Enqueue plugin assets
     */
     
     add_action('admin_notices', function () {
    $user = wp_get_current_user();
    echo '<div class="notice notice-info"><p><strong>Role:</strong> ' . implode(', ', $user->roles) . '</p>';
    echo '<p><strong>Can edit_instructors:</strong> ' . (current_user_can('edit_instructors') ? 'Yes' : 'No') . '</p></div>';
});

     
    add_action('admin_enqueue_scripts', 'student_manager_enqueue_assets');
    function student_manager_enqueue_assets($hook) {
        $plugin_pages = [
            'aisha-academy-management',
            'hifzul-quran-management',
            'teacher-management',
            'edit-aisha-academy-student',
            'instructor-edit',
            'edit-instructor-managment',
            'hifzul-quran-academy-edit'
        ];
        
        if (!in_array($hook, $plugin_pages)) {
            return;
        }
        
        function load_jquery_for_custom_form() {
            wp_enqueue_script('jquery');
        }
        add_action('wp_enqueue_scripts', 'load_jquery_for_custom_form');

        
        // Enqueue Font Awesome
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
            [],
            '5.15.4'
        );
        
        // Enqueue plugin CSS
        wp_enqueue_style(
            'student-manager-css',
            plugin_dir_url(__FILE__) . 'assets/css/student-manager.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/student-manager.css')
        );
    
        wp_enqueue_style(
            'employeement-css',
            plugin_dir_url(__FILE__) . 'assets/css/employeement.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/employeement.css')
        );
        
        // Enqueue plugin JS
        wp_enqueue_script(
            'student-manager-js',
            plugin_dir_url(__FILE__) . 'assets/js/student-manager.js',
            ['jquery'],
            filemtime(plugin_dir_path(__FILE__) . 'assets/js/student-manager.js'),
            true
        );
        
        // Localize script for AJAX calls
        wp_localize_script(
            'student-manager-js',
            'studentManager',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('student_manager_nonce')
            ]
        );
    }

    // Initialize the plugin
    Student_Manager::init();
}