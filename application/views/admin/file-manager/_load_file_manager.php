<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/file-manager/fileicon.css"/>
<?php if (!empty($load_images)) {
    $images = $this->file_model->get_images(60);
    $this->load->view("admin/file-manager/_file_manager_image", ['images' => $images]);
}
if (!empty($load_quiz_images)) {
    $quiz_images = $this->file_model->get_quiz_images(60);
    $this->load->view("admin/file-manager/_file_manager_quiz_image", ['quiz_images' => $quiz_images]);
}
if (!empty($load_files)) {
    $files = $this->file_model->get_files(60);
    $this->load->view("admin/file-manager/_file_manager", ['files' => $files]);
}
if (!empty($load_videos)) {
    $videos = $this->file_model->get_videos(60);
    $this->load->view("admin/file-manager/_file_manager_video", ['videos' => $videos]);
}
if (!empty($load_audios)) {
    $audios = $this->file_model->get_audios(60);
    $this->load->view("admin/file-manager/_file_manager_audio", ['audios' => $audios]);
} ?>
