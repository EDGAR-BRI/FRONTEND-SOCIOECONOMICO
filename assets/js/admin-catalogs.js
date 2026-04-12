(function () {
  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }

  function show(el) {
    if (!el) return;
    el.classList.remove('hidden');
    el.setAttribute('aria-hidden', 'false');
  }

  function hide(el) {
    if (!el) return;
    el.classList.add('hidden');
    el.setAttribute('aria-hidden', 'true');
  }

  document.addEventListener('DOMContentLoaded', function () {
    var modal = qs('#catalog-item-modal');
    var btnNew = qs('#btn-new-catalog-item');
    var form = qs('#catalog-item-form');
    var title = qs('#catalog-item-modal-title');

    var inputResource = qs('#catalog-resource');
    var inputInstituto = qs('#catalog-instituto-id');

    var page = window.__CATALOGS_PAGE__ || {};
    var baseUrl = page.baseUrl || '';
    var fields = Array.isArray(page.fields) ? page.fields : [];

    function setCommonHidden() {
      if (inputResource && page.resource) inputResource.value = page.resource;
      if (inputInstituto) {
        // Para tenant-scoped el backend lo necesita; para el resto se ignora.
        inputInstituto.value = page.institutoId != null ? String(page.institutoId) : '';
      }
    }

    function openCreate() {
      if (!form) return;
      form.action = baseUrl + '/admin/catalogos/create';
      if (title) title.textContent = 'Nueva Opción';

      setCommonHidden();

      fields.forEach(function (f) {
        var input = qs('#catalog-' + f);
        if (!input) return;
        input.value = '';
      });

      show(modal);
      var first = fields.length ? qs('#catalog-' + fields[0]) : null;
      if (first) first.focus();
    }

    function openEdit(btn) {
      if (!form) return;
      var id = btn.getAttribute('data-id');
      form.action = baseUrl + '/admin/catalogos/update/' + encodeURIComponent(id);
      if (title) title.textContent = 'Editar Opción';

      setCommonHidden();

      fields.forEach(function (f) {
        var input = qs('#catalog-' + f);
        if (!input) return;
        input.value = btn.getAttribute('data-' + f) || '';
      });

      show(modal);
      var first = fields.length ? qs('#catalog-' + fields[0]) : null;
      if (first) first.focus();
    }

    function closeModal() {
      hide(modal);
    }

    if (btnNew) {
      btnNew.addEventListener('click', function () {
        openCreate();
      });
    }

    qsa('.js-edit-catalog-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        openEdit(btn);
      });
    });

    qsa('[data-modal-close]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        closeModal();
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });
  });
})();
