file = 'func.php';

$(document).ready(function () {
    setpages();

    $('.refresh-tb').click(function (e) {
        settb();
    });

});

async function settb() {
    form = $('#tb_form')[0];
    var fd = new FormData(form);
    fd.append('settb', '');
    var res = await myajax(file, fd);
    $('#company_tb').html(res);
}

async function setpages() {
    form = $('#tb_form')[0];
    var fd = new FormData(form);
    fd.append('setpages', '');
    var res = await myajax(file, fd);
    $('#page').html(res);
    settb();
}

async function addcompany(btn) {
    form = $(btn).closest('form')[0];
    elems = $(form).find('input:required');
    if (!validator(elems)) {
        return;
    }
    $(btn).prop('disabled', true);
    var fd = new FormData(form);
    fd.append('addcompany', '');
    var res = await myajax(file, fd);
    if (res === "success") {
        tsuccess(`Company added.`);
        closeModal();
    } else {
        $(btn).prop('disabled', false);
        terror();
    }
}

async function setcompanyform(btn) {
    var fd = new FormData();
    fd.append('setcompanyform', '');
    var res = await myajax(file, fd);
    modalmd(`Add new company`, res);
    $('#btn_submit').html(`<button type="submit" class="btn btn-primary btn-sm">Submit</button>`);
    $('#btn_submit').click(function (e) {
        addcompany($(this));
    });
}

