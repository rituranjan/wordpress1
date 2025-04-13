//GLOBAL
var table = $('#dataTable');

function is_touch_device() {
    var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
    var mq = function(query) {
        return window.matchMedia(query).matches;
    }
    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
        return true;
    }
    var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
    return mq(query);
}

function onlyNumbersLettersOrDashes(obj) {
    var regex = new RegExp("[^a-zA-Z0-9-]");
    if (obj.value.search(regex) != -1) {
        obj.value = obj.value.replace(regex, '');
        alert('Only letters, numbers and dashes allowed in this field.');
    }
}

function sanitizeNames(obj) {

    var regex = new RegExp("[^" + "a-z" + decodeURI("\\u00C0-\\u017F") + "0-9_.,' ()&-]", "i");

    if (obj.value.search(regex) != -1) {
        var match = obj.value.match(regex);
        if (match == " ") var space = "Empty space";
        else var space = "";
        obj.value = obj.value.replace(regex, '');
        alert('Illegal Character Removed: ' + match + space + "\n\nAllowed Input:\n------------------\nletters\nnumbers: 0-9\nempty space\nparentheses: ( )\nunderscore: _\nperiod: .\ncomma: ,\napostrophe: \'\nampersand: &\nhyphen: -");
    }
}


//Textarea
function growTextarea(i, elem) {
    var elem = $(elem);
    var resizeTextarea = function(elem) {
        var scrollLeft = window.pageXOffset || (document.documentElement || document.body.parentNode || document.body).scrollLeft;
        var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
        elem.css('height', 'auto').css('height', elem.prop('scrollHeight'));
        window.scrollTo(scrollLeft, scrollTop);
    };

    elem.on('input', function() {
        resizeTextarea($(this));
    });

    resizeTextarea($(elem));
}



//MODULE functions
const preselectCustomer = () => {
    // Check if the URL contains the "&customer=16" parameter
    const urlParams = new URLSearchParams(location.search);
    for (const [key, value] of urlParams) {
        if (key === 'customer') {
            const customerSelectBox = document.getElementById('to_name');

            // Loop through the available customers select box
            for (let i = 0; i < customerSelectBox.options.length; i++) {
                if (customerSelectBox.options[i].value === value) {
                    // Set the selected attribute to 'true' for the matching customer id
                    customerSelectBox.options[i].selected = true;
                    customerSelectBox.dispatchEvent(new Event("change"));
                }
            }
        }
    }
}


function getCustomerAddress(customerId) {

    // means 'New Customer' is selected 
    if (customerId == 0) {

        document.getElementById('rowNewCustomer').style.display = '';
        document.getElementById('to_new_customer').value = '';
        document.getElementById('to_address').value = '';

    }

    // means existing customer is selected and an address must be fetched and inserted 
    else {

        document.getElementById('rowNewCustomer').style.display = 'none';

        ajax_get("/getCustomerAddress.inc.php?customer=" + customerId, function() {

            //alert(xmlhttp.responseText); 

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                // get the whole address record 
                var node = xmlhttp.responseXML.documentElement.getElementsByTagName("address");

                // to_address
                var to_address = node[0].getElementsByTagName("to_address")[0];

                if (to_address.hasChildNodes()) var address = to_address.firstChild.nodeValue;
                else var address = '';

                document.getElementById("to_address").innerHTML = address;
                $('#to_address').each(growTextarea);

                //if there are two empty '.line', preselect previous tax: 
                if (table.find('.line').length == 2 && $('.lineTotal:eq(0)').val() == "0.00" && $('.lineTotal:eq(1)').val() == "0.00") {

                    //Retrieve previous tax-id's for this customer: 
                    var node = xmlhttp.responseXML.documentElement.getElementsByTagName("taxes");

                    var taxes = node[0].getElementsByTagName("tax_id")[0];
                    if (taxes.hasChildNodes()) {
                        if (taxes.firstChild.nodeValue > 0) var tax_id = taxes.firstChild.nodeValue ? .trim();
                        else var tax_id = ''
                    };
                    taxes = node[0].getElementsByTagName("tax2_id")[0];
                    if (taxes.hasChildNodes()) {
                        if (taxes.firstChild.nodeValue > 0) var tax2_id = taxes.firstChild.nodeValue ? .trim();
                        else var tax2_id = ''
                    };
                    taxes = node[0].getElementsByTagName("tax3_id")[0];
                    if (taxes.hasChildNodes()) {
                        if (taxes.firstChild.nodeValue > 0) var tax3_id = taxes.firstChild.nodeValue ? .trim();
                        else var tax3_id = ''
                    };

                    //Clear all taxes selected: 
                    $.each($('.NoTax'), function(i, obj) {

                        $(obj).prop("checked", true);
                        changeTaxSelect(obj);

                    });

                    //Update taxes: 
                    if (tax_id > 0 || tax2_id > 0 || tax3_id > 0) {

                        //Select the tax-lines to be checked: 
                        //ul[data-group='Companies']
                        tax_lines = $('.SingleTax[data-component_id="' + tax_id + '"], .SingleTax[data-component_id="' + tax2_id + '"], .SingleTax[data-component_id="' + tax3_id + '"]');

                        //Update each tax line with the customer preselected tax: 
                        $.each(tax_lines, function(i, obj) {

                            $(obj).prop("checked", true);
                            changeTaxSelect(obj);

                        });

                    }

                }

            }
        });


    }
}

