(function () {
  'use strict';

  window.HandleFileSelect = function (input, submitId, labelId) {
    var submitBtn = document.getElementById(submitId);
    var label = document.getElementById(labelId);

    if (!submitBtn || !label) {
      return;
    }

    if (!input.files || !input.files.length) {
      label.textContent = 'No file selected';
      submitBtn.disabled = true;
      return;
    }

    label.textContent = input.files[0].name;
    submitBtn.disabled = false;
  };
})();
