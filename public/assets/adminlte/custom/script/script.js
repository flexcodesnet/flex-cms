/**
 *
 * @param option [
 *          summernoteLocal,
 *          url,
 *      ]
 */
function main(option) {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize bsCustomFileInput Elements
    bsCustomFileInput.init();

    window.editor = [];
    document.querySelectorAll('.text-editor').forEach(function (val) {
        ClassicEditor
            .create(val, {
                mediaEmbed: {
                    previewsInData: true
                },
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'bulletedList',
                        'numberedList',
                        'removeFormat',
                        'fontBackgroundColor',
                        'fontColor',
                        '|',
                        'outdent',
                        'indent',
                        'alignment',
                        '|',
                        'imageInsert',
                        'blockQuote',
                        'insertTable',
                        'mediaEmbed',
                        'undo',
                        'redo'
                    ]
                },
                language: $(val).data('locale'),
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
            })
            .then(editor => {
                window.editor[val.id] = editor;
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: 62jcx62kppss-3wzbxu43l1qi');
                console.error(error);
            });
    });

    // Initialize bootstrap switch
    $("input[data-bootstrap-switch]").each(function () {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
}

function tagify(query) {
    // The DOM element you wish to replace with Tagify
    const input = document.querySelector(query);

    // initialize Tagify on the above input node reference
    const tagify = new Tagify(input)

    // bind "DragSort" to Tagify's main element and tell
    // it that all the items with the below "selector" are "draggable"
    const dragsort = new DragSort(tagify.DOM.scope, {
        selector: '.' + tagify.settings.classNames.tag,
        callbacks: {
            dragEnd: function (elm) {
                // must update Tagify's value according to the re-ordered nodes in the DOM
                tagify.updateValueByDOMTags()
            }
        }
    })
}

function clean(obj) {
    let propNames = Object.getOwnPropertyNames(obj);
    for (let i = 0; i < propNames.length; i++) {
        let propName = propNames[i];
        if (obj[propName] === null || obj[propName] === undefined) {
            delete obj[propName];
        }
    }
    return obj
}

/**
 *
 * @param option [
 *          query,
 *          url,
 *      ]
 */
function sortable(option) {
    let dropIndex;
    $(option.query).sortable({
        update: function (event, ui) {
            dropIndex = ui.item.index()

            let imageIdsArray = [];
            $(`${option.query} li`).each(function (index) {
                let href = $(this).find('.remove-image').attr('href')
                imageIdsArray.push(href)
            })

            if ($debug)
                console.log(imageIdsArray)

            $.ajax({
                url: option.url,
                headers: {
                    'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
                },
                type: 'PUT',
                data: {images: imageIdsArray},
                success: function (result, status, xhrs) {
                    if ($debug) {
                        console.log(result)
                    }
                    if (status === "success") {
                        toastr.success(result.message)
                    } else {
                        // event.target.reset()
                    }
                },
                error: function (xhr, status, error) {
                    if ($debug) {
                        console.log(xhr)
                        console.log(error)
                    }
                    toastr.error(xhr.responseJSON.message);
                }
            })
        }
    })
}

/**
 *
 * @param option [
 *          path,
 *          getURL,
 *          putURL,
 *      ]
 */
function captionImage(option) {
    $('#update-caption form').submit(function (event) {
        event.preventDefault()
        const formData = new FormData(event.target)
        const fromEntries = Object.fromEntries(formData.entries())
        fromEntries.path = option.path

        if ($debug)
            console.log(fromEntries)

        $.ajax({
            url: option.putURL,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
            },
            data: fromEntries,
            success: function (result, status, xhr) {
                if ($debug) {
                    console.log(result)
                }
                if (status === "success") {
                    toastr.options.onHidden = function () {
                        window.location.reload(true);
                    }
                    $(option.query).find('#loading').addClass('invisible')
                    $(option.query).modal('hide')
                    toastr.success(result.message)
                } else {
                    // event.target.reset()
                }
            },
            error: function (xhr, status, error) {
                if ($debug) {
                    console.log(xhr)
                    console.log(error)
                }
                toastr.error(xhr.responseJSON.message);
            }
        })
    })

    $.get({
        url: `${option.getURL}?path=${option.path}`,
        headers: {
            'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
        },
        success: function (result, status, xhr) {
            if ($debug) {
                console.log(result)
            }
            if (status === "success") {
                $('#update-caption').find('#loading').addClass('invisible')
                if (result.model !== null) {
                    $('#update-caption').find('input[name="caption"]').val(result.model.locale_caption)
                }
            } else {
                // event.target.reset()
            }
        },
        error: function (xhr, status, error) {
            if ($debug) {
                console.log(xhr)
                console.log(error)
            }
            toastr.error(xhr.responseJSON.message);
        }
    })

    $('#update-caption').find('input[name="caption"]').val('')
    $('#update-caption').find('#loading').removeClass('invisible')
    $('#update-caption').modal('show')
}

