function formatSize(bytes)
{
    var labels = new Array('TB', 'GB', 'MB', 'kB', 'b');
    var measurements = new Array(1099511627776, 1073741824, 1048576, 1024, 1);

    for (var i = 0; i < measurements.length; i++) {
        var conv = bytes / measurements[i];

        if (conv > 1) {
            return Math.round(conv * 10) / 10 + " " + labels[i];
        }
    }
}

function createAlert(type, message)
{
    var html = '<div class="alert alert-' + type + '">';
    html += '<button type="button" class="close" data-dismiss="alert">×</button>';
    html += message;
    html += '</div>';
    
    $("#main").prepend(html);
}

$(function() {
    window['files'] = 0;
    $("#files").hide();
    
    $("#dropzone").filedrop({
        fallback_id: "file",
        url: window['upload_url'],
        paramname: "file",
        maxfiles: window['max_files'],
        maxfilesize: window['max_size'],
        
        error: function(err, file) {
            switch (err) {
                case 'BrowserNotSupported':
                    createAlert("error", "Your browser does not support required HTML5 features.");
                    break;
                    
                case 'TooManyFiles':
                    createAlert("error", "You can upload a maximum of " + window['max_files'] + " files per time.")
                    break;
                    
                case 'FileTooLarge':
                    html = '<span class="label label-important">';
                    html += "Maximum file size is " + window['max_size'] + " MB.";
                    html += '</span>';
                    
                    $("#" + window[file.name] + "_progress").html(html);
                    break;
                    
                default:
                    createAlert("error", "An unexpected error occurred.");
                    break;
            }
        },
        
        beforeEach: function(file) {
            window[file.name] = ++window['files'];
            
            html = '<tr>';
            html += '<td>' + file.name + '</td>';
            html += '<td>' + formatSize(file.size) + '</td>';
            html += '<td id="' + window['files'] + '_speed">N/A</td>';
            html += '<td id="' + window['files'] + '_progress">';
            html += '<div class="bar" id="' + window['files'] + '_bar" style="width: 0%;"></div></div>';
            html += '</td>';
            html += '</tr>';
            
            $("#files tbody").append(html);
            $("#files").show();
        },
        
        progressUpdated: function(i, file, progress) {
            $("#" + window[file.name] + "_bar").width(progress + "%");
        },
        
        speedUpdated: function(i, file, speed) {
            $("#" + window[file.name] + "_speed").text(formatSize(speed) + "/s");
        },
        
        uploadFinished: function(i, file, response, time) {
            switch (response.type) {
                case 'success':
                    labelClass = 'label-success';
                    break;

                case 'error':
                    labelClass = 'label-important';
                    break;

                default:
                    labelClass = 'label-important';
            }
            
            html = '<span class="label ' + labelClass + '">';
            html += response.status;
            html += '</span>';
            
            $("#" + window[file.name] + "_progress").html(html);
        },
    });
});
