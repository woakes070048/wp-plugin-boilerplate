(function ($) {
  'use strict';

  $(document).on('click', '.wppb-media-select', function (e) {
    e.preventDefault();

    const btn   = $(this);
    const wrap  = btn.closest('.wppb-media-field');
    const input = wrap.find('input[type="hidden"]');

    const fieldType = btn.data('field'); // image | audio | video | document | archive | media | file
    const mimes     = btn.data('mimes'); // array of allowed MIME types

    const frame = wp.media({
      title: 'Select file',
      button: { text: 'Use this file' },
      multiple: false,
      library: (() => {
        // Only native, stable filters
        if (fieldType === 'image') return { type: 'image' };
        if (fieldType === 'audio') return { type: 'audio' };
        if (fieldType === 'video') return { type: 'video' };
        return {}; // document / archive / media / file
      })()
    });

    frame.on('select', function () {
      const selection = frame.state().get('selection');

      if (!selection || !selection.first()) {
        return;
      }

      const attachment = selection.first().toJSON();

      // Hard MIME enforcement (authoritative)
      if (Array.isArray(mimes) && !mimes.includes(attachment.mime)) {
        alert('This file type is not allowed.');
        return;
      }

      input.val(attachment.id);

      const preview = wrap.find('.wppb-media-preview');
      preview.empty();

      // Preview rules
      if (
        fieldType === 'image' ||
        (fieldType === 'media' && attachment.type === 'image')
      ) {
        if (attachment.sizes && attachment.sizes.thumbnail) {
          preview.append(`<img src="${attachment.sizes.thumbnail.url}" />`);
        }
      } else {
        preview.append(`<code>${attachment.filename}</code>`);
      }

      wrap.find('.wppb-media-remove').prop('disabled', false);
    });

    frame.open();
  });

  $(document).on('click', '.wppb-media-remove', function (e) {
    e.preventDefault();

    const wrap = $(this).closest('.wppb-media-field');
    wrap.find('input[type="hidden"]').val('');
    wrap.find('.wppb-media-preview').empty();
    $(this).prop('disabled', true);
  });

})(jQuery);