/**
 *
 * @param option [
 *          query,
 *          url,
 *      ]
 */
function removeImage(option) {
    $(option.query).click(function (event) {
        event.preventDefault()
        if ($debug)
            console.log($(this).attr('href'))

        $.ajax({
            url: `${option.url}${$(this).attr('href')}&item=${$(this).data('item')}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
            },
            success: function (result, status, xhr) {
                if ($debug) {
                    console.log(result)
                }
                if (status === "success") {
                    toastr.options.onHidden = function () {
                        window.location.reload(true);
                    }
                    toastr.success(result.message)
                } else {
                    // event.target.reset()
                }
            },
            error: function (xhr, status, error) {
                if ($debug) {
                    console.log(xhr)
                    console.log(error)
                }
                toastr.error(xhr.responseJSON.message);
            }
        })
    })
}

/**
 *
 * @param option [
 *          queryCarousel,
 *          rtl,
 *          url [
 *              removeImage,
 *              featuredImage,
 *          ],
 */
function initGallery(option) {
    $(option.queryCarousel).owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        rtl: option.rtl,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    })

    removeImage({
        query: `${option.queryCarousel} .remove-image`,
        url: option.url.removeImage,
    })

    if (option.url.featuredImage) {
        $(`${option.queryCarousel} .featured-image`).click(function (event) {
            event.preventDefault()
            if ($debug) {
                console.log($(this).attr('href'))
            }

            $.ajax({
                url: `${option.url.featuredImage}${$(this).attr('href')}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
                },
                success: function (result, status, xhr) {
                    if ($debug) {
                        console.log(result)
                    }
                    if (status === "success") {
                        toastr.options.onHidden = function () {
                            window.location.reload(true);
                        }
                        toastr.success(result.message)
                    } else {
                        // event.target.reset()
                    }
                },
                error: function (xhr, status, error) {
                    if ($debug) {
                        console.log(xhr)
                        console.log(error)
                    }
                    toastr.error(xhr.responseJSON.message);
                }
            })
        })
    }
}

/**
 *
 * @param option [
 *          queryTown,
 *          queryDistrict,
 *          queryQuarter,
 *          url [
 *              getTowns,
 *              getDistrictsByTown,
 *              getQuartersByDistrictAndTown,
 *          ]
 *      ]
 */
