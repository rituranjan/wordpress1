( function( blocks, editor, element ) {

	var blockInitComplete = false
	var el  = React.createElement
	var InnerBlocks = wp.blockEditor.InnerBlocks
    var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var woo_products = []
	var checkIcon = el( 'span', { className: 'dashicons dashicons-yes' } )
	var learnMoreButton = el( wp.components.Button, {
		variant: 'primary',
		onClick: function(){
			window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
		}
	}, 'Get Premium' )
	
		
	if( fcaEpt4EditorData.woo_active ) {
		jQuery.ajax( {
			url: fcaEpt4EditorData.ajax_url,
			type: 'POST',
			data: {
				"action": "fca_ept4_get_woo_products_ajax",
				"nonce": fcaEpt4EditorData.nonce
			}
			}).done( function( response ){

				if( response && response.success ){
					woo_products = response.data
				}
		})
	}

	blocks.registerBlockCollection( 'easy-pricing-tables', {
		title: 'Easy Pricing Tables - by Fatcat Apps',
		icon: fcaEpt4Icons.fatcatapps,
	})
	
	blocks.registerBlockType( 'easy-pricing-tables/table', {
		icon: fcaEpt4Icons.table2,
		edit: function( props ) {
			if( blockInitComplete == false ) {
				blockInit()
				blockInitComplete = true
			}
			
			React.useEffect( function() {
				
				if( props.attributes.tableID == '' ) {
					props.setAttributes({
						tableID: Math.random().toString(16).slice(2)
					})
				}
			}, [] )
			
			var useState = wp.element.useState
			var modalUseState = useState( false )
			var fcaEpt4showWooModal = modalUseState[0]
			var setfcaEpt4showWooModal = modalUseState[1]
			
			var productUseState = useState( 0 )
			var fcaEpt4wooProduct = productUseState[0]
			var setfcaEpt4wooProduct = productUseState[1]
			
			var templateID = props.attributes.templateID
			var className = 'ept4-table-' + props.attributes.tableID + ' layout-' + templateID
						
			if( props.attributes.matchRowHeight ) {
				className += ' matchRowHeight'
			}
			var blockProps = useBlockProps({				
				className: className
			})
			
			
			var innerBlocksProps = useInnerBlocksProps({
				style: {
					gap: props.attributes.gridGap + 'px' 
				}
			}, {				
				orientation: 'horizontal',
				renderAppender: false,
				allowedBlocks:  [],
				template: defaultTemplate( props )
			})
			
			//FOR TOGGLES PLACEHOLDER TABLE
			if( templateID === -1 ) {
				return
			}
			
			return templateID == false ? pickTemplate( props ) : el( 'div', blockProps, 
				controls( props ),
				fcaEpt4showWooModal ? wooModal( props ) : null,
				
				el( 'div', innerBlocksProps ),
				el( 'style', {}, props.attributes.customCSS ),
				tableFontCss( props )
			)
			
			function controls ( props ) {
				var templateID = props.attributes.templateID
				var fontOptions = [
					{
						label: 'Sans-serif',
						value: 'sans-serif'
					},
					{
						label: 'Inherit',
						value: 'inherit'
					},
					{
						label: 'Roboto',
						value: 'Roboto'
					},
					{
						label: 'Open Sans',
						value: 'Open Sans'
					},
					{
						label: 'Lato',
						value: 'Lato'
					},
					{
						label: 'Oswald',
						value: 'Oswald'
					},
					{
						label: 'Source Sans Pro',
						value: 'Source Sans Pro'
					},
					{
						label: 'Montserrat',
						value: 'Montserrat'
					},
					{
						label: 'Merriweather',
						value: 'Merriweather'
					},
					{
						label: 'Raleway',
						value: 'Raleway'
					},
					{
						label: 'PT Sans',
						value: 'PT Sans'
					},
					{
						label: 'Lora',
						value: 'Lora'
					},
					{
						label: 'Noto Sans',
						value: 'Noto Sans'
					},
					{
						label: 'Nunito Sans',
						value: 'Nunito Sans'
					},
					{
						label: 'Concert One',
						value: 'Concert One'
					},
					{
						label: 'Prompt',
						value: 'Prompt'
					},
					{
						label: 'Work Sans',
						value: 'Work Sans'
					}
				]
				
				return el( wp.blockEditor.InspectorControls, { 
						key: 'ept4-table-controls',
					},			
					
					el( wp.components.Button, {
						style:{
							margin: '16px'
						},
						icon: 'table-col-after',
						variant: "primary",
						onClick: function(){ 
							
							var column = wp.blocks.createBlock( 'easy-pricing-tables/column', defaultBlockProps( props ) )
							var selectedBlock = wp.data.select( 'core/block-editor' ).getSelectedBlock()
							
							wp.data.dispatch('core/block-editor').insertBlocks( column, selectedBlock.innerBlocks.length, selectedBlock.clientId )

						}
					},
						'Add Column'
					),
					fcaEpt4EditorData.woo_active ? el( wp.components.Button, {
						style:{
							margin: "0 16px 16px 16px",
							backgroundColor: "#7f54b3"
						},						
						icon: 'table-col-after',
						variant: "primary",
						onClick: function(){ 
							setfcaEpt4showWooModal( true )
						}
					},
						'Add WooCommerce Column'
					) : null,
					
					el( wp.components.PanelBody, { 
						
						style: {
							padding: "16px"
						},
						title: 'Table Settings',
						className: 'fca-ept-table-settings',
						},
						fcaEpt4EditorData.edition === 'Free' ? el( 'div', { 
							className: 'fca-ept-get-premium',						
							style: {
								marginBottom: '16px'
							}
						},
							el( 'h2', {}, "Upgrade to Premium and Build Better Pricing Tables. You'll Get:" ),
							el( 'p', {}, checkIcon, 'Nine Gorgeous & Fully-Customizable Designs' ),
							el( 'p', {}, checkIcon, '700+ Icons to Add to Your Tables' ),
							el( 'p', {}, checkIcon, 'Tooltips Support for More Details' ),
							el( 'p', {}, checkIcon, 'Font Picker with 12+ fonts' ),
							el( 'p', {}, checkIcon, 'Priority Email Support' ),
							el( 'p', {}, checkIcon, 'Import WooCommerce Products' ),
							el( 'p', {}, checkIcon, 'Pricing Toggles - switch between currencies or monthly/yearly pricing' ),
							learnMoreButton
							
						) : el( wp.components.SelectControl, {
							label: 'Font',
							value: props.attributes.fontFamily,
							options: fontOptions,
							onChange: function( selected ){ 
								if ( selected ){
									props.setAttributes( { fontFamily: selected } )
								}
							}
						}),
						el( wp.components.ToggleControl, {
							className: 'ept4-table-toggle',
							label: 'Match row height',
							checked: props.attributes.matchRowHeight,
							onChange: function() {
								var nuValue = !props.attributes.matchRowHeight
								
								if( nuValue == false ) {
									//REMOVE MINHEIGHT
									var className = '.ept4-table-' + props.attributes.tableID
									var tables = document.querySelectorAll( className )
								
									var elementsToMatch = [ '.planText', '.planSubText', '.priceText', '.pricePeriod', '.periodText', '.billingText', '.comparisonSpacer' ]
									
									elementsToMatch.forEach(function(selector){
										
										var divs = tables[0].querySelectorAll( selector )		
										
										for( var i = 0; i < divs.length; i++ ) {							
											divs[i].style.minHeight = 0
										}
										
									})	
									
									var listItems = tables[0].querySelectorAll( '.featuresText li' )	
									listItems.forEach(function(item){
										item.style.minHeight = 0
									})
								}
								
								props.setAttributes( { matchRowHeight: nuValue } )
							}
						}),
						el( wp.components.RangeControl, {
							
							label: "Grid Gap",
							max: 100,
							min: 0,
							value: props.attributes.gridGap,
							onChange: function( value ){ 
								props.setAttributes({
									gridGap: value
								})

							}
						})
					),
				
					el( wp.components.PanelBody, { 
						title: 'Custom CSS',
						className: 'fca-ept-css-settings',
						initialOpen: false
						},
						el( wp.components.TextareaControl, {
							value: props.attributes.customCSS,
							label: 'Custom CSS',			
							help: 'Add CSS to fine-tune the look of your table. For example: .wp-block-easy-pricing-tables-column { font-weight:bold }',
							onChange: (
								function( newValue ){					
									props.setAttributes( { customCSS: newValue } )
								} 
							)
						})
					)
				)
			}
						
			function blockInit() {
				if(	wp.data.select( 'core/edit-post' ).isFeatureActive( 'welcomeGuide' ) ) {
					wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'welcomeGuide' )
				}
				
				var currentPost = wp.data.select( 'core/editor' ).getCurrentPost()
				
				if( currentPost.type === 'wp_block' ) {
					
					var isEpt4Block = currentPost.content.split( '<!--' )[1].includes( 'wp:easy-pricing-tables/table' ) 
					
					if( !isEpt4Block ) {
						return
					}
					
					//MAKE "BACK" WP BUTTON GO TO OUR POST LIST INSTEAD OF RESUABLE BLOCK LIST (NOT WORKING ATM...BUTTON IS GENERATED LATER
					document.body.addEventListener( 'click', function(e) {
						var hrefLink = e.target.href || 0
						if( hrefLink == fcaEptEditorData.edit_url + '?post_type=wp_block' ) {
							e.target.href = fcaEptEditorData.edit_url + '?post_type=easy-pricing-table&page=ept3-list'
						}	
					}, true ) 
					
					
					var eptBlock = wp.data.select( 'core/block-editor' ).getBlocks().filter( function( block ){
						return block.name === 'easy-pricing-tables/table'
					})
										
					wp.data.subscribe( function(){			
						// prevent block from being removed
						var newBlockList = wp.data.select( 'core/block-editor' ).getBlocks().filter( function( block ){
							return block.name === 'easy-pricing-tables/table'
						})
						
						if ( newBlockList.length < eptBlock.length ){
							wp.data.dispatch( 'core/block-editor' ).resetBlocks( eptBlock )
						}
						
						//SAVE HOOK
						var isSavingPost = wp.data.select( 'core/editor' ).isSavingPost()
						var isAutosavingPost = wp.data.select( 'core/editor' ).isAutosavingPost()

						if ( isSavingPost && !isAutosavingPost ){

							var activeNotices = wp.data.select( 'core/notices' ).getNotices()
							var result = activeNotices.filter( function( notice, i ){
								return notice.id === 'fcaEptSuccessNotice'
							})

							if( !result.length ){

								wp.data.dispatch( 'core/notices' ).createNotice(
									'success',
									'Pricing Table saved successfully! Your shortcode: [easy-pricing-tables id=' + wp.data.select( 'core/editor' ).getCurrentPost().id + ']',
									{
										id: 'fcaEptSuccessNotice',
										isDismissible: true,
										actions: [
											{
												onClick: ( function(){ window.open( 'https://fatcatapps.com/knowledge-base/how-to-create-your-first-pricing-table/', '_blank' ) } ),
												label: 'Need help publishing your new table?',
											},
										],
									}
								)
							}
						}
					})
				}
			}
			
			function wooModal( props ) {
				
				return el( wp.components.Modal, {
					title: 'Choose a product',
					isDismissible: true,
					//shouldCloseOnClickOutside: ( props.attributes.templateID !== 0 ),
					className: 'fca-ept-modal',
					onRequestClose: function(){				 
						setfcaEpt4showWooModal( false )
					}
					},
					el( 'div', { },
						el( wp.components.ComboboxControl, {
							options: woo_products,
							help: "Select a product and we'll add a new column with the product name, price and URL.",
							onChange: function( id ) {
								setfcaEpt4wooProduct( id )
							}
							
						}
						
						),
						el( wp.components.Button, {
							
							variant: "primary",
							onClick: function(){ 
								var templateID = props.attributes.templateID
								if( templateID == 0 ) {
									return []
								}
								
								if( fcaEpt4wooProduct == 0 ) {
									return []
								}
								
								var newBlockProps = defaultBlockProps( props )
								var selectedWooProduct = woo_products.find(function(p) {
									return p.value == fcaEpt4wooProduct
								})
								 
								var wooInnerBlocksFunction = new Function( 'product', 'return ept4WooColumnInnerBlocks' + templateID + '(product)' ) 
								
								newBlockProps.innerBlocksTemplate = wooInnerBlocksFunction( selectedWooProduct )
								
								var column = wp.blocks.createBlock( 'easy-pricing-tables/column', newBlockProps )
								var selectedBlock = wp.data.select( 'core/block-editor' ).getSelectedBlock()
								
								wp.data.dispatch('core/block-editor').insertBlocks( column, selectedBlock.innerBlocks.length, selectedBlock.clientId )
								setfcaEpt4showWooModal( false )
							}
						},
							'Add Product Column'
						)
					)

				)
			}
					
			function pickTemplate( props ) {
				var selectedBlock = wp.data.select( 'core/block-editor' ).getSelectedBlock()
				
				//MAKE SURE OUR BLOCK IS ACTUALLY SELECTED... PREVENT ANNOYING BEHVIOR ON UNSAVED BLOCKS IN EDITOR
				if( selectedBlock && [ 'easy-pricing-tables/table', 'easy-pricing-tables/toggle-table' ].indexOf( selectedBlock.name ) === -1 ) {
					return null
				}
				
				return el( wp.components.Modal, {
					title: 'Choose a template',
					isDismissible: true,
					onRequestClose: function(){						
						if( props.isSelected ) {
							wp.data.dispatch('core/block-editor').removeBlock( selectedBlock.clientId )	
						} else {
							var parents = wp.data.select('core/block-editor').getBlockParentsByBlockName( props.clientId, [ 'easy-pricing-tables/toggle-table' ] )
							wp.data.dispatch('core/block-editor').removeBlock( parents[0] )
						}						
					},
					shouldCloseOnClickOutside: false,
					className: 'fca-ept-modal',
					},
					el( 'div', { 
							className: 'fca-ept-template-selection',
						},
						
						el( 'div', {
								className: 'template-container' 
							},							
					
							el( 'div', {
								className: 'template',
								onClick: function(){ 
									loadTemplate( 2, props )
								}
								},
									fcaEpt4Icons.template2(),
									learnMoreButton
							),
							el( 'div', {
								className: 'template',
								onClick: function(){ 							
									loadTemplate( 1, props )
								}
								},					
									fcaEpt4Icons.template1(),
									learnMoreButton								
							),
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 5, props )
									}
								}
								},					
									fcaEpt4Icons.template3(),
									learnMoreButton									
							),	
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 6, props )
									}
								}
								},					
									fcaEpt4Icons.template4(),
									learnMoreButton									
							),	
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 8, props )
									}
								}
								},					
									fcaEpt4Icons.template5(),
									learnMoreButton									
							),
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 4, props )
									}
								}
								},					
									fcaEpt4Icons.template6(),
									learnMoreButton
							),
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 7, props )
									}
								}
								},					
									fcaEpt4Icons.template7(),
									learnMoreButton
							),	
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 3, props )
									}
								}
								},
									fcaEpt4Icons.template8(),
									learnMoreButton
							),
							el( 'div', {
								className: fcaEpt4EditorData.edition === 'Free' ? 'template pro-only' : 'template',
								onClick: function(){
									if ( fcaEpt4EditorData.edition === 'Free' ) {
										window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
									} else {
										loadTemplate( 9, props )
									}
								}
								},					
									fcaEpt4Icons.template9(),
									learnMoreButton
							)					
						) // end inner div
					) //end div
				) // end modal
			}
						
			function loadTemplate( templateNumber, props ) {
				
				var defaultAttributes = eval( 'fcaEpt4defaultAttributes' + templateNumber )
				props.setAttributes( defaultAttributes )
				
				var parents = wp.data.select('core/block-editor').getBlockParentsByBlockName( props.clientId, [ 'easy-pricing-tables/toggle-table' ] )
				
				if( parents.length ) {
					var parentBlock = wp.data.select('core/block-editor').getBlock( parents[0] )
					
					wp.data.dispatch('core/block-editor').updateBlockAttributes( parentBlock.innerBlocks[2].clientId, defaultAttributes )
					
					window.setTimeout(function(){
						
						var toggleBlock = document.querySelectorAll( '.wp-block-easy-pricing-tables-toggle' )
						
						if( toggleBlock.length > 0 ) {
							toggleBlock[( toggleBlock.length - 1 )].click()
						}
						
					}, 400 )
					
				}			
			}
			
		},
		
		

		save: function( props ) {
			
			var className = 'ept4-table-' + props.attributes.tableID + ' layout-' + props.attributes.templateID
			
			if( props.attributes.matchRowHeight ) {
				className += ' matchRowHeight'
			}
			
			var blockProps = useBlockProps.save({
				style: {
					gap: props.attributes.gridGap + 'px',
				},				
				className: className
				
			})
			
			var innerBlocksProps = useInnerBlocksProps.save()
            return el( 'div', 
				blockProps, 
				innerBlocksProps.children,
				el( 'style', {}, props.attributes.customCSS ),
				tableFontCss( props )
			)
		},
	} )
	
	function tableFontCss( props ) {
		var fontFamily = props.attributes.fontFamily
		var css = ''
		if( fontFamily !== 'inherit' && fontFamily !== 'sans-serif' ) {
			css += "@import url('https://fonts.googleapis.com/css?family=" + fontFamily + "&display=swap');"
		}
		if( fontFamily === 'sans-serif' ) {
			css += "div.ept4-table-" + props.attributes.tableID + " p, div.ept4-table-" + props.attributes.tableID + " a, div.ept4-table-" + props.attributes.tableID + " li { font-family: sans-serif !important};"
		} else {
			css += "div.ept4-table-" + props.attributes.tableID + " p, div.ept4-table-" + props.attributes.tableID + " a, div.ept4-table-" + props.attributes.tableID + " li { font-family: '" + fontFamily + "' !important};"			
		}
				
		return el( 'style', {}, css )
		
	}
	
	function defaultTemplate( props ) {
		var templateID = props.attributes.templateID
		
		if( templateID == false ) {
			return []
		}
		
		//FOR TOGGLES PLACEHOLDER TABLE
		if( templateID === -1 ) {
			return []
		}
		
		return eval( 'fcaEpt4template' + templateID )
		
	}
		
	function defaultBlockProps( props ) {
		var templateID = props.attributes.templateID
		
		if( templateID == false ) {
			return {}
		}
		
		return eval( 'fcaEpt4defaultBlockProps' + templateID )
		
	}
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))

	