function numberToCurrency(num) {
    //Outputs a string for use in locations that need a default 0.00 value.
    if (num) {
        return (Number(num).toFixed(2));
    } else {
        return ('0.00');
    }
}

function numberFormat(value, precision = 2) {
    value = parseFloat(value); // make sure we deal with type Number

    if (isNaN(value) || value === null || value === undefined) {
        return 0.00;
    }
    // get the rounding scale from provided precision. 
    const rounding_scale = Math.pow(10, precision);
    // Rounding scale is used to multiply and divide the provided value by, so that in between we can work with int (whole number). 
    // add the smallest representable number (epsilon) to make sure we round up
    const roundedValue = Math.round((value * rounding_scale) * (1 + Number.EPSILON)) / rounding_scale;
    return roundedValue.toFixed(precision);
}

// adds provided values treating them as of type Number
function sum(...values) {
    return values.reduce((accumulator, currentValue) => {
        const num = isNaN(currentValue) ? 0.00 : Number(currentValue);
        return accumulator + num;
    }, 0);
}

// Row Sums
function calculateLineTotal(elem) {
    var elem = $(elem);
    var row = elem.closest('.line');
    var qty = row.find('.lineQty').val() || 0;
    var cost = row.find('.linePrice').val() || 0;
    var linetotal = (Number(qty) * Number(cost)) || 0;
    row.find('.lineTotal').val(numberToCurrency(linetotal));
}


function reCalculate() {

    //Calculate total discount
    var total_discount = 0;
    table.find('.isDiscount').find('.lineTotal').each(function() {
        total_discount += Math.abs(Number($(this).val()));
    });

    //Display discounts
    $('#sum_discount').val(numberToCurrency(total_discount));
    (total_discount > 0 ? $('#sum_discountTR').show() : $('#sum_discountTR').hide());

    //Calculate subtotal
    var subtotal = 0;
    table.find('.line').not('.isDiscount').find('.lineTotal').each(function() {
        subtotal += Number($(this).val());
    });
    $('#sum_subtotal').val(numberToCurrency(subtotal));

    var amount_paid = Number($('#amount_paid').val());
    var new_sum_tax = 0;

    //Calculate tax if checked
    if ($('#customization_tax').is(':checked')) new_sum_tax = reCalculateTax();

    var sum_total = sum(subtotal, new_sum_tax, -total_discount);
    var balance_due = sum_total - amount_paid;

    //Write back into the form
    $('#sum_total').val(numberFormat(sum_total, 2));
    $('#balance_due').val('$' + numberFormat(balance_due, 2))

    if ($('body').hasClass('pub')) {
        $('#insert_balance').text(currencyCode + numberToCurrency(balance_due));
    }

}


