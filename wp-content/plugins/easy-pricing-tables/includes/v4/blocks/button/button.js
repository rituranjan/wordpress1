( function( blocks, editor, element ) {

	var el  = React.createElement
	var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var selectedBlock = {}

	
	blocks.registerBlockType( 'easy-pricing-tables/button', {	
		icon: 'button',
		edit: function( props ) {
		
			var innerBlocksProps = useInnerBlocksProps( {}, {
				template: [ [ 'core/shortcode', { text: props.attributes.shortcode } ] ]					
			})
			
			React.useEffect( function() {				
				if( props.attributes.buttonID == '' ) {
					props.setAttributes({
						buttonID: Math.random().toString(16).slice(2)
					})
				}
			}, [] )
			
			var obj = {
				href: props.attributes.url,
				tagName: 'a', 
				rel: 'noopener',
				className: 'easy-pricing-tables-button-' + props.attributes.buttonID,
				placeholder: 'Add to Cart',
				value: props.attributes.content,
				onChange: function( newValue ){
					props.setAttributes({
						content: newValue
					})
				}
			}
			
			var blockProps = useBlockProps( blockPropsObj( props, obj ) )
			var backgroundHoverColor = props.attributes.backgroundHoverColor
			var textHoverColor = props.attributes.textHoverColor
			var hoverStyle = "background-color:" + backgroundHoverColor + ' !important;'
			
			if( backgroundHoverColor === "" ) {
				hoverStyle = "filter: grayscale(0.65);"
			}
			
			if( textHoverColor ) {
				hoverStyle += "color:" + textHoverColor + ' !important;'
			}
						
			if( props.attributes.shortcode ) {
			
				return el( 'div', {},
					//controls( props ),
					advanced_controls( props ),
					//el( wp.blockEditor.RichText, blockProps ),	
					el( 'div', innerBlocksProps )
				)
			} 
			
			return	el( 'div', {
				style: {
					textAlign: 'center'
				}
			},
				controls( props ),	
				advanced_controls( props ),				
				el( wp.blockEditor.RichText, blockProps	),
				el( 'style', {}, ".wp-block-easy-pricing-tables-column .wp-block-easy-pricing-tables-button.easy-pricing-tables-button-"+props.attributes.buttonID+":hover { " + hoverStyle +" }" )
			)
			
		},
		save: function( props ) {
			
			var obj = {				
				href: props.attributes.url,
				tagName: 'a', 
				rel: 'noopener',				
				value: props.attributes.content,
				className: 'easy-pricing-tables-button-' + props.attributes.buttonID,
				target: props.attributes.urlTargetBlank ? "_blank" : "_self"
			}
			var blockProps = useBlockProps.save( blockPropsObj( props, obj ) )
				
			var backgroundHoverColor = props.attributes.backgroundHoverColor
			var textHoverColor = props.attributes.textHoverColor
			var hoverStyle = "background-color:" + backgroundHoverColor + ' !important;'
			
			if( backgroundHoverColor === "" ) {
				hoverStyle = "filter: grayscale(0.65);"
			}
			
			if( textHoverColor ) {
				hoverStyle += "color:" + textHoverColor + ' !important;'
			}
			
			var innerBlocksProps = useInnerBlocksProps.save()
			
			if( props.attributes.shortcode ) {
				return el( 'div', {},
					innerBlocksProps.children		
				)
			}
			
			return	el( 'div', {
				style: {
					textAlign: 'center'
				}
			},
				el( wp.blockEditor.RichText.Content, blockProps	),
				el( 'style', {}, ".wp-block-easy-pricing-tables-column .wp-block-easy-pricing-tables-button.easy-pricing-tables-button-"+props.attributes.buttonID+":hover { " + hoverStyle +" }" )
			)
		}
		
		
	} )
	
	
	function controls( props ) {
		var colorSettings = [
				{
					"label": "Background",
					"value": props.attributes.backgroundColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { backgroundColor: newValue } )
					}
				},
				{
					"label": "Text",
					"value": props.attributes.textColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { textColor: newValue } )
					}
				},	
				{
					"label": "Hover Color",
					"value": props.attributes.backgroundHoverColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { backgroundHoverColor: newValue } )
					}
				},
				{
					"label": "Text Hover Color",
					"value": props.attributes.textHoverColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { textHoverColor: newValue } )
					}
				}				
		]
		
		if ( props.attributes.borderColor ) {
			colorSettings = [
				{
					"label": "Background",
					"value": props.attributes.backgroundColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { backgroundColor: newValue } )
					}
				},
				{
					"label": "Text",
					"value": props.attributes.textColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { textColor: newValue } )
					}
				},	
				{
					"label": "Border Color",
					"value": props.attributes.borderColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { borderColor: newValue } )
					}
				},
				{
					"label": "Hover Color",
					"value": props.attributes.backgroundHoverColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { backgroundHoverColor: newValue } )
					}
				},
				{
					"label": "Text Hover Color",
					"value": props.attributes.textHoverColor,
					"onChange": function( newValue ){ 							
						props.setAttributes( { textHoverColor: newValue } )
					}
				}				
			]
		}
		return el( wp.blockEditor.InspectorControls, { 
				key: 'ept4-button-controls'				
			},
			
			el( wp.blockEditor.PanelColorSettings, {
				title: 'Button Colors',
				colorSettings: colorSettings
			}),
			el( 'div', { 
				className: 'ept4-button-sidebar',
				style: {
					margin: '16px'
				}
			},
				
				el( wp.components.RangeControl, {
					
					label: "Button Width",
					max: 100,
					min: 0,
					value: props.attributes.width,
					onChange: function( value ){ 
						props.setAttributes({
							width: value
						})

					}
				}),
				el( wp.components.TextControl, {
					
					label: 'Link URL',
					value: props.attributes.url,
					onChange: function( newVal ) {
						props.setAttributes( { url: newVal } )
					}
				}),
				el( wp.components.ToggleControl, { 
					label: 'Open in new tab',
					checked: props.attributes.urlTargetBlank,
					
					onChange: function(){
						props.setAttributes( { urlTargetBlank: !props.attributes.urlTargetBlank } )
					}
				})
				
			)
		)
	}
	
	function advanced_controls( props ) {
		return el( wp.blockEditor.InspectorAdvancedControls, {},
			el( wp.components.TextControl, { 
				label: 'Shortcode (overrides default button)',
				value: props.attributes.shortcode,
				onChange: function( newVal ){
					props.setAttributes( { shortcode: newVal } )
				}
			})
		)		
	}
	
	function blockPropsObj( props, obj ) {		
		
		var blockStyleObj = {
			maxWidth: props.attributes.width + '%'				
		}
		
		if( props.attributes.textColor ) {
			blockStyleObj.color = props.attributes.textColor
		}
		if( props.attributes.borderColor ) {
			blockStyleObj.border = "1px solid " + props.attributes.borderColor
		}
		
		if( props.attributes.backgroundColor ) {
			blockStyleObj.backgroundColor = props.attributes.backgroundColor
		}
		
		if( props.attributes.borderColor ) {
			blockStyleObj.borderColor = props.attributes.borderColor
		}
					
		obj.style = blockStyleObj				
		
		return obj
	}
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))