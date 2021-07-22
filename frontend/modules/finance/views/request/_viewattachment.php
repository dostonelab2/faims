<div class="file-loading">
    <input id="input-pd" name="input-pd[]" type="file" multiple>
</div>
<script>
$("#input-pd").fileinput({
    uploadUrl: "/file-upload-batch/1",
    uploadAsync: false,
    minFileCount: 2,
    maxFileCount: 5,
    overwriteInitial: false,
    initialPreview: [
        // PDF DATA
        'https://kartik-v.github.io/bootstrap-fileinput-samples/samples/pdf-sample.pdf',
    ],
    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    initialPreviewDownloadUrl: 'https://kartik-v.github.io/bootstrap-fileinput-samples/samples/{filename}', // includes the dynamic `filename` tag to be replaced for each config
    initialPreviewConfig: [
        {type: "pdf", size: 8000, caption: "About.pdf", url: "/file-upload-batch/2", key: 10, downloadUrl: false}, // disable download

    ],
    uploadExtraData: {
        img_key: "1000",
        img_keywords: "happy, places"
    }
}).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('fileuploaded', function(e, params) {
    console.log('File uploaded params', params);
});
</script>