function reCalculateTax() {

    var rowCount = table.find('.line').length;
    var discount = $('#sum_discount').val() || 0;

    var sum_of_all_taxes = 0;
    var subtotal_positive_lines = 0;

    //Discount should only be distributed on positive lines:
    for (rowNo = 0; rowNo < rowCount; rowNo++) {

        var row = table.find('.line').eq(rowNo);
        var p = Number(row.find('.linePrice').val()) || 0;
        var q = Number(row.find('.lineQty').val()) || 0;
        var rowAmount = numberFormat(p * q, 2);
        if (rowAmount > 0) subtotal_positive_lines = sum(subtotal_positive_lines, rowAmount);

    }
    if (subtotal_positive_lines > 0) var discount_per_subtotal = discount / subtotal_positive_lines;
    else var discount_per_subtotal = 0;


    var taxPct = [];
    var taxSums = [];
    var taxComponentName = [];
    var taxComponentNameValue;


    table.find('.line').each(function() {

        lineTotal = numberFormat($(this).find('.lineTotal').val() || '0', 2);
        var cell = $(this).find('.tax1');

        for (i = 1; i < 4; i++) {

            var taxPctValue = Number(cell.find('input').eq(i).val());

            if (taxPctValue > 0) { //both the tax percentage and the line amount must be over 0 

                var new_value = '';
                var taxComponentId = cell.find('input').eq(i + 3).val();
                var taxComponentNameValue = cell.find('.SingleTax[data-component_id=' + taxComponentId + ']').data("name");

                for (j = 0; j < taxPct.length; j++) {

                    if (taxPctValue.toFixed(4) == (taxPct[j] * 1).toFixed(4) && taxComponentNameValue == taxComponentName[j]) new_value = j;

                }

                // calculate the tax per line after subtracting the discount proportionally 
                if (new_value === "") {

                    taxPct.push(Math.round(taxPctValue * 1e4) / 1e4);
                    taxComponentName.push(taxComponentNameValue);
                    var line_total_after_discount = (lineTotal < 0) ? lineTotal : lineTotal - (lineTotal * discount_per_subtotal);
                    const line_tax = line_total_after_discount * taxPctValue / 100;
                    taxSums.push(line_tax);

                } else {

                    // this line uses a tax percentage already in the array; update the tax sums for that tax percentage 
                    var old_tax = taxSums[new_value];
                    var line_total_after_discount = (lineTotal < 0) ? lineTotal : lineTotal - (lineTotal * discount_per_subtotal);
                    const line_tax = line_total_after_discount * taxPctValue / 100;
                    taxSums.splice(new_value, 1, sum(line_tax, old_tax));

                }

            }

        }

    }); // end each

    // Sort the two arrays 
    if (taxPct.length > 1) {

        var tempPct = [];
        var tempSums = [];
        var tempName = [];
        var taxPctLength = taxPct.length;

        for (i = 0; i < taxPctLength; i++) {

            for (x = 0; x < taxPct.length; x++) { //runs through the array and finds the highest value

                taxPct[x] = taxPct[x] * 1; //multiplies with 1 to cast the value as a number to get numerical instead of alphabetical comparison		 		

                if (x == 0) {
                    highestValue = taxPct[0];
                    highestPos = 0;
                }

                if (taxPct[x] > highestValue) {
                    highestValue = taxPct[x];
                    highestPos = x;
                }
            }

            tempPct.push(highestValue); //puts the highest value found in a new temp array
            taxPct.splice(highestPos, 1); //removes the value placed in the temp array from the original array
            tempTax = numberFormat(taxSums[highestPos], 2);
            tempSums.push(tempTax);
            sum_of_all_taxes = sum(sum_of_all_taxes, tempTax);
            taxSums.splice(highestPos, 1);
            tempName.push(taxComponentName[highestPos]);
            taxComponentName.splice(highestPos, 1);
        }

        taxPct = tempPct;
        taxSums = tempSums;
        taxComponentName = tempName;
    } else {

        if (taxSums[0] * 1 > 0) {
            sum_of_all_taxes = numberFormat(taxSums[0], 2);
        }
    }


    var tableTaxes = $('#tableTaxes'); //table for inserting the tax sums
    var taxSum_rows = tableTaxes.find("input[name='line_tax[]']").length;

    // remove all current tax rows 
    if (taxSum_rows > 0) {
        tableTaxes.find('tr').remove();
    }

    if (taxPct.length > 0) {

        for (i = 0; i < taxPct.length; i++) {

            var taxName = taxComponentName[i];
            var taxPctOutput = pad_zeros(taxPct[i] * 1);
            var taxSumsOutput = numberFormat(taxSums[i], 2);

            var html = '<tr><td class="sum-label">+ ' + taxName + ' (' + taxPctOutput + '%)</td>' +
                '<td class="sum-number"><input type="text" name="line_tax[]" value="' + taxSumsOutput + '" readonly></td></tr>';

            tableTaxes.append(html);

        }
    }

    return sum_of_all_taxes;
}


//delete row
table.on('click', '.btnDeleteRow', function(e) {
    var rowCount = table.find('.zap').length;

    if (rowCount > 1) {
        $(this).closest('.line').remove();
    }
    rowCount--;

    if (rowCount <= 1) {
        $(document).find('.btnDeleteRow').hide();
    }
    reCalculate();
});

const loadDefaultItemCategories = (row) => {
    const selectBox = row.find('[name="item[]"]');
    selectBox.html('');
    Object.keys(defaultItemsList).map(category => {
        const option = $("<option>").val(category).text(defaultItemsList[category]);
        selectBox.append(option);
    });
}


// Add row

function addRow() {

    $('.hasAutocomplete').unbind();

    table.find('.taxDropdown').hide();
    table.find('.focus').removeClass('focus');

    //Clone row and attached functions
    var lastRow = table.find('.line').last();
    var newRow = lastRow.clone(true, false);

    //Reset all values except tax
    newRow.find('input.linePrice, input.lineQty, textarea, select, input.hasAutocomplete').val('');

    // Set default item list for selection; prevent multiple instances of Prodcut being added via Inventory
    // Skip if pub
    if (typeof defaultItemsList !== 'undefined') {
        loadDefaultItemCategories(newRow)
    }

    newRow.find('.growTextarea').css('height', 'auto');
    newRow.find('[name="expense_id[]"]').val('0');
    newRow.find('.clearOnNew').val('');
    newRow.find('.lineTotal').val('0.00');
    newRow.find('.openTaxDropdown').prop('disabled', false)
    newRow.find('.focus').removeClass('focus');
    newRow.removeClass('isDiscount isExpense isInventory onlyPositive');
    newRow.find('.hidden-inputs').remove(); //remove any attached expenses (see modal)
    newRow.insertAfter(lastRow);

    if ($('.zap').length > 1) {
        $('.zap').find('.btnDeleteRow').show();
    }

    $('.hasAutocomplete').each(createAuto); //unbind then reattach, so it understands itself as separate
    $('.growTextarea').each(growTextarea);

}

