// AJAX Form Submission
$('.chat-footer form').on('submit', async function(e) {
    e.preventDefault();
    
    let form = $(this);
    let actionUrl = form.attr('action');
    let submitBtn = form.find('.voice-note-btn');
    let formData = new FormData(this);
    
    // Ensure all selected files are appended correctly
    let fileInput = document.getElementById('hiddenRefFile');
    let files = fileInput.files;
    for (let i = 0; i < files.length; i++) {
        formData.append('attachments[]', files[i]);
    }
    
    let messageText = form.find('input[name="message"]').val();
    
    // 1. Generate Optimistic UI
    let tempId = 'temp_' + Date.now();
    let attachmentsHtml = '';
    
    if(files.length > 0) {
        attachmentsHtml += '<div class="mt-2 attachments-preview">';
        for(let i=0; i<files.length; i++) {
            let file = files[i];
            if(file.type.startsWith('image/')) {
                 let src = URL.createObjectURL(file);
                 attachmentsHtml += `<img src="${src}" class="preview-img">`;
            } else {
                 attachmentsHtml += `<div class="attachment-link"><span class="material-symbols-rounded" style="font-size: 20px;">description</span> ${file.name}</div>`;
            }
        }
        attachmentsHtml += '</div>';
    }
    
    let tempHtml = `
        <div class="message-row sent" id="${tempId}">
            <div class="message-bubble">
                <div class="message-text">
                    ${messageText}
                    ${attachmentsHtml}
                </div>
                <div class="message-meta">
                    <span class="time">Sending...</span>
                    <span class="material-symbols-rounded icon-status">schedule</span>
                </div>
                <div class="uploading-mask">
                    <div class="spinner"></div>
                    <div class="progress-text" style="color: white; font-size: 10px; margin-top: 2px; font-weight: bold;">0%</div>
                </div>
            </div>
        </div>
    `;
    
    $('#messagesArea').append(tempHtml);
    scrollToBottom();
    
    // Clear inputs
    form.find('input[name="message"]').val('');
    form.find('input[type="file"]').val('');
    document.getElementById('cameraInput').value = '';
    
    // Disable button
    submitBtn.prop('disabled', true);
    
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'Accept': 'application/json'
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                    $(`#${tempId} .progress-text`).text(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            if(response.status === 'success') {
                // Replace temp message with real HTML from server
                if(response.data.html) {
                    $(`#${tempId}`).replaceWith(response.data.html);
                } else {
                     $(`#${tempId} .uploading-mask`).remove();
                     $(`#${tempId} .icon-status`).text('done_all').addClass('read');
                     $(`#${tempId} .time`).text('Now');
                }
                scrollToBottom();
                
                // If it was a new ticket creation, redirect to ensure proper state/url
                if(response.data.redirect_url && !actionUrl.includes('reply')) {
                         window.location.href = response.data.redirect_url;
                }
                
            } else {
                $(`#${tempId} .uploading-mask`).remove();
                $(`#${tempId} .message-bubble`).css('border', '1px solid red');
                alert('Something went wrong. Please try again.');
            }
        },
        error: function(xhr) {
           $(`#${tempId} .uploading-mask`).remove();
           $(`#${tempId} .message-bubble`).css('border', '1px solid red');
           alert('Error sending message');
        },
        complete: function() {
            submitBtn.prop('disabled', false);
        }
    });
});
