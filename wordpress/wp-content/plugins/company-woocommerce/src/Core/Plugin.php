<?php
namespace Company\WooCommerce\Core;

use Company\WooCommerce\Filter\Module as FilterModule;
use Company\WooCommerce\Search\Module as SearchModule;
use Company\WooCommerce\Testimonial\Module as TestimonialModule;
use Company\WooCommerce\Language\Module as LanguageModule;
use Company\WooCommerce\Wishlist\Module as WishlistModule;
use Company\WooCommerce\ZaloContact\Module as ZaloContactModule;

defined('ABSPATH') || exit;

class Plugin
{
    private static $instance = null;

    protected $modules = [
        'filter'       => [
            'enabled'     => true,
            'class'       => FilterModule::class,
            'description' => 'Hiển thị bộ lọc sản phẩm (danh mục, thẻ, thương hiệu, thuộc tính, giá, đánh giá) trên trang cửa hàng.',
            'usage'       => 'Vào <strong>Company → Filter Settings</strong> để cấu hình loại bộ lọc muốn hiển thị. Module này tự động thêm sidebar bộ lọc lên trang cửa hàng WooCommerce, không cần thêm shortcode hay template nào.',
        ],
        'search'       => [
            'enabled'     => true,
            'class'       => SearchModule::class,
            'description' => 'Bật tìm kiếm sản phẩm với AJAX, hiển thị kết quả ngay khi gõ.',
            'usage'       => 'Module tự động kích hoạt tìm kiếm AJAX trên ô tìm kiếm sản phẩm. Không cần thêm shortcode.',
        ],
        'testimonial'  => [
            'enabled'     => true,
            'class'       => TestimonialModule::class,
            'description' => 'Quản lý khách hàng gửi đánh giá qua form, bài đánh giá là post trong chuyên mục "Testimonial".',
            'usage'       =>
'<p><strong>1. Form gửi đánh giá</strong><br>Dùng shortcode:</p>
<pre><code>[submit_testimonial_form]</code></pre>
<p>Tạo một trang, chọn template "Submit Testimonial" (có sẵn trong theme), hoặc chèn shortcode vào bài viết.</p>

<p><strong>2. Hiển thị danh sách đánh giá</strong><br>Module không có shortcode hiển thị sẵn. Bạn có thể đặt đoạn code này vào template PHP:</p>
<pre><code>&lt;?php
$testimonials = new WP_Query([
    \'category_name\'  =&gt; \'testimonial\',
    \'posts_per_page\' =&gt; -1,
    \'post_status\'    =&gt; \'publish\',
]);
if ($testimonials-&gt;have_posts()) :
    while ($testimonials-&gt;have_posts()) : $testimonials-&gt;the_post();
        $role = get_post_meta(get_the_ID(), \'_testimonial_role\', true) ?: \'Verified Reader\';
        ?&gt;
        &lt;div class="testimonial-card"&gt;
            &lt;h3&gt;&lt;?php the_title(); ?&gt;&lt;/h3&gt;
            &lt;p class="role"&gt;&lt;?php echo esc_html($role); ?&gt;&lt;/p&gt;
            &lt;p&gt;&lt;?php the_excerpt(); ?&gt;&lt;/p&gt;
        &lt;/div&gt;
    &lt;?php endwhile;
    wp_reset_postdata();
endif;
?&gt;</code></pre>
<p>Theme mặc định hiển thị 3 đánh giá gần nhất ở trang chủ (front-page.php). Bạn có thể copy đoạn trên vào bất kỳ template nào.</p>

<p><strong>3. Quản trị</strong><br>Vào <strong>Posts → Add New</strong>, gán chuyên mục "Testimonial" để tạo đánh giá thủ công. Hoặc vào một bài viết đã có chuyên mục "Testimonial" để chỉnh sửa thông tin (vai trò,...).</p>',
        ],
        'language'     => [
            'enabled'     => true,
            'class'       => LanguageModule::class,
            'description' => 'Cho phép chuyển đổi ngôn ngữ (Tiếng Việt / Tiếng Anh) trên frontend. Có thể cấu hình ngôn ngữ mặc định.',
            'usage'       => 'Người dùng có thể chuyển đổi ngôn ngữ qua dropdown/flag có sẵn trong theme. Cấu hình ngôn ngữ mặc định tại <strong>Settings → General</strong> (Site Language).',
        ],
        'wishlist'     => [
            'enabled'     => false,
            'class'       => WishlistModule::class,
            'description' => 'Cho phép khách hàng lưu sản phẩm vào danh sách yêu thích (wishlist).',
            'usage'       => '<p><strong>Cách dùng</strong></p>
<ol>
<li>Bật module này lên.</li>
<li>Vào <strong>Settings → Permalinks</strong> và nhấn <strong>Save Changes</strong> để flush rewrite rules (chỉ cần làm 1 lần).</li>
<li>Sau khi đăng nhập, khách hàng thấy nút ♥ trên mỗi sản phẩm (trang danh sách & trang chi tiết).</li>
<li>Vào <strong>My Account → Wishlist</strong> để xem danh sách yêu thích và thêm vào giỏ hàng.</li>
</ol>',
        ],
        'zalo_contact' => [
            'enabled'     => false,
            'class'       => ZaloContactModule::class,
            'description' => 'Hiển thị nút Zalo liên hệ dạng floating trên toàn bộ trang web.',
            'usage'       => 'Vào <strong>Company → Zalo Contact</strong> để cấu hình:<br>• Số điện thoại Zalo<br>• Tin nhắn mặc định<br>• Hiển thị trên trang (chọn trang cụ thể hoặc tất cả)<br>• Ẩn trên mobile<br>Module tự động hiển thị nút Zalo floating, không cần thêm shortcode.',
        ],
    ];

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init()
    {
        spl_autoload_register([$this, 'autoload']);

        // Register locale filter early so WooCommerce translations work
        add_filter('locale', [LanguageModule::class, 'set_locale']);

        // Load plugin translations
        load_plugin_textdomain('company-woocommerce', false, 'company-woocommerce/languages');

        if (is_admin()) {
            AdminPage::init();
        }

        add_action('plugins_loaded', [$this, 'init_modules'], 11);
    }

    public function autoload($class)
    {
        $prefix = 'Company\\WooCommerce\\';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative_class = substr($class, $len);
        $file = COMPANY_WOO_PATH . 'src/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }

    public function init_modules()
    {
        $saved = get_option('company_modules_settings', []);
        if (!is_array($saved)) {
            $saved = [];
        }

        foreach ($this->modules as $key => $config) {
            $enabled = $config['enabled'];

            if (array_key_exists($key, $saved)) {
                $enabled = (bool) $saved[$key];
            }

            if (!$enabled || !$config['class']) {
                continue;
            }
            $module = new $config['class']();
            if (method_exists($module, 'init')) {
                $module->init();
            }
        }
    }

    public function get_modules()
    {
        return $this->modules;
    }
}