function toggleLogo() {

    var isChecked = $('#customization_logo').is(':checked');
    var imageName = $('#customization_logoFilename').val();
    if (imageName) var hasImage = (imageName.length > 0);

    if (isChecked) {

        if (hasImage) {

            //Show image & delete-button
            $('#btnDeleteLogo').show();
            $('#upload_area').removeClass('empty').show();
            $('#upload_area').find('img').attr('src', baseHref + imageName).show();

        } else {

            //Show upload container
            $('#btnUploadLogo').show();
            $('#btnDeleteLogo').hide();
            $('#upload_area').addClass('empty').addClass('focus').addClass('yournamehere').show();
            $('#upload_area').find('img').attr('src', '').hide();

        }

        $('#modeEdit').addClass("logoShown");

    } else {

        //Remove logo container
        $('#upload_area').hide();
        $('#btnUploadLogo').hide();
        $('#btnDeleteLogo').hide();
        $('#customization_logoFilename').val('');
        $('#modeEdit').removeClass("logoShown");

    }

}

function updateLogo() {
    // #customization_logoFilename onchange event fired from ajaxuploader.php when image successfully uploads 
    $('#upload_area').find('img').attr('src', baseHref + $('#customization_logoFilename').val()).show();
    $('#btnUploadLogo').hide();
    $('#btnDeleteLogo').show();
    $('#upload_area').show().removeClass('empty').removeClass('focus').removeClass('yournamehere').removeClass('uploading');
}

function deleteLogo() {
    $('#customization_logoFilename').val('');
    $('#upload_area').addClass('empty').addClass('yournamehere');
    $('#upload_area').find('img').attr('src', '').hide();
    $('#btnDeleteLogo').hide();
    $('#btnUploadLogo').show();
}


function pad_zeros(value) {

    //Stop if no value exists
    if (!Math.abs(value) > 0) return;

    //Set everything to at least two decimals; remove 3+ zero decimals, keep non-zero decimals
    var new_value = value * 1; //removes trailing zeros
    new_value = new_value + ''; //casts it to string
    pos = new_value.indexOf('.');

    if (pos == -1) new_value = new_value + '.00';
    else {

        var integer = new_value.substring(0, pos);
        var decimals = new_value.substring(pos + 1);
        while (decimals.length < 2) decimals = decimals + '0';
        new_value = integer + '.' + decimals;
    }

    return new_value;
}


function correctInputValue(obj) {

    var elem = $(obj);
    var row = elem.closest('.line');
    var v = Number(elem.val());

    //Stop if no value exists
    if (!Math.abs(v) > 0) return;

    if (row.hasClass('isDiscount')) {

        //In discount rows, unit price is negative
        if (elem.hasClass('linePrice')) elem.val(pad_zeros(-1 * Math.abs(v)));
        //In discount rows, qty is positive
        if (elem.hasClass('lineQty')) elem.val(pad_zeros(Math.abs(v)));

    } else if (row.hasClass('isExpense') || row.hasClass('onlyPositive')) {

        //In expense rows, all values should be positive
        elem.val(pad_zeros(Math.abs(v)));

    }

}

function ajax_get(url, passedFunction) {

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = passedFunction;
    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
}

