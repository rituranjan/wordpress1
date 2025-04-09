( function( blocks, editor, element ) {

	var el  = React.createElement
	var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var selectedBlock = {}
	
	blocks.registerBlockType( 'easy-pricing-tables/column', {	
		icon: fcaEpt4Icons.column2,
		edit: function( props ) {
			
			selectedBlock = wp.data.useSelect( function( select ) {
				return select( 'core/block-editor' ).getSelectedBlock()
			})
			
			var obj = {
				className:  props.attributes.columnPopular ? 'is-style-featured' : ''
			}
			if( props.attributes.showImage ) {
				obj.className += ' is-style-showImage' 
			}
			
			if( props.attributes.showButton == false ) {
				obj.className += ' is-style-hideButton' 
			}
			
			var blockProps = useBlockProps( blockPropsObj( props, obj ) )
			var innerBlockPropsAtts = {
				template: innerBlocksTemplate( props )
			}
			if( fcaEpt4ColumnData.allowAddingBlocks == 'false' ) {
				innerBlockPropsAtts.renderAppender = false
			}
			
			var innerBlocksProps = useInnerBlocksProps( {}, innerBlockPropsAtts )
			
			return	el( 'div', blockProps,
				controls( props ),	
				el( 'div', innerBlocksProps )			
			)
		},
		save: function( props ) {
						
			var obj = {
				className:  props.attributes.columnPopular ? 'is-style-featured' : ''
			}
			
			if( props.attributes.showImage ) {
				obj.className += ' is-style-showImage' 
			}
			
			if( props.attributes.showButton == false ) {
				obj.className += ' is-style-hideButton' 
			}
			
			var blockProps = useBlockProps.save( blockPropsObj( props, obj ) )
			
			var innerBlocksProps = useInnerBlocksProps.save()
			
			return el( 'div', blockProps,				
				el( 'div',  {}, innerBlocksProps.children )
			)			
		}
	} )	
	
	function blockPropsObj( props, obj ) {
					
		var blockStyleObj = {}
		
		if( props.attributes.backgroundColor ) {
			blockStyleObj.backgroundColor = props.attributes.backgroundColor
		}
		
		if( props.attributes.borderColor ) {
			blockStyleObj.borderColor = props.attributes.borderColor
		}
		
		if( props.attributes.borderColor || props.attributes.backgroundColor ) {			
			obj.style = blockStyleObj				
		}
		
		return obj
	}
	
	function controls ( props ) {
		var templateID = getTemplateID( props )
		var templateHasFeaturedStyle = ( [ 1, 2, 5, 6 ].indexOf( templateID ) !== -1 )
		
		var colorPanels = [{
			"label": "Background Color",
			"value": props.attributes.backgroundColor,
			"onChange": function( newValue ){ 							
				props.setAttributes( { backgroundColor: newValue } )
			}
		}]
		
		if( props.attributes.borderColor ) {
			colorPanels = [{
				"label": "Background Color",
				"value": props.attributes.backgroundColor,
				"onChange": function( newValue ){ 							
					props.setAttributes( { backgroundColor: newValue } )
				}
			},
			{
				"label": "Border Color",
				"value": props.attributes.borderColor,
				"onChange": function( newValue ){ 							
					props.setAttributes( { borderColor: newValue } )
				}
			}]			
		}
		
		return el( wp.blockEditor.InspectorControls, { 
				key: 'ept4-column-controls'
			},
			el( 'div', { 
				className: 'ept4-column-sidebar',
			},
				el( wp.components.Button, {
					style:{
						margin: '16px'
					},
					icon: 'table-col-after',
					variant: "primary",
					onClick: function(){ 
						
						var selectedBlockIndex = wp.data.select('core/block-editor').getBlockIndex( selectedBlock.clientId )
						var column = wp.blocks.createBlock( 'easy-pricing-tables/column', defaultBlockProps( props ) )
						var	selectedBlockRootID =  wp.data.select('core/block-editor').getBlockRootClientId( selectedBlock.clientId )	
						
						wp.data.dispatch('core/block-editor').insertBlocks( column, (selectedBlockIndex + 1), selectedBlockRootID )

					}
				},
					'Add Column'
				),
				el( wp.components.Button, {
					icon: 'table-col-after',
					variant: "secondary",
					style: {
						margin: "0 16px 16px 16px",
					},
					onClick: function() {
						var selectedBlockIndex = wp.data.select('core/block-editor').getBlockIndex( selectedBlock.clientId )
						var column = wp.blocks.cloneBlock( selectedBlock, { columnID: Math.random().toString(16).slice(2) } )
						var	selectedBlockRootID =  wp.data.select('core/block-editor').getBlockRootClientId( selectedBlock.clientId )			
						wp.data.dispatch('core/block-editor').insertBlocks( column, (selectedBlockIndex + 1), selectedBlockRootID )

					}
				},
					'Clone Column'
				),
				templateHasFeaturedStyle ? el( wp.components.ToggleControl, {
					className: 'ept4-column-toggle',
					label: 'Show "Featured Column" style',
					checked: props.attributes.columnPopular,
					onChange: function() {
						var newValue = !props.attributes.columnPopular
						var templateID = getTemplateID( props )
						var templateHasBorder = ( [ 2, 6 ].indexOf( templateID ) !== -1 )
						props.setAttributes( { columnPopular: newValue } )
						if( newValue && templateHasBorder ) {
							var borderColor = "#333333"
							switch( templateID ) {
								
								case 2:
									borderColor = "#6236FF"
									break
								
								default:
									//DO NOTHING ITS ALREADY BLACK
							}
							props.setAttributes( { borderColor: borderColor } )
						} else if ( templateHasBorder ) {
							props.setAttributes( { borderColor: "" } )
						}
					}
				}) : null,
				el( wp.components.ToggleControl, { 
					className: 'ept4-column-toggle',
					label: 'Show Image',
					checked: props.attributes.showImage,
					onChange: function() {
						props.setAttributes( { showImage: !props.attributes.showImage } )
					}
				}),
				el( wp.components.ToggleControl, {
					className: 'ept4-column-toggle',
					label: 'Show Button',
					checked: props.attributes.showButton,
					onChange: function() {
						props.setAttributes( { showButton: !props.attributes.showButton } )
					}
				}),
				el( wp.blockEditor.PanelColorSettings, {
					title: 'Column Colors',
					colorSettings: colorPanels
				})
			)
		)
	}
	
	function innerBlocksTemplate( props ) {
		if( props.attributes.innerBlocksTemplate ) {
			return props.attributes.innerBlocksTemplate
		}
		
		var templateID = getTemplateID( props )
		var templateObj = eval( 'fcaEpt4template' + templateID )
		var last = templateObj[ ( templateObj.length - 1 ) ]
		
		return last[1].innerBlocksTemplate		
	}
	
	function defaultBlockProps( props ) {
		var templateID = getTemplateID( props )
		
		if( templateID == false ) {
			return {}
		}
		
		return eval( 'fcaEpt4defaultBlockProps' + templateID )
		
	}
	
	function getTemplateID( props ) {
		var context = props.context
		return context["easy-pricing-tables/table/templateID"]		
	}
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))