function iniAddress(option) {
    const $town = $(option.queryTown)
    const $district = $(option.queryDistrict)
    const $quarter = $(option.queryQuarter)

    if ($debug) {
        console.log(option)
    }

    $.get(option.url.getTowns, function (data, status) {
        if ($debug) {
            console.log(data)
        }
        $town.empty()
        $town.append(`<option selected disabled>${$please_choose}</option>`);
        for (let i = 0; i < data.length; i++) {
            $town.append(`<option
${((option.selected.townId !== undefined) && (option.selected.townId === data[i]._id)) ? "selected" : ""}
value=` + data[i]._id + '>' + data[i].name + '</option>');
        }

        if ((option.selected.townId !== undefined)) {
            $.get(option.url.getDistrictsByTown.replace(':id', option.selected.townId), function (data, status) {
                if ($debug) {
                    console.log(data)
                }
                $district.empty()
                $district.append(`<option selected disabled>${$please_choose}</option>`);
                for (let i = 0; i < data.length; i++) {
                    $district.append(`<option
${((option.selected.districtId !== undefined) && (option.selected.districtId === data[i]._id)) ? "selected" : ""}
value=` + data[i]._id + '>' + data[i].name + '</option>');
                }

                if ((option.selected.districtId !== undefined)) {
                    $.get(option.url.getQuartersByDistrictAndTown
                        .replace(':townId', option.selected.townId)
                        .replace(':districtId', option.selected.districtId), function (data, status) {
                        if ($debug) {
                            console.log(data)
                        }
                        $quarter.empty()
                        $quarter.append(`<option selected disabled>${$please_choose}</option>`);
                        for (let i = 0; i < data.length; i++) {
                            $quarter.append(`<option
${((option.selected.quarterId !== undefined) && (option.selected.quarterId === data[i]._id)) ? "selected" : ""}
value=` + data[i]._id + '>' + data[i].name + '</option>');
                        }
                    })
                }
            })
        }
    })

    $town.change(function () {
        const $townId = $town.find(':selected')[0].value;
        if ($debug) {
            console.log($townId)
        }

        $.get(option.url.getDistrictsByTown.replace(':id', $townId), function (data, status) {
            if ($debug) {
                console.log(data)
            }

            $district.empty()
            $district.append(`<option selected disabled>${$please_choose}</option>`);
            for (let i = 0; i < data.length; i++) {
                $district.append(`<option value=` + data[i]._id + '>' + data[i].name + '</option>');
            }
        })
    })

    $district.change(function () {
        const $townId = $town.find(':selected')[0].value;
        const $districtId = $district.find(':selected')[0].value;
        if ($debug) {
            console.log($townId)
            console.log($districtId)
        }

        $.get(option.url.getQuartersByDistrictAndTown
            .replace(':townId', $townId)
            .replace(':districtId', $districtId), function (data, status) {
            if ($debug) {
                console.log(data)
            }

            $quarter.empty()
            $quarter.append(`<option selected disabled>${$please_choose}</option>`);
            for (let i = 0; i < data.length; i++) {
                $quarter.append(`<option value=` + data[i]._id + '>' + data[i].name + '</option>');
            }
        })
    })
}

function initCodeMirror(query) {
    CodemirrorInstance = CodeMirror.fromTextArea(document.getElementById(query), {
        lineNumbers: true,
        mode: "htmlmixed",
        // theme: "idea"
    })

    // on and off handler like in jQuery
    CodemirrorInstance.on('change', function (cMirror) {
        // get value right from instance
        document.getElementById(query).value = cMirror.getValue()
        if ($debug) {
            console.log(document.getElementById(query).value)
        }
    })
}

/**
 *
 * @param option [
 *          queryInput,
 *          queryHidden
 */
function initMultiSelect(option) {
    $(option.queryInput).on("change", function (e) {
        $(option.queryHidden).val(`${$(this).val()}`)
        if ($debug) {
            console.log($(option.queryHidden).val())
        }
    })
}

/**
 *
 * @param option [
 *          slug,
 *          target,
 *          locale,
 *          url,
 *        ]
 */
