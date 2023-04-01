document.addEventListener("DOMContentLoaded", function () {

    const $enableDangerousActionForm = $('#enable-dangerous-action-form')
    $enableDangerousActionForm.on('submit', (e) => {
        e.preventDefault()
        const dangerousKey = $enableDangerousActionForm.find("input[name='key']").val()
        $('.dangerous-action-key-value').each((index, el) => {
            $(el).val(dangerousKey)
        })
        $('.dangerous-action-button').removeAttr("disabled")
    });

    const $copyLinkButton = $('#copyLinkButton')
    if ($copyLinkButton) {
        navigator.permissions.query({name: "clipboard-write"}).then((result) => {
            if (result.state === "granted" || result.state === "prompt") {
                $copyLinkButton.on('click', () => {
                    navigator.clipboard.writeText(document.getElementById('appLinkText').innerText);
                    $copyLinkButton.val('Copied!')
                    setTimeout(() => {
                        $copyLinkButton.val('Copy Link')
                    }, 2000)
                })

                $copyLinkButton.show()
            }
        });
    }

    const $recipesTable = $('#recipesTable')
    $recipesTable.find('tbody textarea').map(function (i, el) {
        $(el).change(function(e) {
            const $form = $(e.target).parents('form')
            $.ajax({
                url: $form.attr('action'),
                dataType: "json",
                type: "Post",
                async: true,
                data: $form.serialize(),
                success: function (data) {},
            })
        })
    })
})
