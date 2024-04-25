file = 'func.php';

$(document).ready(function () {
    setpages();

    $('.refresh-tb').click(function (e) {
        setpages();
    });

    $('#entries').change(function (e) {
        setpages();
    });

    $('#search').keyup(function (e) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            setpages();
        }, 250);
    });

    $('#page').change(function (e) {
        settb();
    });
});

async function updatedept(btn) {
    $(btn).prop('disabled', true);
    row = $(btn).closest('div.row');
    department = $(row).find('input:text[name="department"]');
    did = $(row).find('input:hidden[name="did"]').val();

    if ($(department).val() === "") {
        reqfunc($(department));
        twarning('Please enter department');
        $(btn).prop('disabled', false);
        return;
    }
    if ($(department).val() === $(btn).attr('value')) {
        reqfunc($(department));
        twarning('No changes were made to department.');
        $(btn).prop('disabled', false);
        return;
    }

    var fd = new FormData();
    fd.append('department', $(department).val());
    fd.append('did', did);
    fd.append('updatedept', '');
    var res = await myajax(file, fd);
    if (res === "success") {
        deptpages();
        tsuccess(`Department updated.`);
        canceldept();
    } else {
        $(btn).prop('disabled', false);
        terror();
    }
}

async function editdepartment(btn) {
    department = $(btn).closest('tr').find('td:eq(1)').text();
    did = $(btn).attr('did');
    $(btn).closest('table').find('button').prop('disabled', true);
    $('#manage_dept_div').closest('div').html(
        `
        <div class="row">
            <div class="col">
                <input type="hidden" name="did" value="${did}">
                <input type = "text" id="department" value="${department}" name="department" class="form-control form-control-sm" placeholder="Add new department">
            </div>
            <div class="col-auto">
                <button type = "submit" value="${department}" class="btn btn-warning btn-sm" onclick="updatedept(this);"><i class="bi bi-save"></i></button>
                <button type = "button" class="btn btn-danger btn-sm" onclick="canceldept(this);"><i class="bi bi-x-square"></i></button>
            </div>
        </div>
        `
    );
    setTimeout(() => {
        reqfunc($('#manage_dept_div').find('input'));
    }, 150);
}

async function removedept(btn) {
    const confirmation = await question("Remove this department?", "Are you sure you want to delete it?");
    if (confirmation) {
        var fd = new FormData();
        fd.append('did', $(btn).attr('did'));
        fd.append('removedept', '');
        var res = await myajax(file, fd);
        if (res === "success") {
            tsuccess(`Department deleted.`);
            deptpages();
        } else {
            $(btn).prop('disabled', false);
            terror();
        }
    }
}

async function newdept(btn) {
    department = $(btn).closest('div.row').find('input');
    if ($(department).val() === "") {
        reqfunc($(department));
        twarning('Please enter department');
        return;
    }

    var fd = new FormData();
    fd.append('department', $(department).val());
    fd.append('cid', $('#cid').val());
    fd.append('newdept', '');
    var res = await myajax(file, fd);
    if (res === "success") {
        deptpages();
        tsuccess(`Department added.`);
        canceldept();
    } else {
        $(btn).prop('disabled', false);
        terror();
    }
}

async function canceldept(btn = "#manage_dept_div") {
    $(btn).closest('div.col-6').html(`<button class="btn btn-primary btn-sm" title="Add department to this company" onclick="setnewdept(this);"><i class="bi bi-building-add"></i></button>`);
    deptpages();
}

async function setnewdept(btn) {
    $(btn).closest('table').find('button').prop('disabled', true);
    $(btn).closest('div').html(
        `
        <div class="row">
            <div class="col">
                <input type = "text" id="department" name="department" class="form-control form-control-sm" placeholder="Add new department">
            </div>
            <div class="col-auto">
                <button type = "submit" class="btn btn-success btn-sm" onclick="newdept(this);"><i class="bi bi-save"></i></button>
                <button type = "button" class="btn btn-danger btn-sm" onclick="canceldept(this);"><i class="bi bi-x-square"></i></button>
            </div>
        </div>
        `
    );
}

async function dept_tb() {
    modal = $('#modal');
    form = modal.find('form');
    table = form.find('table');
    table.html(loadingsm());
    var fd = new FormData(form[0]);
    fd.append('dept_tb', '');
    var res = await myajax(file, fd);
    table.html(res);

    $('.refresh-tbdp').click(function (e) {
        deptpages();
    });

    $('#entries_dept').change(function (e) {
        deptpages();
    });

    $('#search_dept').keyup(function (e) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            deptpages();
        }, 250);
    });

    $('#page_dept').change(function (e) {
        dept_tb();
    });

}

async function deptpages() {
    modal = $('#modal');
    form = modal.find('form');
    page = $(form).find('select[name="page"]');
    var fd = new FormData(form[0]);
    fd.append('deptpages', '');
    var res = await myajax(file, fd);
    $(page).html(res);
    dept_tb();
}

async function managedepartment(btn) {
    var fd = new FormData();
    fd.append('cid', $(btn).attr('cid'));
    fd.append('managedepartment', '');
    var res = await myajax(file, fd);
    modalxl(`Manage company department - ${$(btn).closest('tr').find('td:eq(1)').text()}`, res);
    deptpages()
}

async function removecompany(btn) {
    const confirmation = await question("Remove this company?", "Are you sure you want to delete it?");
    if (confirmation) {
        var fd = new FormData();
        fd.append('cid', $(btn).attr('cid'));
        fd.append('removecompany', '');
        var res = await myajax(file, fd);
        if (res === "success") {
            setpages();
            tsuccess(`Company deleted.`);
        } else {
            $(btn).prop('disabled', false);
            terror();
        }
    }
}

async function updatecompany(btn) {
    form = $(btn).closest('form')[0];
    elems = $(form).find('input:required');
    if (!validator(elems)) {
        return;
    }
    $(btn).prop('disabled', true);
    var fd = new FormData(form);
    fd.append('updatecompany', '');
    var res = await myajax(file, fd);
    if (res === "success") {
        setpages();
        tsuccess(`Company updated.`);
        closeModal();
    } else {
        $(btn).prop('disabled', false);
        terror();
    }
}


async function editcompany(btn) {
    var fd = new FormData();
    fd.append('cid', $(btn).attr('cid'));
    fd.append('editcompany', '');
    var res = await myajax(file, fd);
    modalmd(`Edit company information`, res);
    $('#btn_submit').html(`<button type="submit" class="btn btn-warning btn-sm">Update company</button>`);
    btnnew = $('#btn_submit').find('button');
    $(btnnew).click(function (e) {
        updatecompany($(this));
    });
}

async function settb() {
    $('#company_tb').html(loadingsm());
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
        setpages();
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
    btnnew = $('#btn_submit').find('button');
    $(btnnew).click(function (e) {
        addcompany($(this));
    });
}

