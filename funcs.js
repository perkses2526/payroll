let timeout;

$(document).ready(function () {
    setInterval(() => {
        $('select.selectize').each(function (i, v) {
            $(v).removeClass('selectize');
            $(v).selectize({
                /* create: true, */ // Allow creating new options
                sortField: 'text', // Sort options alphabetically
                placeholder: 'Select ' + $('label[for="' + $(v).attr('id') + '"]').text(),
                onType: function (str) {
                    // Convert the options to an array
                    var validOptionsArray = Object.values(this.options);
                    // Check if the entered text does not match any existing options
                    var isNoDataFound = !validOptionsArray.some(function (option) {
                        return option.text.toLowerCase().includes(str.toLowerCase());
                    });
                    // Get the dropdown content element
                    var dropdownContent = this.$dropdown_content[0];
                    drop = $(dropdownContent).closest('div.selectize-dropdown');
                    // Remove existing options from the dropdown content
                    if (isNoDataFound) {
                        var noDataOption = $('<div class="option" data-value="">No Data Found</div>');
                        $(dropdownContent).append(noDataOption);
                        $(drop).show();
                    }
                }
            });
            if ($(v).hasClass('sf')) {
                selectize = $(v)[0].selectize;
                var foption = Object.values(selectize.options)[0];
                selectize.setValue(foption.value);
            }
        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            $(tooltipTriggerEl).removeAttr('data-bs-toggle');
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }, 500);

    $('.modal-footer button, .modal-header button').click(function () {
        closeModal();
    });
});

function getValue(data, name) {
    const item = data.find(item => item.name === name);
    return item ? item.value : '';
}

function settbsort() {
    $('th').click(function () {
        sortTable($(this));
    });
}

function sortTable(th) {
    var columnIndex = th.index();
    var table = th.closest('table');
    var rows = table.find("tbody > tr").toArray(); // Convert to an array for faster sorting
    var isAscending = th.hasClass("asc");

    table.find("th").removeClass("asc desc");

    if (isAscending) {
        th.addClass("desc");
    } else {
        th.addClass("asc");
    }

    rows.sort(function (a, b) {
        var x = a.cells[columnIndex].textContent.toLowerCase();
        var y = b.cells[columnIndex].textContent.toLowerCase();
        return isAscending ? (x.localeCompare(y)) : (y.localeCompare(x));
    });

    // Append sorted rows back to the table
    $.each(rows, function (index, row) {
        table.children('tbody').append(row);
    });
}

function question(title, text) {
    return new Promise((resolve) => {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(true);
            } else {
                resolve(false);
            }
        });
    });
}

function setselect(sel, res) {
    setTimeout(() => {
        var optionobj = [];
        var val = '';
        $(res).each(function (i, v) {
            var isSelected = $(v).prop("selected");
            var isDisabled = $(v).prop("disabled");
            val = isSelected && !isDisabled ? $(v).val() : val;
            optionobj.push({
                value: $(v).val(),
                text: $(v).text(),
                disabled: isDisabled // Set the disabled property for the option
            });
        });
        $(sel)[0].selectize.clearOptions();
        $(sel)[0].selectize.addOption(optionobj);
        $(sel)[0].selectize.setValue(val);
    }, 500);
}

function setDocTitle(titles) {
    document.title = titles;
}

function tsuccess(texts, sec = 3) {
    toast('success', 'Successful', texts, sec);
}

function terror(texts, sec = 3) {
    toast('error', 'Error', (texts === "" ? `There's something wrong, please try again.` : texts), sec);
}

function twarning(texts, sec = 3) {
    toast('warning', 'Warning', texts, sec);
}

function ttimer(titles, sec = 3) {
    let timerInterval
    Swal.fire({
        title: titles,
        timer: (sec * 1000),
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    });
}

function toast(icons, titles, texts, sec) {
    Swal.fire({
        icon: icons,
        title: titles,
        html: texts,
        timer: (sec * 1000),
        showConfirmButton: false,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })
}

function getFileNameFromURL(url) {
    var segments = url.split('/');
    var filename = segments[segments.length - 1];
    return filename;
}

function reqfunctwtoast(elem, texts, sec) {
    reqfunc(elem)
    twarning(texts, sec);
}


function htmltb_to_excel(tbid) {
    var name = $("#tbtitle").html();
    name = ((name == "" || name == undefined) ? tbid : name);
    var data = document.getElementById(tbid).cloneNode(true);
    elems = $(data).find('.hideelem');
    links = $(data).find('a');
    var head = $(data).find('thead');
    $.each(links, function (i, v) {
        var l = $(v).attr('href');
        var p = $(v).parent();
        $(p).html(l);
    });
    $.each(head, function (i, v) {
        var thcnt = $(v).contents();
        $(v).replaceWith(thcnt);
    });
    var bdy = $(data).find('tbody');
    $.each(bdy, function (i, v) {
        var bdycnt = $(v).contents();
        $(v).replaceWith(bdycnt);
    });

    $(elems).remove();
    var file = XLSX.utils.table_to_book(data, {
        sheet: "Schedule",
        raw: false
    });

    XLSX.write(file, {
        bookType: 'xlsx',
        bookSST: true,
        type: 'base64'
    });

    XLSX.writeFile(file, name + '.xlsx');

}

function printDiv(id) {
    var divToPrint = document.getElementById(id).innerHTML;

    var newWin = window.open('', 'Print-Window');

    newWin.document.open();

    newWin.document.write(`<html>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
    @media print {
        .pagebreak { page-break-before: always; } /* page-break-after works, as well */
    }
    </style>

    <body onload="window.print();" class="bg-white m-3">` + divToPrint + `</body>
    <script src="../jq.js">
    </script>
    <script>
        $(".hideelem").each(function(i,v){
            $(v).hide();
        });
        setTimeout(() => {
            window.close();
        }, 500);
    </script>
    </html>`);

    newWin.document.close();

}

