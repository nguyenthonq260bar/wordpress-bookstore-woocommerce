<?php
namespace Company\WooCommerce\Testimonial;

defined('ABSPATH') || exit;

class Shortcode
{
    public static function init()
    {
        add_shortcode('submit_testimonial_form', [__CLASS__, 'render']);
    }

    public static function render()
    {
        $form_success = '';
        $form_error   = '';

        if (FormHandler::get_message()) {
            if (FormHandler::get_type() === 'success') {
                $form_success = FormHandler::get_message();
            } else {
                $form_error = FormHandler::get_message();
            }
        }

        ob_start();
        $current_user = wp_get_current_user();
?>
<div style="max-width:620px;margin:0 auto;">
  <?php if ($form_success) : ?>
    <div style="padding:16px 20px;background:#e8f5e9;color:#2e7d32;border-radius:8px;margin-bottom:24px;font-weight:500;">
      <?php echo esc_html($form_success); ?>
    </div>
  <?php endif; ?>

  <?php if ($form_error) : ?>
    <div style="padding:16px 20px;background:#fbe9e7;color:#c62828;border-radius:8px;margin-bottom:24px;font-weight:500;">
      <?php echo esc_html($form_error); ?>
    </div>
  <?php endif; ?>

  <?php if (!$form_success) : ?>
  <form method="post" style="display:flex;flex-direction:column;gap:20px;">
    <div>
      <label for="t_name" style="display:block;font-weight:600;margin-bottom:6px;font-size:14px;">
        <?php echo _t('Your Name', 'Tên của bạn'); ?> <span style="color:#e53935;">*</span>
      </label>
      <input type="text" id="t_name" name="t_name" required
             value="<?php echo is_user_logged_in() ? esc_attr($current_user->display_name) : ''; ?>"
             style="width:100%;padding:12px 14px;font-size:15px;border:1px solid var(--color-border,#C1C7DF);border-radius:6px;background:var(--white,#fff);color:var(--color-heading,#3760BF);">
    </div>

    <div>
      <label for="t_email" style="display:block;font-weight:600;margin-bottom:6px;font-size:14px;">
        <?php echo _t('Email address', 'Địa chỉ email'); ?>
      </label>
      <input type="email" id="t_email" name="t_email"
             value="<?php echo is_user_logged_in() ? esc_attr($current_user->user_email) : ''; ?>"
             style="width:100%;padding:12px 14px;font-size:15px;border:1px solid var(--color-border,#C1C7DF);border-radius:6px;background:var(--white,#fff);color:var(--color-heading,#3760BF);"
             placeholder="<?php echo esc_attr(_t('Optional — not shown publicly', 'Không bắt buộc — không hiển thị công khai')); ?>">
    </div>

    <div>
      <label for="t_role" style="display:block;font-weight:600;margin-bottom:6px;font-size:14px;">
        <?php echo _t('Role', 'Vai trò'); ?>
      </label>
      <input type="text" id="t_role" name="t_role"
             style="width:100%;padding:12px 14px;font-size:15px;border:1px solid var(--color-border,#C1C7DF);border-radius:6px;background:var(--white,#fff);color:var(--color-heading,#3760BF);"
             placeholder="<?php echo esc_attr(_t('e.g. Verified Reader, Book Club Member', 'VD: Người đọc đã xác thực, Thành viên CLB Sách')); ?>">
    </div>

    <div>
      <label for="t_content" style="display:block;font-weight:600;margin-bottom:6px;font-size:14px;">
        <?php echo _t('Your Testimonial', 'Đánh giá của bạn'); ?> <span style="color:#e53935;">*</span>
      </label>
      <textarea id="t_content" name="t_content" required rows="5"
                style="width:100%;padding:12px 14px;font-size:15px;border:1px solid var(--color-border,#C1C7DF);border-radius:6px;background:var(--white,#fff);color:var(--color-heading,#3760BF);resize:vertical;font-family:inherit;"
                placeholder="<?php echo esc_attr(_t('Tell us about your experience...', 'Chia sẻ trải nghiệm của bạn...')); ?>"></textarea>
    </div>

    <div style="display:none;">
      <input type="text" name="_hp_comment" value="" tabindex="-1" autocomplete="off">
    </div>

    <?php wp_nonce_field('submit_testimonial_action', '_submit_testimonial_nonce'); ?>
    <input type="hidden" name="submit_testimonial" value="1">

    <button type="submit" style="align-self:flex-start;padding:14px 36px;font-size:16px;font-weight:600;font-family:inherit;color:#fff;background:var(--accent,#f0883e);border:none;border-radius:8px;cursor:pointer;transition:background 0.2s,transform 0.15s;">
      <?php echo _t('Submit Testimonial', 'Gửi đánh giá'); ?>
    </button>
  </form>
  <?php endif; ?>
</div>
<?php
        return ob_get_clean();
    }
}