table.on('change', 'select.selectItem', function(e) {

    //reset active (selected) line
    $('.line').removeClass('active');

    var self = $(this);
    var row = self.closest('.line');
    var selectedOption = self.find(":selected").val();
    var taxButton = row.find('.openTaxDropdown');

    if ($.inArray(selectedOption, ['Discount', 'Expense', 'Discount_aynax', 'Payment_aynax']) >= 0) {
        var isDisabled = true;
    }

    if (isDisabled) {

        //Set tax to No tax
        NoTaxSelector = row.find(".taxDropdown").find("input").first();
        NoTaxSelector.click(); //NOTE: Clicks the 'No Tax' checkbox. This will run reCalculate and remove any tax from the totals.
        NoTaxSelector.prop("checked", true);
        taxButton.prop('disabled', true);

    } else {

        taxButton.prop('disabled', false);

    }


    var p = Math.abs(Number(row.find('.linePrice').val()));
    var q = Math.abs(Number(row.find('.lineQty').val()));

    if (selectedOption == 'Discount_aynax') {

        row.addClass('isDiscount');

        // if price is not null or zero, make it negative number
        if (p > 0) row.find('.linePrice').val(pad_zeros(-1 * p));

        // if qty is not null or zero, make it positive number
        if (q > 0) row.find('.lineQty').val(pad_zeros(q));

        // Display discount in sums at bottom of page
        $('#sum_discountTR').show();

    } else {
        row.removeClass('isDiscount');
        if (p > 0) row.find('.linePrice').val(pad_zeros(p));
        if (q > 0) row.find('.lineQty').val(pad_zeros(q));
    }

    if (selectedOption == 'Expense') {

        if (self.hasClass('permEx')) {
            row.addClass('active');
            row.addClass('isExpense');
            $('#modalAttachExpense').modal('show');
        } else {
            //for employee
            row.addClass('active');
            row.addClass('onlyPositive');
        }

        //Prevent negative values in Expense 
        // if price is not null or zero, make it positive number
        if (p > 0) row.find('.linePrice').val(pad_zeros(p));

        // if qty is not null or zero, make it positive number
        if (q > 0) row.find('.lineQty').val(pad_zeros(q));

    }

    calculateLineTotal($(this));
    reCalculate();

});


function newLineClick() {

    addRow();
    var taxButton = table.find('.line:last-child').find('.openTaxDropdown');
    taxButton.prop('disabled', false);

}


function updateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
    } else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        } else
            return url;
    }
}

function createDateQuery(date) {
    var date = date;
    var month = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1);
    var day = (date.getDate()) < 10 ? '0' + (date.getDate()) : (date.getDate());
    var datestring = encodeURIComponent(month + '/' + day + '/' + date.getFullYear());
    return datestring;
}


//Autocomplete
var visible;

function createAuto(i, elem) {

    var input = $(elem);
    var dropdown = input.closest('.dropdown');
    var listItems = dropdown.find('.dropdown-radio');

    listItems.hide();
    listItems.each(function() {
        $(this).data('value', $(this).text());
        //!important, keep this copy of the text outside of keyup/input function
    });

    input.on("input", function(e) {

        var query = input.val().toLowerCase();
        if (query.length > 1) {

            dropdown.addClass('open').addClass('in');

            listItems.each(function() {

                var text = $(this).data('value');
                if (text.toLowerCase().indexOf(query) > -1) {

                    var textStart = text.toLowerCase().indexOf(query);
                    var textEnd = textStart + query.length;
                    var htmlR = text.substring(0, textStart) + '<strong>' + text.substring(textStart, textEnd) + '</strong>' + text.substring(textEnd + length);
                    $(this).find('ins').html(htmlR);
                    $(this).show();

                } else {

                    $(this).hide();

                }
            });

            visible = listItems.filter(':visible');

        } else {
            listItems.hide();
            dropdown.removeClass('open').removeClass('in');
        }
    });



    input.on("keyup", function(e) {
        switch (e.keyCode) {
            case 13: //enter
                dropdown.removeClass('open').removeClass('in');
                break;
            case 40: //down arrow
                visible.eq(0).focus();
                break;
            default:
                return;
        }

    });

    listItems.on("keyup", function(e) {

        var li = $(this);

        if (visible.length > 0) {

            switch (e.keyCode) {
                case 13: //enter
                    li.trigger('click');
                case 38: //up arrow
                    li.prevAll(':visible').first().focus();
                    break;
                case 40: //down arrow
                    li.nextAll(':visible').first().focus();
                    break;
                default:
                    return;
            }
        }

    });

    //prevent page to scroll when scrolling the pricelist
    listItems.on("keydown", function(e) {
        if (e.keyCode === 38 || e.keyCode === 40) e.preventDefault();
    });

    listItems.on('click', function(e) {

        var elem = $(this);
        var row = elem.closest('.line');

        row.find('.usedOnlyForArrowKeysToFocus').prop('checked', false);

        var txt = elem.text().replace(/^\s+|\s+$/g, ""); //remove leading and trailing whitespace
        input.val(txt);
        dropdown.removeClass('open').removeClass('in');

        var itemtype = elem.data('itemtype');
        row.find("select.selectItem").val(itemtype);

        var price = elem.data('price');
        row.find(".linePrice").val(price);
        calculateLineTotal(elem);

        var tax1 = elem.data('tax1');
        if (Number(tax1) > 0) {

            if (!$('#customization_tax').is(':checked')) {
                $('#customization_tax').prop('checked', true).trigger("change");
            }

            row.find('.taxDropdown').find('input[type="checkbox"]').prop("checked", false);
            row.find('.openTaxDropdown').removeClass('placeholder');

            //check first tax
            var cb1 = row.find(".SingleTax[data-component_id='" + elem.data('taxid1') + "']");
            cb1.prop('checked', true);
            updateTax(cb1);

            //check second tax
            var tax2 = elem.data('tax2');
            var cb2 = row.find(".SingleTax[data-component_id='" + elem.data('taxid2') + "']");
            if (Number(tax2) > 0) {
                cb2.prop('checked', true);
                updateTax(cb2);
            }

            //check third tax
            var tax3 = elem.data('tax3');
            var cb3 = row.find(".SingleTax[data-component_id='" + elem.data('taxid3') + "']");
            if (Number(tax3) > 0) {
                cb3.prop('checked', true);
                updateTax(cb3);
            }

        }
        reCalculate();
        reCalculateTax();


    });

}