function initUppy(option) {
    const uppy = new Uppy.Core({
        debug: $debug,
        locale: option.locale,
        autoProceed: false,
        restrictions: {
            maxFileSize: 1500000,
            maxNumberOfFiles: 19,
            minNumberOfFiles: 1,
            allowedFileTypes: ['image/*']
        }
    })

    uppy.use(Uppy.Dashboard, {
        inline: true,
        target: option.target,
        replaceTargetContent: true,
        showProgressDetails: true,
        height: 256,
        browserBackButtonClose: false
    })

    uppy.use(Uppy.XHRUpload, {
        endpoint: option.url,
        headers: {
            'X-CSRF-TOKEN': $token, // pass the CSRF_TOKEN()
            'X-Requested-With': 'XMLHttpRequest', // multipart/form-data
        },
        limit: 10,
        formData: true,
        bundle: true,
        fieldName: `${option.slug}[]`,
    })

    uppy.on('upload-success', (file, response) => {
        response.status // HTTP status code
        response.body   // extracted response data

        // do something with file and response
        if (response.status === 200) {
            toastr.options.onHidden = function () {
                window.location.reload(true);
            }
            toastr.success(response.body.message)
        } else {
            // event.target.reset()
        }
    })
}

/**
 * Show Delete Modal
 * @param option [
 *          id,
 *          query,
 *          url
 *        ]
 */
function delModal(option) {
    $(option.query).find("#delete").off().click(function () {
        $(option.query).find('#loading').removeClass('invisible')

        option.url = option.url.replace(':id', option.id)
        if ($debug) {
            console.log(option.url)
        }
        $.ajax({
            url: option.url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
            },
            data: {
                // _token: $token, //pass the CSRF_TOKEN()
                id: option.id,
            },
            success: function (result, status, xhr) {
                if ($debug) {
                    console.log(result)
                }
                if (status === "success") {
                    $(option.query).find('#loading').addClass('invisible')
                    $(option.query).modal('hide')
                    toastr.success(result.message)
                    try {
                        $table.ajax.reload()
                    } catch (e) {
                        // do something
                        if (e instanceof ReferenceError) {
                            // Handle error as necessary
                            window.location.reload(true)
                        }
                    }
                } else {
                    $(option.query).find('#loading').addClass('invisible')
                }
            },
            error: function (xhr, status, error) {
                if ($debug) {
                    console.log(xhr)
                    console.log(error)
                }
                toastr.error(xhr.responseJSON.message);
                $(option.query).find('#loading').addClass('invisible')
            }
        })
    })

    $(option.query).find('.modal-body strong').html(option.id)
    $(option.query).modal('show')
}

/**
 * Show Add Modal
 * @param option [
 *          id,
 *          query,
 *          url
 *        ]
 */
function addModal(option) {
    $(`${option.query} form`).submit(function (event) {
        event.preventDefault()
        const formData = new FormData(event.target)
        const fromEntries = Object.fromEntries(formData.entries())
        if ($debug)
            console.log(fromEntries)

        $.ajax({
            url: option.url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
            },
            data: fromEntries,
            success: function (result, status, xhr) {
                if ($debug) {
                    console.log(result)
                }
                if (status === "success") {
                    toastr.options.onHidden = function () {
                        window.location.reload(true);
                    }
                    $(option.query).find('#loading').addClass('invisible')
                    $(option.query).modal('hide')
                    toastr.success(result.message)
                } else {
                    // event.target.reset()
                }
            },
            error: function (xhr, status, error) {
                if ($debug) {
                    console.log(xhr)
                    console.log(error)
                }
                toastr.error(xhr.responseJSON.message);
            }
        })
    })

    $(option.query).modal('show')
}

/**
 *
 * @param option [
 *          all,
 *          slug,
 *          query,
 *          data,
 *          values,
 *          disabled,
 *        ]
 */
function initTreeView(option) {
    return new Tree(option.query, {
        data: [{id: '-1', text: option.all, children: option.data}],
        closeDepth: 3,
        loaded: function () {
            this.values = option.values

            if ($debug) {
                console.log(this.selectedNodes)
                console.log(this.values)
            }
            if (option.disabled) {
                this.disables = this.values
                $(`.treejs-node`).addClass('treejs-node__disabled')
            }
            $(`input[name="${option.slug}"]`).val(this.values)
        },
        onChange: function () {
            if ($debug) {
                console.log(this.selectedNodes)
                console.log(this.values)
            }
            $(`input[name="${option.slug}"]`).val(this.values)
        }
    })
}
