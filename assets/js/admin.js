jQuery(document).ready(function($) {
    // Tabs
    $('.ucb-tab-nav a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        $('.ucb-tab-nav a').removeClass('active');
        $(this).addClass('active');
        
        $('.ucb-tab-content').removeClass('active');
        $(target).addClass('active');
    });

    // Sortable
    $('#ucb-desktop-list').sortable({
        handle: '.handle',
        placeholder: 'ucb-sortable-placeholder',
        update: function(event, ui) {
            updateIndexes();
        }
    });

    // Add Desktop Item
    $('#ucb-add-desktop-item').on('click', function() {
        var index = $('#ucb-desktop-list li').length;
        var template = `
            <li class="ucb-list-item">
                <span class="dashicons dashicons-move handle"></span>
                <div class="ucb-item-fields">
                    <div class="ucb-field-row">
                        <select name="ucb_settings[desktop_buttons][${index}][type]">
                            <option value="phone">Phone</option>
                            <option value="zalo">Zalo</option>
                            <option value="messenger">Messenger</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="telegram">Telegram</option>
                            <option value="mail">Email</option>
                            <option value="custom">Custom</option>
                        </select>
                        <input type="text" name="ucb_settings[desktop_buttons][${index}][label]" value="" placeholder="Label" />
                        <input type="text" name="ucb_settings[desktop_buttons][${index}][link]" value="" placeholder="Link/ID" />
                    </div>
                    <div class="ucb-field-row ucb-upload-row">
                        <input type="text" class="ucb-img-url" name="ucb_settings[desktop_buttons][${index}][icon_url]" value="" placeholder="Custom SVG URL" />
                        <button type="button" class="button ucb-upload-btn">Upload SVG</button>
                    </div>
                    <div class="ucb-field-row ucb-svg-row">
                        <textarea name="ucb_settings[desktop_buttons][${index}][icon_svg]" placeholder="Or Paste Custom SVG Code here"></textarea>
                    </div>
                </div>
                <button type="button" class="ucb-remove-item button-link-delete">Remove</button>
            </li>
        `;
        $('#ucb-desktop-list').append(template);
    });

    // Remove Item
    $(document).on('click', '.ucb-remove-item', function() {
        $(this).closest('li').fadeOut(300, function() {
            $(this).remove();
            updateIndexes();
        });
    });

    function updateIndexes() {
        $('#ucb-desktop-list li').each(function(index) {
            $(this).find('select, input, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    // Media Uploader
    var file_frame;
    $(document).on('click', '.ucb-upload-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $input = $btn.prev('.ucb-img-url');

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select SVG Icon',
            button: {
                text: 'Use Icon',
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $input.val(attachment.url).trigger('change');
        });

        // Finally, open the modal
        file_frame.open();
    });
});
