<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Modal -->
<div id="file_manager" class="modal fade modal-file-manager" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo trans('files'); ?></h4>
                <div class="file-manager-search">
                    <input type="text" id="input_search_file" class="form-control" placeholder="<?php echo trans("search"); ?>">
                </div>
            </div>
            <div class="modal-body">
                <div class="file-manager">
                    <div class="file-manager-left">
                        <div class="dm-uploader-container">
                            <div id="drag-and-drop-zone" class="dm-uploader text-center">
                                <?php if (!empty($this->general_settings->allowed_file_extensions)):
                                    $exts = explode(',', $this->general_settings->allowed_file_extensions);
                                    if (!empty($exts) && item_count($exts) > 0): ?>
                                        <p class="file-manager-file-types">
                                            <?php foreach ($exts as $ext):
                                                $ext = str_replace('"', '', $ext);
                                                $ext = strtoupper($ext); ?>
                                                <span><?= html_escape($ext); ?></span>
                                            <?php endforeach; ?>
                                        </p>
                                    <?php endif;
                                endif; ?>
                                <p class="dm-upload-icon">
                                    <i class="fa fa-cloud-upload"></i>
                                </p>
                                <p class="dm-upload-text"><?php echo trans("drag_drop_files_here"); ?></p>
                                <p class="text-center">
                                    <button class="btn btn-default btn-browse-files"><?php echo trans('browse_files'); ?></button>
                                </p>
                                <a class='btn btn-md dm-btn-select-files'>
                                    <input type="file" name="file" size="40" multiple="multiple">
                                </a>
                                <ul class="dm-uploaded-files" id="files-file"></ul>
                                <button type="button" id="btn_reset_upload" class="btn btn-reset-upload"><?php echo trans("reset"); ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="file-manager-right">
                        <div class="file-manager-content">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div id="file_upload_response">
                                        <?php foreach ($files as $file):
                                            if (!empty($file)): ?>
                                                <div class="col-file-manager" id="file_col_id_<?php echo $file->id; ?>">
                                                    <div class="file-box" data-file-id="<?php echo $file->id; ?>" data-file-name="<?php echo $file->file_name; ?>">
                                                        <div class="image-container icon-container">
                                                            <div class="file-icon file-icon-lg" data-type="<?php echo @pathinfo($file->file_path, PATHINFO_EXTENSION); ?>"></div>
                                                        </div>
                                                        <span class="file-name"><?php echo html_escape($file->file_name); ?></span>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="selected_file_id">
                    <input type="hidden" id="selected_file_name">
                    <input type="hidden" id="selected_file_path">
                </div>
            </div>

            <div class="modal-footer">
                <div class="file-manager-footer">
                    <button type="button" id="btn_file_delete" class="btn btn-danger pull-left btn-file-delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?php echo trans('delete'); ?></button>
                    <button type="button" id="btn_file_select" class="btn bg-olive btn-file-select"><i class="fa fa-check"></i>&nbsp;&nbsp;<?php echo trans('select_file'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File item template -->
<script type="text/html" id="files-template-file">
    <li class="media">
        <img class="preview-img" src="<?php echo base_url(); ?>assets/admin/plugins/file-manager/file.png" alt="">
        <div class="media-body">
            <div class="progress">
                <div class="dm-progress-waiting"><?php echo trans("waiting"); ?></div>
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    var txt_processing = "<?php echo trans("txt_processing"); ?>";
    $(function () {
        $('#drag-and-drop-zone').dmUploader({
            url: '<?php echo base_url(); ?>file_controller/upload_file',
            queue: true,
            extFilter: [<?= $this->general_settings->allowed_file_extensions;?>],
            extraData: function (id) {
                return {
                    "file_id": id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": $.cookie(csfr_cookie_name)
                };
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onInit: function () {
            },
            onComplete: function (id) {
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "file");
            },
            onBeforeUpload: function (id) {
                $('#uploaderFile' + id + ' .dm-progress-waiting').hide();
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
                $("#btn_reset_upload").show();
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                refresh_files();
                document.getElementById("uploaderFile" + id).remove();
                ui_multi_update_file_status(id, 'success', 'Upload Complete');
                ui_multi_update_file_progress(id, 100, 'success', false);
                $("#btn_reset_upload").hide();
            },
            onUploadError: function (id, xhr, status, message) {
            },
            onFallbackMode: function () {
            },
            onFileSizeError: function (file) {
            },
            onFileTypeError: function (file) {
                swal({
                    text: "<?= trans("invalid_file_type");?>",
                    icon: "warning",
                    button: sweetalert_ok
                });
            },
            onFileExtError: function (file) {
                swal({
                    text: "<?= trans("invalid_file_type");?>",
                    icon: "warning",
                    button: sweetalert_ok
                });
            }
        });
    });


    $(document).on('click', '#btn_reset_upload', function () {
        $("#drag-and-drop-zone").dmUploader("reset");
        $("#files-file").empty();
        $(this).hide();
    });
</script>
