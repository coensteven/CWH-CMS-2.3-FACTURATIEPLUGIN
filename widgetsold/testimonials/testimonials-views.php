<?php
    defined('PF_VERSION') OR header('Location:404.html');
    $title = isset($this->setting['widget-title']) && trim($this->setting['widget-title']) != ''  ? $this->setting['widget-title'] : '';
    $rand = uniqid() . rand(0, 100);?>
<div class="widgets row testimonial-widget">
    <div class="col-xs-12">
        <?php echo !empty($title) ? "<h3 class='text-color widget-title'>$title</h3><hr>" : ''; ?>
        <div class="widget-content">
            <div id="testimonials-<?php echo $rand; ?>" class="carousel slide testimonials testimonials-v1">
                <a class="left-arrow" href="#testimonials-<?php echo $rand; ?>" data-slide="prev"> <i class="fa fa-angle-left"></i></a>
                <a class="right-arrow" href="#testimonials-<?php echo $rand; ?>" data-slide="next"> <i class="fa fa-angle-right"></i> </a>
                <div class="carousel-inner">
                    <?php foreach ($list as $item) { ?>
                        <div class="item <?php
                            if (empty($active)) {
                                echo "active";
                            }
                        ?>">
                            <p><?php echo $item['testimonial_content']; ?></p>

                            <div class="testimonial-info">
                                <div class='avatar-img img-circle pull-left'>
                                    <img
                                        src="<?php echo!empty($item['testimonial_avatar']) ? get_thumbnails($item['testimonial_avatar'],400) : "data:image/png;base64,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"; ?>"
                                        alt="">
                                </div>
                                <div class="pull-left testimonial-author text-color">
                                    <?php echo $item['testimonial_name']; ?>,
                                    <em><?php echo $item['testimonial_info']; ?>.</em><br/>
                                </div>
                            </div>
                        </div>
                        <?php $active = 1;}?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php public_css("http://fonts.googleapis.com/css?family=Amaranth:400,700",true); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.carousel .right-arrow').trigger('click');
    });
</script>