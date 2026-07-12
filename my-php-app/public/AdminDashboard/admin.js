(function () {
  var API = "../api.php";
  var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  var notice = document.getElementById("notice");
  var adminNameInput = document.getElementById("admin_name");
  var userList = document.getElementById("userList");
  var newUserRoleSelect = document.getElementById("newUserRole");
  var stockList = document.getElementById("stockList");
  var reportBody = document.getElementById("reportBody");
  var auditLog = document.getElementById("auditLog");
  var applicationBody = document.getElementById("applicationBody");

  var roles = []; // cached from GET ?resource=roles

  function safeText(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function showNotice(message, persistent) {
    notice.textContent = message;
    notice.hidden = false;
    window.clearTimeout(showNotice.timer);
    if (!persistent) {
      showNotice.timer = window.setTimeout(function () {
        notice.hidden = true;
      }, 2500);
    }
  }

  function titleCase(value) {
    value = String(value || "");
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
  }

  // ---- generic API helper -------------------------------------------------
  function api(resource, options) {
    options = options || {};
    var url =
      API +
      "?resource=" +
      encodeURIComponent(resource) +
      (options.id ? "&id=" + encodeURIComponent(options.id) : "");

    var fetchOptions = {
      method: options.method || "GET",
      headers: { "X-CSRF-Token": csrfToken },
    };
    if (options.body) {
      fetchOptions.headers["Content-Type"] = "application/json";
      fetchOptions.body = JSON.stringify(options.body);
    }

    return fetch(url, fetchOptions).then(function (res) {
      if (res.status === 401) {
        window.location = "../login.php";
        return Promise.reject("Not authenticated");
      }
      return res.json().then(function (data) {
        if (!res.ok) {
          return Promise.reject(data.error || "Request failed");
        }
        return data;
      });
    });
  }

  // ---- dashboard summary ---------------------------------------------------
  function loadSummary() {
    api("summary")
      .then(function (data) {
        document.getElementById("totalProducts").textContent =
          data.total_products;
        document.getElementById("totalStock").textContent = data.total_stock;
        document.getElementById("lowStock").textContent = data.low_stock;
        document.getElementById("inventoryValue").textContent =
          "PHP " + Number(data.inventory_value).toFixed(2);
      })
      .catch(function () {
        /* dashboard summary is non-critical */
      });
  }

  // ---- admin users -----------------------------------------------------
  function roleOptionsHtml(selectedRoleName) {
    return roles
      .map(function (role) {
        var selected = role.role_name === selectedRoleName ? " selected" : "";
        return (
          "<option" + selected + ">" + safeText(role.role_name) + "</option>"
        );
      })
      .join("");
  }

  function statusOptionsHtml(selectedStatus) {
    return ["Active", "Inactive"]
      .map(function (status) {
        return (
          "<option" +
          (status === selectedStatus ? " selected" : "") +
          ">" +
          status +
          "</option>"
        );
      })
      .join("");
  }

  function makeUserRow(user) {
    var row = document.createElement("form");
    row.className = "edit-row user-row";
    row.dataset.userId = user.user_id;
    var fullName = (user.first_name + " " + user.last_name).trim();
    row.innerHTML =
      '<label><span>Name</span><input name="name" value="' +
      safeText(fullName) +
      '" required></label>' +
      '<label><span>Email</span><input type="email" name="email" value="' +
      safeText(user.email) +
      '" required></label>' +
      '<label><span>Role</span><select name="role">' +
      roleOptionsHtml(user.role_name) +
      "</select></label>" +
      '<label><span>Status</span><select name="status">' +
      statusOptionsHtml(titleCase(user.status)) +
      "</select></label>" +
      '<div class="row-actions"><button type="submit" class="ghost-button">Update</button><button type="button" class="danger-button delete-user">Delete</button></div>';
    return row;
  }

  function loadRoles() {
    return api("roles").then(function (data) {
      roles = data;
      if (newUserRoleSelect) {
        newUserRoleSelect.innerHTML = roleOptionsHtml(null);
      }
    });
  }

  function loadUsers() {
    api("users")
      .then(function (users) {
        userList.querySelectorAll(".user-row").forEach(function (row) {
          row.remove();
        });
        users.forEach(function (user) {
          userList.appendChild(makeUserRow(user));
        });
      })
      .catch(function (err) {
        showNotice("Could not load users: " + err);
      });
  }

  document
    .getElementById("addUserForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      var form = event.currentTarget;
      var payload = {
        name: form.elements.name.value.trim(),
        email: form.elements.email.value.trim(),
        role: form.elements.role.value,
        status: form.elements.status.value,
      };

      if (!payload.name || !payload.email) {
        showNotice("Please complete the admin user fields.");
        return;
      }

      api("users", { method: "POST", body: payload })
        .then(function (result) {
          var message = result.email_sent
            ? "User added. Account setup email sent."
            : "User added, but email was not sent (" +
              result.email_error +
              "). Setup link: " +
              result.setup_url;
          showNotice(message, !result.email_sent);
          form.reset();
          loadUsers();
          loadAudit();
        })
        .catch(function (err) {
          showNotice("Could not add user: " + err);
        });
    });

  userList.addEventListener("submit", function (event) {
    if (!event.target.classList.contains("user-row")) return;
    event.preventDefault();

    var row = event.target;
    var payload = {
      name: row.elements.name.value.trim(),
      email: row.elements.email.value.trim(),
      role: row.elements.role.value,
      status: row.elements.status.value,
    };

    api("users", { method: "PUT", id: row.dataset.userId, body: payload })
      .then(function () {
        showNotice("Admin user updated.");
        loadAudit();
      })
      .catch(function (err) {
        showNotice("Could not update user: " + err);
      });
  });

  userList.addEventListener("click", function (event) {
    if (!event.target.classList.contains("delete-user")) return;

    var row = event.target.closest(".user-row");
    api("users", { method: "DELETE", id: row.dataset.userId })
      .then(function () {
        row.remove();
        showNotice("Admin user deleted.");
        loadAudit();
      })
      .catch(function (err) {
        showNotice("Could not delete user: " + err);
      });
  });

  // ---- stocks ------------------------------------------------------------
  function makeStockRow(item) {
    var row = document.createElement("form");
    row.className = "edit-row stock-row";
    row.dataset.productId = item.product_id;
    row.innerHTML =
      '<label><span>Product</span><input name="name" value="' +
      safeText(item.product_name) +
      '" required></label>' +
      '<label><span>Category</span><input name="category" value="' +
      safeText(item.category_name) +
      '" required></label>' +
      '<label><span>Price</span><input type="number" name="price" min="0" step="0.01" value="' +
      Number(item.price).toFixed(2) +
      '" required></label>' +
      '<label><span>Stock</span><input type="number" name="stock" min="0" step="1" value="' +
      item.stock_quantity +
      '" required></label>' +
      '<div class="row-actions"><button type="submit" class="ghost-button">Update</button><button type="button" class="danger-button delete-stock">Delete</button></div>';
    return row;
  }

  function loadStock() {
    api("stock")
      .then(function (items) {
        stockList.innerHTML = "";
        items.forEach(function (item) {
          stockList.appendChild(makeStockRow(item));
        });
      })
      .catch(function (err) {
        showNotice("Could not load stock: " + err);
      });
  }

  var addStockForm = document.getElementById("addStockForm");
  if (addStockForm) {
    addStockForm.addEventListener("submit", function (event) {
      event.preventDefault();
      var form = event.currentTarget;
      var payload = {
        name: form.elements.name.value.trim(),
        category: form.elements.category.value,
        price: parseFloat(form.elements.price.value),
        stock: parseInt(form.elements.stock.value, 10),
      };

      api("stock", { method: "POST", body: payload })
        .then(function () {
          showNotice("Stock item added.");
          form.reset();
          loadStock();
          loadReport();
          loadSummary();
          loadAudit();
        })
        .catch(function (err) {
          showNotice("Could not add stock item: " + err);
        });
    });
  }

  if (stockList) {
    stockList.addEventListener("submit", function (event) {
      if (!event.target.classList.contains("stock-row")) return;
      event.preventDefault();
      var row = event.target;
      api("stock", {
        method: "PUT",
        id: row.dataset.productId,
        body: {
          name: row.elements.name.value.trim(),
          category: row.elements.category.value.trim(),
          price: Number(row.elements.price.value),
          stock: Number(row.elements.stock.value),
        },
      })
        .then(function () {
          showNotice("Stock item updated.");
          loadStock();
          loadReport();
          loadSummary();
          loadAudit();
        })
        .catch(function (err) {
          showNotice("Could not update stock item: " + err);
        });
    });
    stockList.addEventListener("click", function (event) {
      if (!event.target.classList.contains("delete-stock")) return;
      var row = event.target.closest("[data-product-id]");
      api("stock", { method: "DELETE", id: row.dataset.productId })
        .then(function () {
          row.remove();
          showNotice("Stock item deleted.");
          loadReport();
          loadSummary();
          loadAudit();
        })
        .catch(function (err) {
          showNotice("Could not delete stock item: " + err);
        });
    });
  }

  function loadApplications() {
    if (!applicationBody) return;
    api("applications")
      .then(function (rows) {
        applicationBody.innerHTML = rows
          .map(function (item) {
            var actions =
              item.status === "pending"
                ? '<button type="button" data-review="approved">Approve</button> <button type="button" class="danger-button" data-review="rejected">Reject</button>'
                : safeText(titleCase(item.status));
            return (
              '<tr data-application-id="' +
              item.application_id +
              '"><td>' +
              safeText(item.complete_name) +
              "</td><td>" +
              safeText(item.email) +
              "<br>" +
              safeText(item.phone || "") +
              "</td><td>" +
              safeText(item.requested_role) +
              "</td><td>" +
              safeText(
                item.reason || item.experience || item.availability || "—",
              ) +
              "</td><td>" +
              actions +
              "</td></tr>"
            );
          })
          .join("");
      })
      .catch(function (err) {
        showNotice("Could not load applications: " + err);
      });
  }
  if (applicationBody)
    applicationBody.addEventListener("click", function (event) {
      var status = event.target.dataset.review;
      if (!status) return;
      var row = event.target.closest("[data-application-id]");
      api("applications", {
        method: "PUT",
        id: row.dataset.applicationId,
        body: { status: status },
      })
        .then(function () {
          showNotice("Application " + status + ".");
          loadApplications();
          loadAudit();
        })
        .catch(function (err) {
          showNotice("Could not review application: " + err);
        });
    });

  // ---- reports -------------------------------------------------------------
  function loadReport() {
    if (!reportBody) return;
    api("report")
      .then(function (rows) {
        reportBody.innerHTML = rows
          .map(function (r) {
            return (
              "<tr><td>" +
              safeText(r.product_name) +
              "</td><td>" +
              safeText(r.category_name) +
              "</td><td>" +
              r.stock_quantity +
              "</td><td>PHP " +
              Number(r.price).toFixed(2) +
              "</td><td>PHP " +
              Number(r.total_value).toFixed(2) +
              "</td></tr>"
            );
          })
          .join("");
      })
      .catch(function (err) {
        showNotice("Could not load report: " + err);
      });
  }

  // ---- audit log -------------------------------------------------------------
  function loadAudit() {
    if (!auditLog) return;
    api("audit")
      .then(function (rows) {
        auditLog.innerHTML = rows
          .map(function (r) {
            return (
              "<tr><td>" +
              safeText(r.created_at) +
              "</td><td>" +
              safeText(r.actor) +
              "</td><td>" +
              safeText(r.details || r.action_name) +
              "</td></tr>"
            );
          })
          .join("");
      })
      .catch(function (err) {
        showNotice("Could not load audit log: " + err);
      });
  }

  // ---- section nav (unchanged) ------------------------------------------
  function showSection(id) {
    var allSections = document.querySelectorAll(".page-section");
    for (var i = 0; i < allSections.length; i++) {
      allSections[i].classList.remove("active");
    }
    var targetSection = document.getElementById(id);
    if (targetSection) targetSection.classList.add("active");
  }

  var sideNavLinks = document.querySelectorAll(".side-nav a");
  var topNavLinks = document.querySelectorAll(".top-nav a");

  function navHandler(e) {
    var link = this.getAttribute("href");
    if (link && link.charAt(0) === "#") {
      e.preventDefault();
      showSection(link.substring(1));
    }
  }

  for (var k = 0; k < sideNavLinks.length; k++)
    sideNavLinks[k].addEventListener("click", navHandler);
  for (var m = 0; m < topNavLinks.length; m++)
    topNavLinks[m].addEventListener("click", navHandler);

  // ---- initial load ------------------------------------------------------
  showSection("dashboard");
  loadSummary();
  loadRoles().then(loadUsers);
  loadStock();
  loadApplications();
  loadReport();
  loadAudit();
})();
