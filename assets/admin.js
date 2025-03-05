jQuery(document).ready(function ($) {

  // Rescan GitHub
  $('#matrix-ci-rescan').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      url: matrixCI.ajaxUrl,
      method: 'POST',
      data: {
        action: 'matrix_ci_rescan',
        nonce: matrixCI.nonce
      },
      success: function (response) {
        if (response.success) {
          alert(response.data.message);
          location.reload();
        } else {
          alert(response.data.message || 'Error rescanning');
        }
      },
      error: function () {
        alert('AJAX error');
      }
    });
  });

  // Vertical Tab switching
  $('.matrix-ci-tab-link').on('click', function () {
    $('.matrix-ci-tab-link').removeClass('active');
    $('.matrix-ci-tab-content').removeClass('active');

    $(this).addClass('active');
    const tabId = $(this).data('tab');
    $('#' + tabId).addClass('active');
  });

  // Import button
  $('.matrix-ci-import-btn').on('click', function (e) {
    e.preventDefault();

    const blockType = $(this).data('type');
    const folderName = $(this).data('folder');

    $.ajax({
      url: matrixCI.ajaxUrl,
      method: 'POST',
      data: {
        action: 'matrix_ci_import_component',
        nonce: matrixCI.nonce,
        blockType: blockType,
        folderName: folderName
      },
      success: function (response) {
        if (response.success) {
          alert(response.data.message);
        } else {
          alert(response.data.message || 'Error importing component');
        }
      },
      error: function () {
        alert('AJAX error');
      }
    });
  });

});