// FUNCTIONS ON PAGE LOAD //

(function($) {


    $('.hasAutocomplete').each(createAuto);


    // Toggle options above the form
    $('#btnToggleOptions').click(function() {
        $('#options').toggleClass('open');
        var buttontext = ($('#options').hasClass('open')) ? "Hide Customization Options" : "Show Customization Options";
        $(this).text(buttontext);
    });


    //Focus background
    table.on('click', 'td', function(e) {
        $('.focus').not($(this)).removeClass('focus');
        $(this).addClass('focus');
        $(this).find('input,textarea,select').filter(':visible:first').focus();
    });
    table.on('focusin', 'td', function(e) {
        $('.focus').not($(this)).removeClass('focus');
        $(this).addClass('focus');
    });


    // Expand textareas as you type
    $('.growTextarea').each(growTextarea);


    //Datepickers

    // Regular datepicker
    $('.hasDatepicker').datepicker({
        todayHighlight: false,
        format: "mm/dd/yyyy",
        autoclose: true
    });

    // Ranged datepicker
    $('.hasDaterange').datepicker({
        todayHighlight: false,
        format: "mm/dd/yyyy",
        autoclose: true
    });

    //Date range in the table filter app/__common/html/date_range.php
    $('.hasDaterange .startDate').each(function() {
        $(this).on('changeDate', function(e) {

            var url = window.location.href;
            var datequery = createDateQuery(e.date);
            var endDate = encodeURIComponent($('.endDate').val());

            url = updateQueryString('from', datequery, url);
            url = updateQueryString('to', endDate, url);

            window.location = url;

        });
    });

    $('.hasDaterange .endDate').each(function() {
        $(this).on('changeDate', function(e) {

            var url = window.location.href;
            var datequery = createDateQuery(e.date);
            var startDate = encodeURIComponent($('.startDate').val());

            url = updateQueryString('from', startDate, url);
            url = updateQueryString('to', datequery, url);
            window.location = url;

        });
    });

    //Dropdown options in the table filter
    $('.dateOption').click(function() {
        var today = new Date();

        var dd = today.getDate();
        var mm = today.getMonth();
        var yyyy = today.getFullYear();
        var quarterMonth = (Math.floor(mm / 3) * 3);

        var option = $(this).data("value");
        var url = window.location.href;
        var start;
        var end;

        if (option == "this_month") {
            start = new Date(yyyy, mm, 1);
            end = new Date(yyyy, mm + 1, 0);
        } else if (option == "last_month") {
            start = new Date(yyyy, mm - 1, 1);
            end = new Date(yyyy, mm, 0);
        } else if (option == "this_quarter") {
            start = new Date(yyyy, quarterMonth, 1);
            end = new Date(yyyy, quarterMonth + 3, 0);
        } else if (option == "last_quarter") {
            start = new Date(yyyy, quarterMonth - 3, 1);
            end = new Date(yyyy, quarterMonth, 0);
        } else if (option == "this_year") {
            start = new Date(yyyy, 0, 1);
            end = new Date(yyyy + 1, 0, 0);
        } else if (option == "last_year") {
            start = new Date(yyyy - 1, 0, 1);
            end = new Date(yyyy, 0, 0);
        } else {
            var parent = $(this).parents('.dropdown-menu');
            start = new Date(parent.data('mindate'));
            end = new Date(parent.data('maxdate'));
        }

        var startquery = createDateQuery(start);
        var endquery = createDateQuery(end);
        url = updateQueryString('from', startquery, url);
        url = updateQueryString('to', endquery, url);
        window.location = url;
    });


    //Filter Customer
    $('.filterCustomer').click(function(page) {
        var customerID = $(this).data('value');
        var url = window.location.href;
        url = updateQueryString('customer', customerID, url);
        url = updateQueryString('p', '1', url);
        window.location = url;
    });

    $('.filterStatus').click(function(page) {
        var status = $(this).data('value');
        var url = window.location.href;
        url = updateQueryString('status', encodeURI(status), url);
        window.location = url;
    });


    //Update Filter
    //run on page load, when URL has been updated
    $('.filterDropdown').find(".dropdown-item").each(updateParentText);

    //Update SelectBox
    //run when page is not reloading
    $('.selectDropdown').find(".dropdown-item").on("click", updateParentText);

    function updateParentText(i, elem) {
        if (!$(elem).hasClass('active')) {
            return;
        } else {
            $(this).closest('.dropdown').find('ins').html($(this).text());
        }
    }


    //Print Button
    $('.btnPrint').click(function() {
        if (isFormEdited()) {
            $('#modalSave').modal('show');
        } else if (typeof account_upgrade_url !== 'undefined' && !!account_upgrade_url) {
            const currentUrl = window.location.href;
            window.history.pushState({}, '', '/app/invoices/print');
            window.location.href = account_upgrade_url;
            window.history.replaceState({}, '', currentUrl);
        } else {
            window.print();
        }
    });

    //Dismissable alert
    $(".alert .close").click(function(e) {
        $(this).closest('.alert').removeClass('show');
    });


    //Submit form button with spinner
    $('.btnSubmit').click(function(e) {
        e.preventDefault();

        //disable submit button & add spinner
        $(this).prop('disabled', true).addClass('btn-spinner');
        $(this).closest('form').submit();

        //reenable button if form failed to submit the first time
        setTimeout(function() {
            $(this).prop('disabled', false).removeClass('btn-spinner');
        }, 6000);

    });


    //Toggle PO
    $('#customization_poNumber').change(function() {

        if ($(this).is(':checked')) {
            $('#po_numberTR').show();
            $('#po_number').focus();
        } else {
            $('#po_numberTR').hide();
        }

    });


    //Toggle Tax
    $('#customization_tax').change(function() {

        if ($(this).is(':checked')) {
            $('.taxItem').show();
            table.addClass('with-taxes');
        } else {
            $('.taxItem').hide();
            table.removeClass('with-taxes');
            $('#tableTaxes').find('tr').remove();
        }

        $('.NoTax').click().prop('checked', true); //simulated click causes yellow focus
        $('.taxItem').removeClass('focus'); //remove yellow focus

    });


    //Logo Input

    $('#logo_file_input').click(function() {
        $(this).val(''); //Reset value of file input onClick so onChange event is always called after upload
    });

    $('#logo_file_input').change(function() {

        // checking for allowed file types before upload starts
        var ext = $('#logo_file_input').val().split('.').pop().toLowerCase();

        if ($.inArray(ext, ['bmp', 'gif', 'png', 'jpg', 'jpeg']) == -1) {

            // clearing div and creating the error message in the upload area
            $('#upload_area').removeClass();
            $('#upload_area').addClass('empty uploading');
            $('#upload_area').empty();
            $('#upload_area').append("<div class='error-uploading'><i class='xe033'></i><span> File type isn't allowed: " + ext + ".<br>Accepted types: jpg, jpeg, gif, bmp and png.</span></div>");

            // clearing input field containing the custom logo file path
            $('#logo_file_input').val(null);

            return;
        }

        $('#upload_area').removeClass('yournamehere').addClass('uploading');

        // function ajaxUpload(form,url_action,id_element,html_show_loading,html_error_http)
        ajaxUpload(this.form, '/ajaxuploader.php', 'upload_area', '<div class="is-uploading"><i class="spinner"></i><p>File Uploading Please Wait...</p></div>', '<div class="error-uploading"><i class="xe033"></i><span>There was an error; please try again.</span></div>');
        return false;

    });

    toggleLogo();

    //hide dropdown
    table.on('focus', ".lineQty, .linePrice", function() {
        $('.dropdown').removeClass('open').removeClass('in');
    });

    //row sums
    table.on('blur', ".lineQty, .linePrice", function() {
        correctInputValue($(this));
        calculateLineTotal($(this));
        reCalculate();
    });


    //TYPING

    function numbersOnly(elem) {
        var elem = $(elem);
        // -? before 0-9 allows negative numbers
        var str = elem.val().replace(/[^-?0-9.]/g, '').replace(/\.{2,}/g, '.');
        var first, last;
        while ((first = str.indexOf(".")) !== (last = str.lastIndexOf("."))) {
            str = str.substring(0, last) + str.substring(last + 1);
        }
        elem.val(str);
    }

    //Numbers 

    table.on('input', ".numbersOnly", function() {
        numbersOnly($(this));
    });

    $(document).on('blur', ".isCurrency", function() {
        var elem = $(this);
        var v = elem.val();
        if ($.isNumeric(v)) {
            elem.val(Number(v).toFixed(2));
        }
    });

    $(document).on('input', ".isCurrency", function() {
        numbersOnly($(this));
    });

    //Quantity
    //const roundAccurately = (number, decimalPlaces) => Number(Math.round(number + "e" + decimalPlaces) + "e-" + decimalPlaces);
    //$(this).val( roundAccurately( $(this).val(), 5 ) );  //Future: Qty shouldnt require .00

    table.on('blur', ".lineQty, .linePrice", function() {
        var elem = $(this);
        var v = elem.val();
        if ($.isNumeric(v)) {
            elem.val(pad_zeros(v));
        }
    });


    //Expense 
    table.on('blur', ".onlyPositive", function() {
        var elem = $(this);
        if ($.isNumeric(elem.val())) {
            elem.val(Math.abs(elem.val())).toFixed(2);
        }
    });


    //Typing only alphanumeric minus coding and non-ascii chars
    $('.typeAlpha').on("input", function(e) {
        var pos = this.selectionStart;
        //remove anything that is not:  A-z, 0-9 and #@_.,'& ()-/"*
        const toBeReplaced = /[^A-Za-z\n\r0-9#@_.,\'& ()-\/"*]/g;
        var str = $(this).val().replace(toBeReplaced, '').replace(/\.{2,}/g, '.');
        var len = str.length;
        $(this).val(str);
        this.selectionEnd = pos - (len - str.length);
    });


    //up/down arrow keys
    table.on('keyup', ".lineQty, .linePrice", function(e) {
        var code = e.keyCode || e.which;
        if (code == 38 || code == 40) {
            var row = $(this).closest('.line');
            var cell = $(this).closest('td').index();
            if (code == 38 && row.index() > 0) {
                //go up
                row.prev().find('td').eq(cell).find('input').focus();
            }
            if (code == 40) {
                //go down
                row.next().find('td').eq(cell).find('input').focus();
            }
        }
    });


    //Deactivate pdf-button for 3 sec. after being clicked
    $(".btnPDF").on('click', function() {

        $(".btnPDF").addClass('btn-spinner');
        setTimeout(function() {
            $(".btnPDF").removeClass('btn-spinner');
        }, 3000);

    });


    //Recalculate on page load to show tax sums
    $("select.selectItem").each(function() {
        var elem = $(this);
        var row = elem.closest('.line');
        if (elem.val() == "Discount_aynax") row.addClass('isDiscount');
        if (elem.val() == "Expense") {
            if (elem.hasClass('permEx')) {
                row.addClass('isExpense');
            } else {
                row.addClass('onlyPositive');
            }
        }
        if (elem.val().includes("Inventory_aynax")) row.addClass('isInventory');
    });

    reCalculate();


    //Document event listeners
    $(document).on('click', function(e) {
        var origin = $(e.target);

        //Hide taxDropdown on click outside
        if (!origin.closest('.dropdown').length) {
            $('.taxDropdown').hide();
            $('.taxDropdown').closest('.focus').removeClass('focus');

            $('.dropdown').removeClass('open');
        }

        //Hide .focus on click outside
        var focus_parent = origin.closest('.focus');
        $('.focus').not(focus_parent).removeClass('focus');

    });



    //Submit form
    var form = $('#invoice_form');
    var formdata = null;
    window.onload = function() {
        formdata = $.param($.map(form.serializeArray(), function(v, i) {
            return (v.name == "cf-turnstile-response" || v.name == "emailaddress" || v.name == "password") ? null : v;
        }));
    };

    form.submit(function() {
        window.onbeforeunload = null
    })

    window.onbeforeunload = function() {

        //serialize without login name or password (pub)
        var newdata = $.param($.map(form.serializeArray(), function(v, i) {
            return (v.name == "cf-turnstile-response" || v.name == "emailaddress" || v.name == "password") ? null : v;
        }));

        if (newdata != formdata) {
            return 'You have unsaved changes.'
        }

    }

    function isFormEdited() {
        var newdata = $.param($.map(form.serializeArray(), function(v, i) {
            return (v.name == "cf-turnstile-response" || v.name == "emailaddress" || v.name == "password") ? null : v;
        }));
        if (newdata != formdata) {
            return 'edited';
        }

    }

    $(".saveFirst").on('click', function(e) {
        var elem = $(this);
        if (isFormEdited()) {
            e.preventDefault();
            $('#modalSave').modal('show');
        } else {
            if (elem.attr('sel') == "btnPDF") {
                elem.addClass('btn-spinner')
            };
            window.location.replace(elem.attr('href'));
            setTimeout(function() {
                elem.removeClass('btn-spinner');
            }, 4000);
        }
    });

    $("#formPayment").submit(function(e) {

        //Bind functions to form submit (not button click!)
        e.preventDefault();
        $('#submitButton').val('Save');
        this.submit();

    });

    //Edited to allow more characters than other v3 modules
    $("#formPayment").find('textarea').on("input", function(e) {
        var pos = this.selectionStart;
        //replace ~`<>^*{}[]|\" with nothing
        var str = $(this).val().replace(/[\~\`\<\>\^\*{\}\[\]\|\\\"]/g, '').replace(/\.{2,}/g, '.');
        var len = str.length;
        $(this).val(str);
        this.selectionEnd = pos - (len - str.length);
    });

    $(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

})(jQuery);

