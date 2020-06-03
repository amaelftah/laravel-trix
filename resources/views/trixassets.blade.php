<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.js" defer></script>

<script>

addEventListener("trix-file-accept", function(event) {
    var config = laravelTrixConfig(event);

    if(
        config.hideToolbar ||
        (config.hideTools && config.hideTools.indexOf("file-tools") != -1) ||
        (config.hideButtonIcons && config.hideButtonIcons.indexOf("attach") != -1)
    ) {
        return event.preventDefault();
    }
});

addEventListener("trix-attachment-remove", function(event) {
    var config = laravelTrixConfig(event);

    var xhr = new XMLHttpRequest();

    var attachment = event.attachment.attachment.attributes.values.url.split("/").pop();

    xhr.open("DELETE", "{{route('laravel-trix.destroy',['attachment' => ':attachment'])}}".replace(':attachment',attachment), true);

    setAttachementUrlCollectorValue('attachment-' + config['id'], function(collector){
        for( var i = 0; i < collector.length; i++){
            if ( collector[i] === attachment) {
                collector.splice(i, 1);
            }
        }

        return collector;
    });

    xhr.send();
});

addEventListener("trix-attachment-add", function(event) {
    var config = laravelTrixConfig(event);

    if (event.attachment.file) {
        var attachment = event.attachment;

        config['attachment'] = attachment;

         uploadFile(config, setProgress, setAttributes, errorCallback);

        function setProgress(progress) {
            attachment.setUploadProgress(progress);
        }

        function setAttributes(attributes) {
            attachment.setAttributes(attributes);
        }

        function errorCallback(xhr,attachment){
            attachment.remove();
            alert(xhr.statusText);
        }
    }
});


function uploadFile(data, progressCallback, successCallback, errorCallback) {
    var formData = createFormData(data);
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "{{route('laravel-trix.store')}}", true);

    xhr.upload.addEventListener("progress", function(event) {
        var progress = (event.loaded / event.total) * 100;
        progressCallback(progress);
    });

    xhr.addEventListener("load", function(event) {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.response);

            setAttachementUrlCollectorValue('attachment-' + data['id'], function(collector){
                collector.push(response.url.split("/").pop())

                return collector;
            });

            successCallback({
                url : response.url,
                href: response.url
            })
        } else {
            errorCallback(xhr,data.attachment)
        }
    });

    xhr.send(formData);
}

function setAttachementUrlCollectorValue(inputId, callback){
    var attachmentCollector = document.getElementById(inputId);

    attachmentCollector.value = JSON.stringify(callback(JSON.parse(attachmentCollector.value)));
}

function createFormData(data) {
    var formData = new FormData();
    formData.append("Content-Type", data.attachment.file.type);
    formData.append("file", data.attachment.file);
    formData.append("field", data.field);
    formData.append("modelClass", data.modelClass);

    if(data.disk != undefined) {
        formData.append("disk", data.disk);
    }

    return formData;
}

function laravelTrixConfig (event) {
    return JSON.parse(event.target.getAttribute("data-config"));
}

window.onload = function() {
    var laravelTrixInstanceStyles =  document.getElementsByTagName('laravel-trix-instance-style');

    var style = document.createElement('style');
        style.type = 'text/css';

    for (var tag of laravelTrixInstanceStyles) {
        style.innerHTML += tag.textContent + ' ';
    }

    document.getElementsByTagName('head')[0].appendChild(style);
}

</script>
