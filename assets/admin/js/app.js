'use strict';

/* ================= SlimScroll (SAFE) ================= */
$(function () {
  if ($.fn.slimScroll) {
    $('#sidebar__menuWrapper').slimScroll({
      height: 'calc(100vh - 86.75px)',
      railVisible: true,
      alwaysVisible: true
    });

    $('.dropdown-menu__body').slimScroll({ height: '270px' });
    $('.modal-dialog-scrollable .modal-body').slimScroll({ height: '100%' });
    $('.activity-list').slimScroll({ height: '385px' });
    $('.recent-ticket-list__body').slimScroll({ height: '295px' });
  }
});

/* ================= Sidebar Search ================= */
$('.navbar-search-field').on('input', function () {
  var search = $(this).val().toLowerCase();
  var pane = $('.search-list').html('');

  if (!search.length) {
    pane.addClass('d-none');
    return;
  }

  pane.removeClass('d-none');

  var match = $('.sidebar__menu-wrapper .nav-link').filter(function () {
    return $(this).text().toLowerCase().indexOf(search) >= 0;
  });

  if (!match.length) {
    pane.append('<li class="text-muted pl-5">No search result found.</li>');
    return;
  }

  match.each(function () {
    var parent =
      $(this).parents('.sidebar-menu-item.sidebar-dropdown')
        .find('.menu-title').first().text() || 'Main Menu';

    var url = $(this).attr('href') || $(this).data('default-url');
    var text = $(this).text().replace(/\d+/g, '').trim();

    pane.append(`
      <li>
        <small class="d-block">${parent}</small>
        <a href="${url}" class="fw-bold text-color--3 d-block">${text}</a>
      </li>
    `);
  });
});

/* ================= Sidebar Toggle ================= */
$('.res-sidebar-open-btn').on('click', () => $('.sidebar').addClass('open'));
$('.res-sidebar-close-btn').on('click', () => $('.sidebar').removeClass('open'));

$('.sidebar-dropdown > a').on('click', function () {
  var sub = $(this).parent().find('.sidebar-submenu').first();
  if (!sub.length) return;

  $(this).toggleClass('side-menu--open');
  $(this).find('.side-menu__sub-icon').toggleClass('transform rotate-180');

  sub.slideToggle(() => sub.toggleClass('sidebar-submenu__open'));
});

/* ================= Select2 (SAFE) ================= */
if ($.fn.select2) {
  $('.select2-basic,.select2-multi-select').select2();
  $('.select2-auto-tokenize').select2({ tags: true, tokenSeparators: [','] });
}

/* ================= Profile Picture ================= */
function proPicURL(input) {
  if (input.files && input.files[0]) {
    var r = new FileReader();
    r.onload = e => {
      var p = $(input).parents('.thumb').find('.profilePicPreview');
      p.css('background-image', 'url(' + e.target.result + ')')
        .addClass('has-image')
        .hide().fadeIn(650);
    };
    r.readAsDataURL(input.files[0]);
  }
}

$('.profilePicUpload').on('change', function () { proPicURL(this); });
$('.remove-image').on('click', function () {
  $(this).parents('.profilePicPreview')
    .css('background-image', 'none')
    .removeClass('has-image')
    .parents('.thumb').find('input[type=file]').val('');
});

/* ================= Labels ================= */
$('input,select,textarea').each(function () {
  if (!this.id && this.type !== 'hidden') {
    this.id = this.name;
    $(this).closest('.form-group').find('label').attr('for', this.name);
  }
  if (this.required) {
    $(this).closest('.form-group').find('label').addClass('required');
  }
});

/* ================= Tooltips (SAFE) ================= */
if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
  document.querySelectorAll('[title],[data-title],[data-bs-title]')
    .forEach(el => new bootstrap.Tooltip(el));
}

/* ================= Table Search ================= */
var tr = $('.custom-data-table tbody tr');
$(document).on('input', 'input[name=search_table]', function () {
  var s = this.value.toUpperCase();
  var m = tr.filter(function () {
    return $(this).text().toUpperCase().indexOf(s) >= 0;
  });

  var body = $('.custom-data-table tbody');
  body.html(m.length ? m : '<tr><td colspan="100%" class="text-center">Data Not Found</td></tr>');
});

/* ================= Copy Shortcodes ================= */
$(document).on('click', '.short-codes', function () {
  navigator.clipboard.writeText($(this).text());
  $(this).addClass('copied');
  setTimeout(() => $(this).removeClass('copied'), 1000);
});

/* ================= Responsive Tables ================= */
document.querySelectorAll('table').forEach(table => {
  let h = table.querySelectorAll('thead th');
  table.querySelectorAll('tbody tr').forEach(r => {
    r.querySelectorAll('td').forEach((c, i) => {
      c.setAttribute('data-label', h[i]?.innerText || '');
    });
  });
});
