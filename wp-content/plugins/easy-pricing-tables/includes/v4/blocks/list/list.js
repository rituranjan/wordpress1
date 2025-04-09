( function( blocks, editor, element ) {

	var el  = React.createElement
	var useBlockProps = wp.blockEditor.useBlockProps
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps
	var selectedBlock = {}

	
	blocks.registerBlockType( 'easy-pricing-tables/list', {	
		icon: 'editor-ul',
		edit: function( props ) {
			
			var blockProps = useBlockProps({
				style: {
					listStyleType: props.attributes.listStyleType
				}
			})
			
			var innerBlocksProps = useInnerBlocksProps(  {	
				
			}, {
				
				template: [
					[ 'easy-pricing-tables/list-item', { 
						content: 'Feature 1',
						style: {
							spacing: {
								padding: {
									bottom: "18px"
								}
							}
						}
						
					}],
					[ 'easy-pricing-tables/list-item', { 
						content: 'Feature 2',
						style: {
							spacing: {
								padding: {
									bottom: "18px"
								}
							}
						}
						
					}],
					[ 'easy-pricing-tables/list-item', { 
						content: 'Feature 3',
						style: {
							spacing: {
								padding: {
									bottom: "18px"
								}
							}
						}
						
					}],
					[ 'easy-pricing-tables/list-item', { 
						content: 'Feature 4',
						style: {
							spacing: {
								padding: {
									bottom: "18px"
								}
							}
						}
						
					}],
				],
				templateLock: false,
				allowedBlocks: [ 'easy-pricing-tables/list-item' ],				
			} )
			
			return	el( 'ul', blockProps,			
				controls( props ),
				el( 'div', innerBlocksProps )
			)
		},
		save: function( props ) {
			
			var blockProps = useBlockProps.save({
				style: {
					listStyleType: props.attributes.listStyleType
				}
			})
			var innerBlocksProps = useInnerBlocksProps.save()
			
			return el( 'ul', blockProps,				
				innerBlocksProps.children
			)			
		}
	} )	
	
		
	function controls( props ) {
		var listStyleTypeOptions = [
			{
			
			  label: 'None',
			  value: 'none'
			},
			{
			  label: 'Square',
			  value: 'square'
			},
			{
			  label: 'Circle',
			  value: 'circle'
			},
			{
			  label: 'Disc',
			  value: 'disc'
			}
		]

		return el( wp.blockEditor.InspectorControls, { 
				key: 'ept4-list-controls',
				group: "typography"
			},			
			el( wp.components.SelectControl, {
				label: 'List style type',
				value: props.attributes.listStyleType,
				options: listStyleTypeOptions,
				onChange: function( selected ){ 
					if ( selected ){
						props.setAttributes( { listStyleType: selected } )
					}
				}
			})
		)
	}
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))