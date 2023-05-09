<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('additional_images'); ?>
                <small class="small-title"><?php echo trans("more_main_images"); ?></small>
            </h3>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="form-group m-0">
            <div class="row">
                <div class="col-sm-12">
                    <a class='btn btn-sm bg-purple' data-toggle="modal" data-target="#file_manager_image" data-image-type="additional">
                        <?php echo trans('select_image'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group m-0">
            <div class="row">
                <div class="col-sm-12">
                    <?php if (!empty($post)): ?>
                        <div class="additional-image-list">
                            <?php $additional_images = get_post_additional_images($post->id); ?>
                            <?php if (!empty($additional_images)): ?>
                                <?php foreach ($additional_images as $image):
                                    $img_base_url = base_url();
                                    if ($image->storage == "aws_s3") {
                                        $img_base_url = $this->aws_base_url;
                                    } ?>
                                    <div class="additional-item additional-item-<?php echo $image->id; ?>">
                                        <img class="img-additional" src="<?php echo $img_base_url . $image->image_default; ?>" alt="">
                                        <a class="btn btn-danger btn-sm btn-delete-additional-image-database" data-value="<?php echo $image->id; ?>">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="additional-image-list"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>