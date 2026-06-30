<?php
defined('ABSPATH') || exit;

$items         = isset($items) ? $items : [];
$current_value = isset($current_value) ? $current_value : '';
$param_name    = isset($param_name) ? $param_name : '';
$type          = isset($type) ? $type : '';

if (empty($items)) {
    return;
}

$is_price  = $type === 'price';
$is_rating = $type === 'rating';
$is_radio  = $is_price || $is_rating;
?>
<div class="filter-checkbox-list">
    <?php foreach ($items as $item) :
        $is_active = false;
        $slug = '';
        $label = '';

        if ($is_price) {
            $min  = isset($item['min']) ? $item['min'] : '';
            $max  = isset($item['max']) ? $item['max'] : '';
            $label = $item['label'] ?? '';
            $cv_min = is_array($current_value) ? ($current_value['min'] ?? '') : '';
            $cv_max = is_array($current_value) ? ($current_value['max'] ?? '') : '';
            $is_active = ($cv_min === $min && $cv_max === $max);
            $value = $min . '-' . $max;
        } elseif ($is_rating) {
            $val  = $item['value'] ?? '';
            $label = $item['label'] ?? '';
            $cv = !is_array($current_value) ? $current_value : '';
            $is_active = ($cv === $val);
            $value = $val;
        } else {
            if (is_object($item)) {
                $slug  = $item->slug;
                $label = $item->name;
            } elseif (is_array($item)) {
                $slug  = $item['slug'] ?? '';
                $label = $item['name'] ?? '';
            }
            $current_group = is_array($current_value) ? $current_value : [];
            $is_active = in_array($slug, $current_group, true);
            $value = $slug;
        }
    ?>
        <label class="filter-checkbox">
            <input type="<?php echo $is_radio ? 'radio' : 'checkbox'; ?>"
                   name="<?php echo esc_attr($param_name); ?>"
                   value="<?php echo esc_attr($value); ?>"
                   data-filter-type="<?php echo esc_attr($type); ?>"
                   data-filter-param="<?php echo esc_attr($param_name); ?>"
                   <?php if ($is_price) : ?>
                   data-min-price="<?php echo esc_attr($min); ?>"
                   data-max-price="<?php echo esc_attr($max); ?>"
                   <?php endif; ?>
                   <?php echo $is_active ? 'checked' : ''; ?>>
            <span class="filter-checkbox-visual"></span>
            <span class="filter-checkbox-label"><?php echo esc_html($label); ?></span>
        </label>
    <?php endforeach; ?>
</div>
<?php
