file = 'profile-inc.php';
$(document).ready(function () {
    setDocTitle('My Profile');
    setProfile();
});

async function updatePass(btn) {
    form = $(btn).closest('form')[0];
    p = $('#password');
    cp = $('#cpassword');
    pval = $('#password').val();
    cpval = $('#cpassword').val();
    if (pval.length >= 8) {
        if (pval !== cpval) {
            reqfunc(p.add(cp));
            twarning(`Password should be the same`, 3);
            return;
        }
    } else {
        reqfunc(p);
        twarning(`Password should at be least 8 characters`, 3);
        return;
    }
    var updatePass = new FormData(form);
    updatePass.append('updatePass', '');
    var res = await myajax(file, updatePass);
    if (res === "1") {
        tsuccess(`Password successfully updated.`, 3);
        setProfile();
    } else {
        twarning(`No changes made.`, 3);
    }
}
async function editPass(btn) {
    $(btn).prop('disabled', true);
    row = $(btn).closest('.row');
    lbl = row.find('label');
    col = $(lbl).closest('.col');
    id = lbl.attr('for');
    txt = lbl.text();
    $(col).html(
        `
        <form class="row g-2" onsubmit="return false">
            <label for="p${id}">${txt}</label>
            <input type="password" value="" class="form-control form-control-sm" id="password" name="password" placeholder="Password" required>
            <input type="password" value="" class="form-control form-control-sm" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
            <button type="submit" class="btn btn-success btn-sm" onclick="updatePass(this)">Update Password</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="cancelChanges(this)">Cancel</button>
        </form>
        `
    );
}

async function submitChanges(btn) {
    form = $(btn).closest('form')[0];
    elems = $(form).find('input:required');
    if (!validator(elems)) {
        return;
    }
    row = $(btn).closest('.row');
    lbl = row.find('label');
    id = lbl.attr('for');
    var submitChanges = new FormData(form);
    submitChanges.append('id', id);
    submitChanges.append('submitChanges', '');
    var res = await myajax(file, submitChanges);
    if (res === "1") {
        tsuccess(`Successfully updated.`, 3);
        setProfile();
    } else {
        twarning(`No changes made.`, 3);
    }
}

function cancelChanges(btn) {
    setProfile();
}

async function editData(btn) {
    $(btn).prop('disabled', true);
    row = $(btn).closest('.row');
    lbl = row.find('label');
    id = lbl.attr('for');
    h6 = row.find('h6').html();
    txt = lbl.text();
    col = $(lbl).closest('.col');
    $(col).html(
        `
        <form onsubmit="return false">
            <label for="${id}">${txt}</label>
            <input type="text" value="${h6}" class="form-control form-control-sm" id="${id}" name="val" required>
            <button type="submit" class="btn btn-success btn-sm" onclick="submitChanges(this)">Submit Changes</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="cancelChanges(this)">Cancel</button>
        </form>
        `
    );
}

async function setProfile() {
    var setProfile = new FormData();
    setProfile.append('setProfile', '');
    var res = await myajax(file, setProfile);
    $('#body').html(res);
}