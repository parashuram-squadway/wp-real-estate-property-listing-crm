<?php
if ( !class_exists( 'md_sc_crm_featured_properties' ) )
{
	class md_sc_crm_featured_properties {
		protected static $instance = null;

		/**
		 * Return an instance of this class.
		 *
		 * @since     1.0.0
		 *
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance() {

			/*
			 * @TODO :
			 *
			 * - Uncomment following lines if the admin class should only be available for super admins
			 */
			/* if( ! is_super_admin() ) {
				return;
			} */

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct(){
			add_action('admin_footer', array($this, 'md_get_shortcodes'));
			add_shortcode('crm_featured_properties',array($this,'init_shortcode'));
		}

		public function get_template(){
			return \MD_Template::get_instance()->get_theme_page_template(GLOBAL_TEMPLATE . 'list', GLOBAL_TEMPLATE, 'List');
		}

		public function init_shortcode($atts){
			if( isset($atts['template']) ){
				$att_template = $atts['template'];
			}
			if( isset($atts['items']) ){
				$items = $atts['items'];
			}
			if( isset($atts['col']) && is_numeric($atts['col']) ){
				$col = ceil(12 / $atts['col'] );
			}else{
				$col = MD_DEFAULT_GRID_COL;
			}

			$atts = shortcode_atts(
				array(
					'template' 	=> $att_template,
					'col' 		=> $col,
					'items'		=> $items,
					'infinite'	=> false,
				),
				$atts, 'crm_featured_properties'
			);

			$properties = \crm\Properties::get_instance()->getFeaturedProperties();

			\MD\Property::get_instance()->set_properties($properties,'crm');

			$items = 0;
			if( $atts['items'] == 0 ){
				if( $properties ){
					$items = $properties->total;
				}
			}

			if( trim($atts['template']) != '' ){
				// check if its from template
				$template = \MD_Template::get_instance()->load_template($atts['template']);
				if( !$template ){
					$template = CRM_DEFAULT_LIST;
				}
			}

			// hook filter, incase we want to just use hook
			if( has_filter('shortcode_featured_property_crm') ){
				$template = apply_filters('shortcode_featured_property_crm', $path);
			}

			ob_start();

			require $template;
			$output = ob_get_clean();
			return $output;
		}

		/**
		 * Add shortcode JS to the page
		 *
		 * @return HTML
		 */
		public function md_get_shortcodes()
		{
			?>
				<script type="text/javascript">
					function crm_featured_properties(editor){
						var template = [
							<?php if( count($this->get_template()) > 0 ){ ?>
									<?php foreach($this->get_template() as $key=>$val){ ?>
											{text: '<?php echo $val; ?>',value: '<?php echo $key;?>'},
									<?php } ?>
							<?php } ?>
						];
						var submenu_array =
						{
							text: 'Featured Properties',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Display CRM properties by search criteria',
									width:980,
									height:350,
									body: [
										{
											type: 'listbox',
											name: 'listboxTemplate',
											label: 'Choose template to display',
											'values': template
										},
										{
											type: 'textbox',
											name: 'textboxGridCol',
											label: 'Set property per columns ( should be divided by 12 )',
											value:'1'
										},
										{
											type: 'textbox',
											name: 'textboxDisplay',
											label: 'How many to display (zero means all)',
											value:'0'
										},
									],
									onsubmit: function( e ) {
										var template_path = ' template="' + e.data.listboxTemplate + '" ';
										var col_grid = ' col="' + e.data.textboxGridCol + '" ';
										var display = ' items="' + e.data.textboxDisplay + '" ';
										editor.insertContent(
											'[crm_featured_properties ' + template_path + col_grid + display + ']'
										);
									}
								});
							}
						};

						return submenu_array;
					}
				</script>
			<?php
		}
	}
}