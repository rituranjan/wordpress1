( function( blocks, editor, element ) {

	var el  = React.createElement
	var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var selectedBlock = {}

	
	blocks.registerBlockType( 'easy-pricing-tables/list-item', {	
		icon: fcaEpt4Icons.listitem,
		merge: function ( attributes, attributesToMerge ) {
			
			var nuAtts = attributes
			nuAtts.content = ( attributes.content || "" ) + ( attributesToMerge.content || "" )
			return nuAtts
		},
		edit: function( props ) {
			
			var blockProps = useBlockProps( {
				tagName: 'li', 
				className: 'easy-pricing-tables-list-item',
				placeholder: 'Feature',
				value: props.attributes.content,
				onChange: function( newValue ){
					props.setAttributes({
						content: newValue
					})
				},
				identifier: "content", // This is needed to prevent an error message
				// Functions provided in blockProps by useBlockProps()
				onReplace: props.onReplace,
				onMerge: props.mergeBlocks,
				onRemove: props.onRemove
			})
			
			if( fcaEpt4ListItemData.edition === "Free" ) {
				return	el( 'div', {},
					fcaEpt4ListItemData.edition === "Free" ? controls( props ) : null,		
					el( wp.blockEditor.RichText, blockProps	)
				)
			}
			
			return	el( wp.blockEditor.RichText, blockProps	)
			
			function controls( props ) {
				return el( wp.blockEditor.InspectorControls, { 
						key: 'ept4-list-item-controls',
						group: 'styles'
					},
					el( 'div', { 
						style: { 
							padding: '16px',
							fontStyle: 'italic',
							borderTop: '1px solid #ddd'
						}
						},
						el( 'p', {}, 'Enjoying Easy Pricing Tables?' ),
						el( 'p', {}, 'Upgrade and get premium design & customization options, WooCommerce integration & much more.' ),
					
						el( wp.components.Button, {
							variant: 'secondary',
							onClick: function(){
								window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
							}
						}, 'Get Premium' )
					)
				)
			}
		},
		save: function( props ) {			
			
			var blockProps = useBlockProps.save({
				tagName: 'li', 
				className: 'easy-pricing-tables-list-item',
				value: props.attributes.content			
			})
			
			return el( wp.blockEditor.RichText.Content, blockProps )	
		}
	} )	
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))