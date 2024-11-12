@push('page-css')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet"/>
@endpush

@push('page-javascript')
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        const filePondForms = document.querySelectorAll('.filePondForm');
        filePondForms.forEach(function (form) {
            let filePondInputs = form.querySelectorAll('.filePondInput');
            let ponds = [...filePondInputs].map(function (input) {
                return FilePond.create(input, {
                    maxFileSize: '100MB',
                    instantUpload: false,
                    files: input.dataset.url ? [input.dataset.url] : [],
                    server: {
                        process: '/admin/uploads/process',
                        headers: {
                            'X-CSRF-TOKEN': '{!! csrf_token() !!}',
                        }
                    }
                });
            });

            $(form).on('submit', function (e) {
                e.preventDefault();

                let form = this;

                Promise.all(
                    ponds
                        .filter((pond) => pond.getFiles().length > 0)
                        .map(function (pond) {
                            return pond.processFiles();
                        })
                ).then(function () {
                    setTimeout(function () {
                        form.submit();
                    }, 500);
                })
            })
        })
    </script>
@endpush

