(function(){
	
	function toggle(){
		var toggles = document.querySelectorAll( '.fca-ept-period-toggle' )
		toggles.forEach( function( el ){
			var parent_wrapper = el.closest( '.wp-block-easy-pricing-tables-toggle-table' )
			var tables = parent_wrapper.querySelectorAll('.wp-block-easy-pricing-tables-table')
			if( tables.length < 2 ) {
				return
			}
			if( el.checked ) {
				tables[0].style.display='none'
				tables[1].style.display=''
			} else {
				tables[0].style.display=''
				tables[1].style.display='none'
			}
		})
		matchRowHeight()
	}
	

	function comparisonTables() {
		var comparisonTables = document.querySelectorAll( '.wp-block-easy-pricing-tables-table.layout-8, .wp-block-easy-pricing-tables-table.layout-9' )

		for( var i = 0; i < comparisonTables.length; i++ ) {
			var comparisonList = comparisonTables[i].querySelectorAll( '.comparisonText li' )
			
			for( var j = 0; j < comparisonList.length; j++ ) {
				var featuresTextRows = comparisonTables[i].querySelectorAll( '.featuresText:not(.comparisonText) li:nth-child('+ (j+1) +')' )
				featuresTextRows.forEach( function( selector ) {
					selector.innerHTML = "<p class='comparison-mobile-text'>"+ comparisonList[j].innerHTML +":</p>" + selector.innerHTML
				})
			}
			
		}
	}
	
	function matchRowHeight(){
		var tables = document.querySelectorAll( '.wp-block-easy-pricing-tables-table.matchRowHeight' )
		
		for( var j = 0; j < tables.length; j++ ) {
			tables[j].style.visibility = "hidden"
			var elementsToMatch = [ '.planText', '.planSubText', '.priceText', '.pricePeriod', '.periodText', '.billingText' ]
			var isTemplate9 = tables[j].querySelectorAll( '.ept4Template-9' ).length > 0
			
			elementsToMatch.forEach( function( selector ) {
				
				var divs = tables[j].querySelectorAll( selector )		
				var minDivHeight = 0
				
				for( var i = 0; i < divs.length; i++ ) {							
					divs[i].style.minHeight = 0
					
					if ( divs[i].offsetHeight > minDivHeight ) {
						minDivHeight = divs[i].offsetHeight
					}
				}
				
				//SET DIV CSS
				for( var i = 0; i < divs.length; i++ ) {				
					divs[i].style.minHeight = minDivHeight + 'px'
				}
			})
			
			//IMAGES -> MAX HEIGHT
			if( !isTemplate9 ) {
				var imgDivs = tables[j].querySelectorAll( '.planImage img' )		
				var maxDivHeight = 99999
				
				for( var i = 0; i < imgDivs.length; i++ ) {							
					imgDivs[i].style.maxHeight = 'none'
					
					if ( imgDivs[i].offsetHeight && imgDivs[i].offsetHeight < maxDivHeight ) {
						maxDivHeight = imgDivs[i].offsetHeight
					}
					
				}
				
				//SET DIV CSS
				for( var i = 0; i < imgDivs.length; i++ ) {				
					imgDivs[i].style.maxHeight = maxDivHeight + 'px'
				}
			}
			
			if( isTemplate9 ) {
				var spacerBlock =  tables[j].querySelector( '.comparisonSpacer' )
				spacerBlock.style.minHeight = 0
				var columns = tables[j].querySelectorAll( '.ept4Template-9' )
				var firstFeaturesDiv = columns[1]
				var elementsToCheck = [ '.planText', '.planImage', '.priceText', '.periodText' ]
				var minHeight = 0
				elementsToCheck.forEach( function( selector ) {
					if( firstFeaturesDiv.querySelector( selector ) ) {
						minHeight += firstFeaturesDiv.querySelector( selector ).offsetHeight
					}
				})
				
				spacerBlock.style.minHeight = ( minHeight - 1 ) + 'px'
			}
			
			//RESET HEIGHTS
			var listItems = tables[j].querySelectorAll( '.featuresText li' )	
			listItems.forEach( function( item ) {
				item.style.minHeight = 0
			})
			
			//FIND LONGEST LIST..?
			var featuresTextLists = tables[j].querySelectorAll( '.featuresText' )	
			var longestList = []
			featuresTextLists.forEach( function( currentList ) {
				currentList.style.minHeight = 0
				var currentListItems = currentList.querySelectorAll('li')
				if( currentListItems.length > longestList ) {
					longestList = currentListItems
				}
			})
				
			for( var x = 1; x <= longestList.length; x++ ) {		
				var itemMinHeight = 0
				var ThislistItemRow = tables[j].querySelectorAll( ".featuresText li:nth-child("+x+")" )
				ThislistItemRow.forEach( function( currentItem ) {
					if ( currentItem.offsetHeight > itemMinHeight ) {
						itemMinHeight = currentItem.offsetHeight
					}
				})
				
				
				//SET DIV CSS
				for(  var z = 0; z < ThislistItemRow.length; z++ ) {				
					 ThislistItemRow[z].style.minHeight = itemMinHeight + 'px'
				}
			}
			
			//MATCH LIST LENGTHS
			var listMinHeight = 0
			featuresTextLists.forEach( function( currentList ) {
				if ( currentList.offsetHeight > listMinHeight ) {
					listMinHeight = currentList.offsetHeight
				}
			})
			
			//SET LIST DIV CSS
			featuresTextLists.forEach( function( currentList ) {		
				currentList.style.minHeight = listMinHeight + 'px'
			})
			
			tables[j].style.visibility = "visible"
			
		}
	}
	
	document.addEventListener( 'click', matchRowHeight )
	document.addEventListener( 'keyup', matchRowHeight )
	document.addEventListener( 'DOMContentLoaded', matchRowHeight )
	
	document.addEventListener( 'click', toggle )
	document.addEventListener( 'DOMContentLoaded', toggle )

	window.addEventListener( 'load', matchRowHeight )
	window.addEventListener( 'load', comparisonTables )
	window.addEventListener( 'load', toggle )

})()
