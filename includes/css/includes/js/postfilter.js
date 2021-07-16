function filterSelection(id) {
        if (id == 'all') {
          jQuery('.cb-postfilter-wrapper .cb-postfilter-gallery-card').show(0).removeClass('filterHide');
          jQuery('.cb-postfilter-btn-all').addClass('cb-postfilter-btn-active');
          jQuery('.cb-postfilter-btn').not('.cb-postfilter-btn-all').removeClass('cb-postfilter-btn-active');

        }
        else {
          jQuery('.cb-postfilter-wrapper .cb-postfilter-gallery-card').not('.' + id).addClass('filterHide').delay(600).hide(0);
          jQuery('.cb-postfilter-btn').not('.cb-postfilter-btn-' + id).removeClass('cb-postfilter-btn-active');
          jQuery('.cb-postfilter-wrapper .cb-postfilter-gallery-card.' + id).show(0).removeClass('filterHide');
          jQuery('.cb-postfilter-btn-' + id).addClass('cb-postfilter-btn-active');
        }
}
