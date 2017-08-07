<?php
class xoo_wsc_Cart_Data{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $xoo_wsc    The ID of this plugin.
	 */
	private $xoo_wsc;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $xoo_wsc    The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $xoo_wsc ) {

		$this->xoo_wsc = $xoo_wsc;

	}

	/**
	 * Formats the RAW woocommerce price
	 *
	 * @since     1.0.0
	 * @param  	  int $price
	 * @return    string 
	 */

	public function formatted_price($price){
		if(!$price)
			return;
		$options 	= get_option('xoo-wsc-gl-options');
		$default_wc = isset( $options['sc-price-format']) ? $options['sc-price-format'] : 0;
		
		if($default_wc == 1){
			return wc_price($price);
		}

		$thous_sep = wc_get_price_thousand_separator();
		$dec_sep   = wc_get_price_decimal_separator();
		$decimals  = wc_get_price_decimals();
		$price 	   = number_format( $price, $decimals, $dec_sep, $thous_sep );

		$format   = get_option( 'woocommerce_currency_pos' );
		$csymbol  = get_woocommerce_currency_symbol();

		switch ($format) {
			case 'left':
				$fm_price = $csymbol.$price;
				break;

			case 'left_space':
				$fm_price = $csymbol.' '.$price;
				break;

			case 'right':
				$fm_price = $price.$csymbol;
				break;

			case 'right_space':
				$fm_price = $price.' '.$csymbol;
				break;

			default:
				$fm_price = $csymbol.$price;
				break;
		}
		return $fm_price;
	}

	/**
	 * Get Side Cart HTML
	 *
	 * @since     1.0.0
	 * @return    string 
	 */

	public function get_cart_markup(){
		if(is_cart() || is_checkout()){return;}
		require_once  plugin_dir_path( dirname( __FILE__ ) ).'/public/partials/xoo-wsc-markup.php';	
	}


	/**
	 * Sends JSON data on cart update
	 *
	 * @since     1.0.0
	 */

	public function send_json_data(){

		ob_start();
		$this->get_cart_content();
		$cart_markup = ob_get_clean();
		$ajax_fragm  = $this->get_ajax_fragments();

		//Get User Settings
		$options = get_option('xoo-wsc-gl-options');
		$show_count = isset( $options['bk-show-bkcount']) ? $options['bk-show-bkcount'] : 1;

		if($show_count == 1){
			$count_value = WC()->cart->get_cart_contents_count();
		}
		else{
			$count_value = 0;
		}

		//Send JSON data back to browser
		wp_send_json(
			array(
				'ajax_fragm' 	=> $ajax_fragm,
				'items_count' 	=> $count_value,
				'cart_markup' 	=> $cart_markup
				)
			);
	}

	/**
	 * Get required cart data
	 *
	 * @since     1.0.0
	 * @return    array 
	 */

	public function markup_data(){
		$cart_data = WC()->cart->get_cart();
		if(!$cart_data)
			return false;

		foreach($cart_data as $cart_item_key => $item){
			$item_product_id   = $item['product_id'];
			$item_variation_id = $item['variation_id'];

			if($item_variation_id){
				$product_id = $item_variation_id;
				$product 	= new WC_product_variation($product_id);
				$attributes = wc_get_formatted_variation($product);
			}
			else{
				$product_id = $item_product_id;
				$product 	= wc_get_product($product_id);
				$attributes = 0;
			}

			$item_title  	= $product->get_title();
			$item_link		= $product->get_permalink();
			$item_price_raw = $product->get_price();
			$item_price 	= $this->formatted_price($item_price_raw);
			$item_image  	= $product->get_image('shop_thumbnail');
			$is_sold_single = $product->is_sold_individually(); 
			$item_qty  	 	= $item['quantity'];
			$item_total  	= $this->formatted_price($item_qty*$item_price_raw);

			$markup_data[] = array(
				'id'			=> $product_id,
				'title'			=> $item_title,
				'link'			=> $item_link,
				'image'			=> $item_image,
				'price'			=> $item_price,
				'quantity'		=> $item_qty,
				'total'			=> $item_total,
				'attributes'	=> $attributes,
				'is_sold_single'=> $is_sold_single,
				'cart_key'		=> $cart_item_key
				);
		}

		return $markup_data;

	}


	/**
	 * Get Side Cart Content
	 *
	 * @since     1.0.0
	 */

	public function get_cart_content(){
		$items = $this->markup_data();
		$options 	= get_option('xoo-wsc-gl-options');
		?>

		<?php if($items): ?>

			<?php 
			$shipping_txt 	= isset($options['sc-shipping-text']) ? $options['sc-shipping-text']: __("To find out your shipping cost , Please proceed to checkout.",XOO_WSC_DOMAIN);
			$show_ptotal 	= isset( $options['sc-show-ptotal']) ? $options['sc-show-ptotal'] : 1;
			?>

			<?php foreach($items as $item) :  ?>

			<div class="xoo-wsc-product" data-xoo_wsc="<?php echo $item['cart_key']; ?>">
				<div class="xoo-wsc-img-col">
					<?php echo $item['image']; ?>
					<a href="#" class="xoo-wsc-remove"><?php _e('Remove',XOO_WSC_DOMAIN); ?></a>
				</div>
				<div class="xoo-wsc-sum-col">
					<a href="<?php echo $item['link']; ?>" class="xoo-wsc-pname"><?php echo $item['title']; ?></a>
					<?php 

					if($attributes = $item['attributes']){
						echo $attributes;
					} 

					?>
					<div class="xoo-wsc-price">
						<span><?php echo $item['quantity']; ?></span> X <span><?php echo $item['price']; ?></span> = 
						<?php if($show_ptotal == 1): ?>
							<span><?php echo $item['total']; ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php endforeach ?>

			<div class="xoo-wsc-subtotal">
				<span><?php _e("Subtotal:",XOO_WSC_DOMAIN); ?></span> <?php echo $this->formatted_price(WC()->cart->subtotal); ?>
			</div>

			<?php if(!empty($shipping_txt)): ?>
				<span class="xoo-wsc-shiptxt"><?php echo esc_attr($shipping_txt); ?></span>
			<?php endif; ?>

		<?php else : ?>

			<span class="xoo-wsc-ecnt"><?php _e('Your cart is empty.',XOO_WSC_DOMAIN); ?></span>

		<?php endif ?>
		<?php
	}


	/**
	 * Add product to cart
	 *
	 * @since     1.0.0
	 */


	public function add_to_cart(){
		
		//Form Input Values
		$item_id 		= intval($_POST['item_id']);
		$quantity 		= intval($_POST['quantity']);

		//If empty return error
		if(!$item_id){
			wp_send_json(array('error' => __('Something went wrong','xoo-wsc')));
		}

		//Check product type
		$product_type = get_post_type($item_id);

		if($product_type == 'product_variation'){
			$product_id = wp_get_post_parent_id($item_id);
			$variation_id = $item_id;
			$attribute_values = wc_get_product_variation_attributes($variation_id);
			$cart_success = WC()->cart->add_to_cart($product_id,$quantity,$variation_id,$attribute_values );
		}
		else{
			$product_id = $item_id;
			$cart_success = WC()->cart->add_to_cart($product_id,$quantity);
		}

		//Successfully added to cart.
		if($cart_success){
			$this->send_json_data();
		}
		else{
			if(wc_notice_count('error') > 0){
	    		echo wc_print_notices();
			}
	  	}
		die();
	}



	/**
	 * Update product quantity in cart.
	 *
	 * @since     1.0.0
	 */

	public function update_cart(){

		//Form Input Values
		$cart_key = sanitize_text_field($_POST['cart_key']);
		$new_qty  = 0;

		//If empty return error
		if(!$cart_key){
			wp_send_json(array('error' => __('Something went wrong',XOO_WSC_DOMAIN)));
		}
		
		$cart_success = WC()->cart->set_quantity($cart_key,$new_qty);
		
		if($cart_success){
			$this->send_json_data();
		}
		else{
			if(wc_notice_count('error') > 0){
	    		echo wc_print_notices();
			}
		}
		die();
	}

	/**
	 * Get Cart fragments on update
	 *
	 * @since     1.0.0
	 * @return    array
	 */

	public function get_ajax_fragments(){

	  	// Get mini cart
	    ob_start();

	    woocommerce_mini_cart();

	    $mini_cart = ob_get_clean();

	    // Fragments and mini cart are returned
	    $data = array(
	        'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
	                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
	            )
	        ),
	        'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
	    );
	    return $data;
	}
}
?>