( function( blocks, editor, element ) {

	var el  = React.createElement
	var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var selectedBlock = {}
	
	blocks.registerBlockType( 'easy-pricing-tables/field', {	
		icon: 'editor-paragraph',
		edit: function( props ) {
			
			var blockProps = useBlockProps( {
				tagName: 'p', 
				className: 'easy-pricing-tables-field',
				placeholder: 'Feature',
				value: props.attributes.content,
				onChange: function( newValue ){
					props.setAttributes({
						content: newValue
					})
				}
			})
			
			return	el( 'div', {},
				fcaEpt4FieldData.edition === "Free" ? controls( props ) : null,		
				el( wp.blockEditor.RichText, blockProps	)
			)
			
			function controls( props ) {
				return el( wp.blockEditor.InspectorControls, { 
						key: 'ept4-field-controls',
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
				tagName: 'p', 
				className: 'easy-pricing-tables-field',
				value: props.attributes.content				
			})
			
			return el( wp.blockEditor.RichText.Content, blockProps	)	
		}
	} )	
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))