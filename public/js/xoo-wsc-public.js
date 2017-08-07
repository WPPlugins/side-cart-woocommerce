jQuery(document).ready(function($){
	'use strict';

	//Toggle Side Cart
	function toggle_sidecart(){
		$('.xoo-wsc-modal , body').toggleClass('xoo-wsc-active');
	}
	$('.xoo-wsc-basket').on('click',toggle_sidecart);

	//Auto open Side Cart when item added to cart without ajax
	if(localize.added_to_cart){
		toggle_sidecart();
	}


	//Close Side Cart
	function close_sidecart(e){
		$.each(e.target.classList,function(key,value){
			if(value != 'xoo-wsc-container' && (value == 'xoo-wsc-close' || value == 'xoo-wsc-opac' || value == 'xoo-wsc-basket' || value == 'xoo-wsc-cont')){
				$('.xoo-wsc-modal , body').removeClass('xoo-wsc-active');
			}
		})
	}

	$('.xoo-wsc-close , .xoo-wsc-opac , .xoo-wsc-cont').click(close_sidecart);

	//Set Cart content height
	function content_height(){
		var header = $('.xoo-wsc-header').outerHeight(); 
		var footer = $('.xoo-wsc-footer').outerHeight();
		var screen = $(window).height();
		$('.xoo-wsc-body').outerHeight(screen-(header+footer));
	};
	content_height();
	$(window).resize(function(){
    	content_height();
	});
	
	//Refresh ajax fragments
	function refresh_ajax_fragm(ajax_fragm){
		var fragments = ajax_fragm.fragments;
		var cart_hash = ajax_fragm.cart_hash;
		var cart_html = ajax_fragm.fragments["div.widget_shopping_cart_content"];
		$('.woofc-trigger').css('transform','scale(1)');
		$('.shopping-cart-inner').html(cart_html);
		var cart_count = $('.cart_list:first').find('li').length;
		$('.shopping-cart span.counter , ul.woofc-count li').html(cart_count);
	}


	//Add to cart function
	function add_to_cart(atc_btn,item_id,quantity){
		$.ajax({
				url: localize.adminurl,
				type: 'POST',
				data: {action: 'add_to_cart',
					   item_id: item_id,
					   quantity: quantity},
			    success: function(response,status,jqXHR){
			   		atc_btn.find('.xoo-wsc-icon-atc').attr('class','xoo-wsc-icon-checkmark xoo-wsc-icon-atc');
					toggle_sidecart();
					on_cart_success(response);
			    }
			})
	}

	function on_cart_success(response){
	   		$('.xoo-wsc-content').html(response.cart_markup);
	   		$('.xoo-wsc-items-count').html(response.items_count);
	   		content_height();
	   		refresh_ajax_fragm(response.ajax_fragm);
	}

	//Update cart
	function update_cart(cart_key,new_qty){
		$('.xoo-wsc-updating').show();
		$.ajax({
			url: localize.adminurl,
			type: 'POST',
			data: {
				action: 'update_cart',
				cart_key: cart_key,
				new_qty: new_qty
			},
			success: function(response){
				on_cart_success(response);
		   		$('.xoo-wsc-updating').hide();
			}

		})
	}


	//Remove item from cart
	$(document).on('click','.xoo-wsc-remove',function(e){
		e.preventDefault();
		var product_row = $(this).parents('.xoo-wsc-product');
		var cart_key = product_row.data('xoo_wsc');
		update_cart(cart_key,0);
	})

	//Add to cart on single page
	if(localize.ajax_atc == 1){
		$(document).on('submit','form.cart',function(e){
			e.preventDefault();
			var atc_btn  = $(this).find('.single_add_to_cart_button');
			if(atc_btn.find('.xoo-wsc-icon-atc').length !== 0){
				atc_btn.find('.xoo-wsc-icon-atc').attr('class','xoo-wsc-icon-spinner xoo-wsc-icon-atc xoo-wsc-active');
			}
			else{
				atc_btn.append('<span class="xoo-wsc-icon-spinner xoo-wsc-icon-atc xoo-wsc-active"></span>');
			}

			var is_variation = $(this).find('[name=variation_id]');
			if(is_variation.length > 0){
				var item_id = parseInt($(this).find('[name=variation_id]').val());
			}
			else{
				var item_id = parseInt($(this).find('[name=add-to-cart]').val());
			}
			
			var quantity = parseInt($(this).find('.quantity').find('.qty').val());

			add_to_cart(atc_btn,item_id,quantity);//Ajax add to cart
		})
	}

	//Add to cart on shop page
	var shop_btn_default = '.add_to_cart_button';
	if(localize.shop_btn){
		var shop_btn = shop_btn_default+', '+localize.shop_btn;
	}
	else{
		var shop_btn = shop_btn_default;
	}
	
	$(shop_btn).on('click',function(e){
		var atc_btn = $(this);
		if(atc_btn.hasClass('product_type_variable')){return;}
		e.preventDefault();
		
		
		if(atc_btn.find('.xoo-wsc-icon-atc').length !== 0){
			atc_btn.find('.xoo-wsc-icon-atc').attr('class','xoo-wsc-icon-spinner xoo-wsc-icon-atc xoo-wsc-active');
		}
		else{
			atc_btn.append('<span class="xoo-wsc-icon-spinner xoo-wsc-icon-atc xoo-wsc-active"></span>');
		}
	

		var item_id = atc_btn.data('product_id');
		var quantity = 1;
		add_to_cart(atc_btn,item_id,quantity);//Ajax add to cart
	})
	

})