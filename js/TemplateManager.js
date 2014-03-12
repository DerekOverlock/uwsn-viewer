var app = app || {};

app.TemplateManager = (function() {
    var templateJson = "js/templates.json";
    var templateFiles = [];
    var templates = {};
    var callback;

    function addTemplate(id, html) {
        templates[id] = html;
    }

    function download() {
        function cb(jsonResponse) {
            templateFiles = jsonResponse;
            downloadTemplates();
        }
        $.getJSON(templateJson, cb);
    }

    function downloadTemplates() {
        var numDownloaded = 0;
        function cb(id, html) {
            addTemplate(id, html);
            numDownloaded++;
            if(templateFiles.length == numDownloaded) {
                success();
            }
        }
        _.each(templateFiles, function(template) {
            $.get(
                template.file,
                function(html) {
                    cb(template.id, html);
                }
            )
        });
    }

    function setCallback(cb) {
        callback = cb;
    }
    function success() {
        if(callback) {
            callback();
        }
    }

    function getTemplate(id) {
        if(templates[id]) {
            return templates[id];
        } else {
            throw "Template '"+id+"' does not exist.";
        }
    }

    return {
        download: download,
        getTemplate: getTemplate,
        setCallback: setCallback
    }

})();