function checkall(elem) {
    var isChecked = $(elem).prop('checked');
    var tb = $(elem).closest('table');
    tb.find('tbody input[type="checkbox"]').prop('checked', isChecked);
}


function setCheckbox(element) {
    if ($(element).is(':checkbox')) {
        $(element).prop('checked', !$(element).prop('checked'));
    } else if ($(element).is('tr')) {
        var checkbox = $(element).find('input:checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
    } else if ($(element).is('td')) {
        element = $(element).closest('tr');
        var checkbox = $(element).find('input:checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
    }
}

function validator(elems) {
    var isValid = true;
    // Loop through all required inputs and selects
    $(elems).each(function () {
        // Check if the element is an input/select/textarea
        if ($(this).is('input, select, textarea')) {
            var inputValue = $(this).val();
            if (Array.isArray(inputValue)) {
                // If the value is an array, check its length
                if (inputValue.length === 0) {
                    elemid = $(this).attr('id');
                    txt = $('label[for="' + elemid + '"]').text();
                    //terror(txt + ' Is Required', 3);
                    reqfunc($(this));
                    isValid = false;
                    return false; // Break out of loop if any required input is empty
                }
            } else if (typeof inputValue === 'string' && inputValue.trim() === '') {
                // Check if input value is empty or whitespace
                elemid = $(this).attr('id');
                txt = $('label[for="' + elemid + '"]').text();
                //terror(txt + ' Is Required', 3);
                reqfunc($(this));
                isValid = false;
                return false; // Break out of loop if any required input is empty
            }
            // Check if input value length is within minimum and maximum length
            var minLength = $(this).attr("minlength");
            var maxLength = $(this).attr("maxlength");
            if (minLength && inputValue.length < minLength) {
                elemid = $(this).attr('id');
                txt = $('label[for="' + elemid + '"]').text();
                //terror(txt + " must be at least " + minLength + " characters long.", 3);
                reqfunc($(this));
                isValid = false;
                return false; // Break out of loop if any input length is less than minimum
            }
            if (maxLength && inputValue.length > maxLength) {
                elemid = $(this).attr('id');
                txt = $('label[for="' + elemid + '"]').text();
                //terror(txt + " must be more than " + maxLength + " characters long.", 3);
                reqfunc($(this));
                isValid = false;
                return false; // Break out of loop if any input length is more than maximum
            }

            // Check if input value is within the specified minimum and maximum values (for input type="number")
            if ($(this).attr("type") === "number") {
                var minValue = $(this).attr("min");
                var maxValue = $(this).attr("max");
                var value = parseFloat(inputValue);

                if (minValue && value < parseFloat(minValue)) {
                    elemid = $(this).attr('id');
                    txt = $('label[for="' + elemid + '"]').text();
                    // terror(txt + " must be at least " + minValue + ".", 3);
                    reqfunc($(this));
                    isValid = false;
                    return false; // Break out of loop if any input value is less than the minimum
                }

                if (maxValue && value > parseFloat(maxValue)) {
                    elemid = $(this).attr('id');
                    txt = $('label[for="' + elemid + '"]').text();
                    //terror(txt + " must be less than or equal to " + maxValue + ".", 3);
                    reqfunc($(this));
                    isValid = false;
                    return false; // Break out of loop if any input value is greater than the maximum
                }
            }
        }
    });
    return isValid;
}

/* function reqfunc(elem) {
    dp = '';
    if (elem.hasClass("selectized")) {
        $(elem).closest('div').find('div.selectize-input').addClass('border border-danger');
        setTimeout(() => {
            $(elem).closest('div').find('div.selectize-input').removeClass('border border-danger');
        }, 3000);
    } else {
        $(elem).addClass('bg-danger bg-opacity-50');
        setTimeout(() => {
            $(elem).removeClass('bg-danger bg-opacity-50');
        }, 3000);
    }
} */

function reqfunc(elem) {
    let $input = $(elem).closest('div').find('div.selectize-input');
    if (elem.hasClass("selectized")) {
        $input.addClass('border border-danger');
        setTimeout(() => {
            $input.removeClass('border border-danger');
        }, 3000);
    } else {
        $(elem).addClass('bg-danger bg-opacity-50');
        setTimeout(() => {
            $(elem).removeClass('bg-danger bg-opacity-50');
        }, 3000);
    }
}


function loadingsm(msg = "") {
    return `<span class=" fs-6">` + msg + `</span><div class="spinner-border spinner-border-sm text-success" id="l" role="status"></div>`;
}

async function myajax(url, formData) {
    try {
        const response = await $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });
        return response.trim();
    } catch (error) {
        console.log('Error: ' + error.statusText);
        return "";
    }
}

function format12hr(timeStr) {
    const time = new Date("2000-01-01 " + timeStr);
    return time.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
}

function addslashes(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
}

function formatDateToYMD(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Adding 1 to month because months are zero-based
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function getDayNameFromDate(date) {
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    return daysOfWeek[date.getDay()];
}

function modallg(title, body) {
    setViewModal(title, 'lg', body);
}

function modalxl(title, body) {
    setViewModal(title, 'xl', body);
}

function modalmd(title, body) {
    setViewModal(title, 'md', body);
}

function setViewModal(title, size, body) {
    $('#modalDialog').removeClass().addClass("modal-dialog modal-" + size);
    $('#modalTitle').html(title);
    $('#modalBody').html(body);
    $('#modal').modal('show');
}

function closeModal() {
    $('#modal').modal('hide');
}