'use strict';

jQuery(document).ready(function($) {
  if (!$('.woobt_products').length) {
    return;
  }

  var $woobt_products = $('.woobt_products').eq(0);
  var $woobt_ids = $('.woobt_ids').eq(0);
  var $woobt_total = $('.woobt_total').eq(0);
  var $woobt_btn = $woobt_ids.closest('form.cart').
      find('.single_add_to_cart_button');
  if (!$woobt_btn.length) {
    $woobt_btn = $woobt_products.closest('.summary').
        find('.single_add_to_cart_button');
  }
  var woobt_btn_text = $woobt_btn.text();
  var woobt_show_price = $woobt_products.attr('data-show-price');
  var woobt_optional = $woobt_products.attr('data-optional');
  var woobt_sync_qty = $woobt_products.attr('data-sync-qty');

  if (!$woobt_btn.length || !$woobt_ids.length) {
    console.log(
        'Have an issue with your template, so might WPC Bought Together doesn\'t work completely. Please contact us via email contact@wpclever.net to get the help.');
  }

  if ((
      woobt_optional == 'on'
  ) && (
      $woobt_products.find('.woobt-product-this').length > 0
  )) {
    $('form.cart .quantity').hide();
  }

  if ((woobt_vars.position == 'before') &&
      ($woobt_products.attr('data-product-type') == 'variable') &&
      ($woobt_products.attr('data-variables') == 'no')) {
    $woobt_products.closest('.woobt_wrap').insertAfter($woobt_ids);
  }

  woobt_check_ready();
  woobt_save_ids();

  $(document).on('found_variation', function(e, t) {
    var _this_product = $(e['target']).closest('.woobt-product');

    if (_this_product.length) {
      var _this_price = _this_product.attr('data-price');

      if (isNaN(_this_price)) {
        _this_price = t['display_price'] *
            parseInt(_this_product.attr('data-price')) / 100;
      }

      _this_product.find('.woobt-price-ori').hide();
      _this_product.find('.woobt-price-new').
          html(woobt_total_html(_this_price)).
          show();

      if (t['is_purchasable'] && t['is_in_stock']) {
        _this_product.attr('data-id', t['variation_id']);
        _this_product.attr('data-price-ori', t['display_price']);
      } else {
        _this_product.attr('data-id', 0);
      }

      // change availability text
      jQuery(e['target']).closest('.variations_form').find('p.stock').remove();
      if (t['availability_html'] != '') {
        jQuery(e['target']).
            closest('.variations_form').
            append(t['availability_html']);
      }

      if (t['image']['url'] && t['image']['srcset']) {
        // change image
        _this_product.find('.woobt-thumb-ori').hide();
        _this_product.find('.woobt-thumb-new').
            html('<img src="' + t['image']['url'] + '" srcset="' +
                t['image']['srcset'] + '"/>').
            show();
      }

      // reset sku
      jQuery('.product_meta .sku').
          text($woobt_products.attr('data-product-sku'));

      if (woobt_vars.change_image == 'no') {
        // prevent changing the main image
        jQuery(e['target']).closest('.variations_form').trigger('reset_image');
      }
    } else {
      $woobt_products.attr('data-product-price', t['display_price']);
      $woobt_products.attr('data-product-sku', t['sku']);
    }

    woobt_check_ready();
  });

  $(document).on('reset_data', function(e) {
    var _this_product = $(e['target']).closest('.woobt-product');

    if (_this_product.length) {
      _this_product.attr('data-id', 0);

      // reset stock
      jQuery(e['target']).closest('.variations_form').find('p.stock').remove();

      // reset sku
      jQuery('.product_meta .sku').
          text($woobt_products.attr('data-product-sku'));

      // reset thumb
      _this_product.find('.woobt-thumb-new').hide();
      _this_product.find('.woobt-thumb-ori').show();
    } else {
      $woobt_products.attr('data-product-price', 0);
      $woobt_products.attr('data-product-sku',
          $woobt_products.attr('data-product-o-sku'));
    }

    woobt_check_ready();
  });

  jQuery(document).on('woovr_selected', function(e, selected, variations) {
    var _this_product = variations.closest('.woobt-product');

    if (_this_product.length) {
      var _id = selected.attr('data-id');
      var _price = selected.attr('data-price');
      var _purchasable = selected.attr('data-purchasable');

      if (_purchasable == 'yes') {
        _this_product.attr('data-id', _id);
        _this_product.attr('data-price-ori', _price);
      } else {
        _this_product.attr('data-id', 0);
        _this_product.attr('data-price-ori', 0);
      }
    }

    woobt_check_ready();
  });

  $woobt_products.find('select').on('change', function() {
    $(this).closest('.woobt-product').attr('data-id', 0);
  });

  $woobt_products.find('.woobt-checkbox').on('change', function() {
    woobt_check_ready();
  });

  $woobt_btn.closest('form.cart').find('input.qty').on('change', function() {
    var qty = parseInt($(this).val());

    if ((woobt_optional != 'on') && (woobt_sync_qty == 'on')) {
      $woobt_products.find('.woobt-product-together').each(function() {
        var item_qty = parseInt($(this).attr('data-qty-ori')) * qty;
        $(this).attr('data-qty', item_qty);
        $(this).find('.woobt-qty-num').html(item_qty);
      });
    }

    if (woobt_vars.counter != 'hide') {
      woobt_update_count();
    }

    woobt_calc_price();
    woobt_save_ids();
  });

  $woobt_products.on('change keyup mouseup', '.woobt-this-qty',
      function() {
        var this_val = $(this).val();
        $(this).
            closest('.summary').
            find('form.cart .quantity .qty').
            val(this_val).
            trigger('change');
      });

  $woobt_products.on('change keyup mouseup', '.woobt-qty',
      function() {
        var _this = $(this);
        var this_val = parseInt(_this.val());
        var _this_checkbox = $(this).
            closest('.woobt-product').
            find('.woobt-checkbox');
        if (_this_checkbox.prop('checked')) {
          var this_min = parseInt(_this.attr('min'));
          var this_max = parseInt(_this.attr('max'));

          if (this_val < this_min) {
            _this.val(this_min);
          }
          if (this_val > this_max) {
            _this.val(this_max);
          }
          _this.closest('.woobt-product').attr('data-qty', _this.val());
          woobt_check_ready();
        }
      });

  $woobt_btn.on('click touch', function(e) {
    if ($(this).hasClass('woobt-selection')) {
      alert(woobt_vars.alert_selection);
      e.preventDefault();
    }
  });

  function woobt_check_ready() {
    var is_selection = false;

    $woobt_products.find('.woobt-product-together').each(function() {
      var _this = $(this);
      var item_checked = _this.find('.woobt-checkbox').prop('checked');
      var item_id = parseInt(_this.attr('data-id'));

      if (!item_checked) {
        $(this).addClass('woobt-hide');
      } else {
        $(this).removeClass('woobt-hide');
      }

      if (item_checked && (item_id == 0)) {
        is_selection = true;
      }
    });

    if (is_selection) {
      $woobt_btn.addClass('woobt-disabled woobt-selection');
    } else {
      $woobt_btn.removeClass('woobt-disabled woobt-selection');
    }

    woobt_calc_price();
    woobt_save_ids();
  }

  function woobt_calc_price(act) {
    var total = 0;
    var total_ori = 0;
    var total_html = '';
    var total_ori_html = '';
    var discount = parseFloat($woobt_products.attr('data-discount'));
    var ori_price = parseFloat($woobt_products.attr('data-product-price'));
    var ori_qty = parseInt(
        $woobt_btn.closest('form.cart').find('input.qty').val());

    var price_selector = '.summary > .price';
    if ((woobt_vars.price_selector != null) &&
        (woobt_vars.price_selector != '')) {
      price_selector = woobt_vars.price_selector;
    }

    $woobt_products.find('.woobt-product-together').each(function() {
      var _this = $(this);
      var item_checked = _this.find('.woobt-checkbox').prop('checked');
      var item_qty = _this.attr('data-qty');
      var item_id = parseInt(_this.attr('data-id'));
      var item_price = _this.attr('data-price');
      var item_price_ori = _this.attr('data-price-ori');
      var item_total = 0;

      if (item_checked && (item_qty > 0) && (item_id > 0)) {
        if (isNaN(item_price)) {
          //is percent
          item_total = item_qty * item_price_ori * parseInt(item_price) / 100;
        } else {
          item_total = item_qty * item_price;
        }

        if (woobt_show_price == 'total') {
          _this.find('.woobt-price-ori').hide();
          _this.find('.woobt-price-new').
              html(woobt_total_html(item_total)).
              show();
        }

        //calc total
        total += item_total;
      } else {
        _this.find('.woobt-price-new').hide();
        _this.find('.woobt-price-ori').show();
      }
    });

    if (total > 0) {
      total_html = woobt_total_html(total);
      $woobt_total.html(woobt_vars.total_price_text + ' ' + total_html).
          slideDown();

      if (isNaN(discount)) {
        discount = 0;
      }

      total_ori = ori_price * ori_qty * (100 - discount) / 100 + total;
      total_ori_html = woobt_total_html(total_ori);

      // change the main price
      if (woobt_vars.change_price != 'no') {
        $(price_selector).html(total_ori_html);
      }

      // this product
      $woobt_products.find('.woobt-product-this .woobt-price-ori').hide();
      $woobt_products.find('.woobt-product-this .woobt-price-new').show();
    } else {
      $woobt_total.html('').slideUp();

      // change the main price
      if (woobt_vars.change_price != 'no') {
        $(price_selector).html(woobt_total_html(ori_price));
      }

      // this product
      $woobt_products.find('.woobt-product-this .woobt-price-ori').show();
      $woobt_products.find('.woobt-product-this .woobt-price-new').hide();
    }

    if (act == 'return') {
      return total;
    } else {
      $(document).trigger('woobt_calc_price', [total, total_html]);
    }
  }

  function woobt_save_ids() {
    var woobt_items = new Array();

    $woobt_products.find('.woobt-product-together').each(function() {
      var _this = $(this);
      var item_checked = _this.find('.woobt-checkbox').prop('checked');
      var item_qty = _this.attr('data-qty');
      var item_price = _this.attr('data-price');
      var item_id = _this.attr('data-id');

      if (item_checked && (item_qty > 0) && (item_id > 0)) {
        woobt_items.push(item_id + '/' + item_price + '/' + item_qty);
      }
    });

    if (woobt_items.length > 0) {
      $woobt_ids.val(woobt_items.join(','));
    } else {
      $woobt_ids.val('');
    }

    if (woobt_vars.counter != 'hide') {
      woobt_update_count();
    }
  }

  function woobt_update_count() {
    var woobt_qty = 0;
    var woobt_num = 1;

    $woobt_products.find('.woobt-product-together').each(function() {
      var _this = $(this);
      var item_checked = _this.find('.woobt-checkbox').prop('checked');
      var item_qty = _this.attr('data-qty');
      var item_id = _this.attr('data-id');

      if (item_checked && (item_qty > 0) && (item_id > 0)) {
        woobt_qty += parseInt($(this).attr('data-qty'));
        woobt_num++;
      }
    });

    if ($woobt_btn.closest('form.cart').find('input.qty').length) {
      woobt_qty += parseInt(
          $woobt_btn.closest('form.cart').find('input.qty').val());
    }

    if (woobt_num > 1) {
      if (woobt_vars.counter == 'individual') {
        $woobt_btn.text(woobt_btn_text + ' (' + woobt_num + ')');
      } else {
        $woobt_btn.text(woobt_btn_text + ' (' + woobt_qty + ')');
      }
    } else {
      $woobt_btn.text(woobt_btn_text);
    }

    $(document.body).trigger('woobt_update_count', [woobt_num, woobt_qty]);
  }

  function woobt_format_money(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '$';
    thousand = thousand !== undefined ? thousand : ',';
    decimal = decimal !== undefined ? decimal : '.';
    var negative = number < 0 ? '-' : '',
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + '',
        j = 0;
    if (i.length > 3) {
      j = i.length % 3;
    }
    return symbol + negative + (
        j ? i.substr(0, j) + thousand : ''
    ) + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (
        places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : ''
    );
  }

  function woobt_total_html(total, prefix) {
    var total_html = '<span class="woocommerce-Price-amount amount">';
    var total_formatted = woobt_format_money(total, woobt_vars.price_decimals,
        '', woobt_vars.price_thousand_separator,
        woobt_vars.price_decimal_separator);

    switch (woobt_vars.price_format) {
      case '%1$s%2$s':
        //left
        total_html += '<span class="woocommerce-Price-currencySymbol">' +
            woobt_vars.currency_symbol + '</span>' + total_formatted;
        break;
      case '%1$s %2$s':
        //left with space
        total_html += '<span class="woocommerce-Price-currencySymbol">' +
            woobt_vars.currency_symbol + '</span> ' + total_formatted;
        break;
      case '%2$s%1$s':
        //right
        total_html += total_formatted +
            '<span class="woocommerce-Price-currencySymbol">' +
            woobt_vars.currency_symbol + '</span>';
        break;
      case '%2$s %1$s':
        //right with space
        total_html += total_formatted +
            ' <span class="woocommerce-Price-currencySymbol">' +
            woobt_vars.currency_symbol + '</span>';
        break;
      default:
        //default
        total_html += '<span class="woocommerce-Price-currencySymbol">' +
            woobt_vars.currency_symbol + '</span> ' + total_formatted;
    }

    total_html += '</span>';

    if (prefix != null) {
      return prefix + total_html;
    }

    return total_html;
  }
});