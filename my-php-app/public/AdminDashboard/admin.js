(function () {
    var notice = document.getElementById('notice');
    var adminNameInput = document.getElementById('admin_name');
    var userList = document.getElementById('userList');
    var auditLog = document.getElementById('auditLog');

    function safeText(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showNotice(message) {
        notice.textContent = message;
        notice.hidden = false;

        window.clearTimeout(showNotice.timer);
        showNotice.timer = window.setTimeout(function () {
            notice.hidden = true;
        }, 2500);
    }

    function currentAdmin() {
        return adminNameInput.value.trim() || 'System Admin';
    }

    function formatDateTime() {
        var now = new Date();
        var year = now.getFullYear();
        var month = String(now.getMonth() + 1).padStart(2, '0');
        var day = String(now.getDate()).padStart(2, '0');
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');
        return month + '/' + day + '/' + year + ', ' + hours + ':' + minutes + ':' + seconds;
    }

    function addAudit(activity) {
        var row = document.createElement('tr');
        var dateCell = document.createElement('td');
        var adminCell = document.createElement('td');
        var activityCell = document.createElement('td');

        dateCell.textContent = formatDateTime();
        adminCell.textContent = currentAdmin();
        activityCell.textContent = activity;

        row.appendChild(dateCell);
        row.appendChild(adminCell);
        row.appendChild(activityCell);
        auditLog.insertBefore(row, auditLog.firstChild);
    }

    function roleOptions(selectedRole) {
        return ['Super Admin', 'Inventory Manager', 'Reports Viewer'].map(function (role) {
            return '<option' + (role === selectedRole ? ' selected' : '') + '>' + role + '</option>';
        }).join('');
    }

    function statusOptions(selectedStatus) {
        return ['Active', 'Inactive'].map(function (status) {
            return '<option' + (status === selectedStatus ? ' selected' : '') + '>' + status + '</option>';
        }).join('');
    }

    function makeUserRow(user) {
        var row = document.createElement('form');
        row.className = 'edit-row user-row';
        row.innerHTML =
            '<label><span>Name</span><input name="name" value="' + safeText(user.name) + '" required></label>' +
            '<label><span>Email</span><input type="email" name="email" value="' + safeText(user.email) + '" required></label>' +
            '<label><span>Role</span><select name="role">' + roleOptions(user.role) + '</select></label>' +
            '<label><span>Status</span><select name="status">' + statusOptions(user.status) + '</select></label>' +
            '<div class="row-actions"><button type="submit" class="ghost-button">Update</button><button type="button" class="danger-button delete-user">Delete</button></div>';
        return row;
    }

    document.getElementById('adminForm').addEventListener('submit', function (event) {
        event.preventDefault();
        addAudit('Changed admin display name');
        showNotice('Admin name updated.');
    });

    document.getElementById('addUserForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var form = event.currentTarget;
        var user = {
            name: form.elements.name.value.trim(),
            email: form.elements.email.value.trim(),
            role: form.elements.role.value,
            status: form.elements.status.value
        };

        if (!user.name || !user.email) {
            showNotice('Please complete the admin user fields.');
            return;
        }

        userList.appendChild(makeUserRow(user));
        addAudit('Added admin user: ' + user.name);
        showNotice('Admin user added.');
        form.reset();
    });

    userList.addEventListener('submit', function (event) {
        if (!event.target.classList.contains('user-row')) {
            return;
        }

        event.preventDefault();
        addAudit('Updated admin user: ' + event.target.elements.name.value.trim());
        showNotice('Admin user updated.');
    });

    userList.addEventListener('click', function (event) {
        if (!event.target.classList.contains('delete-user')) {
            return;
        }

        var row = event.target.closest('.user-row');
        var name = row.elements.name.value.trim() || 'Admin user';

        row.remove();
        addAudit('Deleted admin user: ' + name);
        showNotice('Admin user deleted.');
    });

    document.getElementById('clearAuditLog').addEventListener('click', function () {
        while (auditLog.firstChild) {
            auditLog.removeChild(auditLog.firstChild);
        }
        addAudit('Audit log cleared');
        showNotice('Audit log cleared.');
    });

    // btw, here is where i coded show/hide sections when clicking nav
    function showSection(id) {
        var allSections = document.querySelectorAll('.page-section');
        
        // for loop hide all sections f
        for (var i = 0; i < allSections.length; i++) {
            allSections[i].classList.remove('active');
        }
        
        // show selected section
        var targetSection = document.getElementById(id);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }

    // setup nav click handlers
    var sideNavLinks = document.querySelectorAll('.side-nav a');
    var topNavLinks = document.querySelectorAll('.top-nav a');
    
    // side nav links
    for (var k = 0; k < sideNavLinks.length; k++) {
        sideNavLinks[k].addEventListener('click', function (e) {
            var link = this.getAttribute('href');
            if (link && link.charAt(0) === '#') {
                e.preventDefault();
                var sectionName = link.substring(1);
                showSection(sectionName);
            }
        });
    }
    
    //for da top nav links
    for (var m = 0; m < topNavLinks.length; m++) {
        topNavLinks[m].addEventListener('click', function (e) {
            var link = this.getAttribute('href');
            if (link && link.charAt(0) === '#') {
                e.preventDefault();
                var sectionName = link.substring(1);
                showSection(sectionName);
            }
        });
    }

    // load dashboard when page opens
    showSection('dashboard');
}());
