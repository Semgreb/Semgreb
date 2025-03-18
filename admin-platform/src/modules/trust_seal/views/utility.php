<script>
    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }

    function MydownloadFile(url, filename) {
        var element = document.createElement('a');
        element.setAttribute('href', url);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
    }

    function showLogoDownload(nameSeal, activeLogo, inactiveLogo) {
        $("#imgActive").attr("src", activeLogo);
        $("#imgInactive").attr("src", inactiveLogo);
        $("#myModalLabel").html(nameSeal);
        $('#audit_commet').modal('show')
    }

    $(".btn_active_download").click(function(e) {
        e.preventDefault();
        let imgActive = $(this).closest('div').find('#imgActive');
        let url = imgActive.attr("src");
        MydownloadFile(url, "active_Seal");
    });

    $(".btn_inactive_download").click(function(e) {
        e.preventDefault();
        let imgInactive = $(this).closest('div').find('#imgInactive');
        let url = imgInactive.attr("src");
        MydownloadFile(url, "inactive_Seal");
    });
</script>