<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px;margin-top:-15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            Factuur  <small>toevoegen</small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Factuur_New" id="frm_Factuur_New" class="form-horizontal" role="form" action="<?php echo admin_url(); ?>" method="post" onsubmit="return false;">
            <div class="row pad">
                <div class="col-md-12" id="col-md-12">
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_klantid");?>">
                        <div class="col-sm-2 control-label">
                            <label for="subject">
                               Klant ID
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php
                            $user_category_select = array();
                            $user_category_select [""] = "Geen";
                            $user_select = $this->controller->post->list_all_users;
                            if($user_select != NULL){
                                foreach($user_select as $key => $value){
                                    $user_category_select[$key] = $value;
                                }
                            }else{ $user_category_select [""] = "Niks"; }
                            $user_category_select = $user_category_select;
                            echo form_dropdown ("factuur_klantid", $user_category_select);
                        		?>
                            <?php $this->error_message("factuur_klantid")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_info");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Factuur info
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_editor("factuur_info");?>
                            <?php $this->error_message("factuur_info")?>
                        </div>
										</div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_datum");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Datum
                                 <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_date("factuur_datum");?>
                            <?php $this->error_message("factuur_datum")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_verloopdatum");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Verloopdatum
                                 <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_date("factuur_verloopdatum");?>
                            <?php $this->error_message("factuur_verloopdatum")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_prijs");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Bedrag
                                 <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_prijs");?>
                            <?php $this->error_message("factuur_prijs")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_BTW");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               BTW regel
                                 <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_BTW");?>
                            <?php $this->error_message("factuur_BTW")?>
                        </div>
                    </div>
							<div id="append">
                 <?php
                    $answer = isset($this->controller->post->answer) ? $this->controller->post->answer : '';
                    $stt = 1;
                    if(is_array($answer)):
                        foreach($answer as $key => $val):
                  ?>
                    <div class="form-group custom-cols">
                        <label for="question" class="col-sm-2 control-label">Veld</label>
                        <div class="col-sm-10" style="padding:0px">
                            <div class="col-sm-11"><input type="text" name='answer[]' value="<?php echo $val; ?>" class="form-control"  />														</div>
                            <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
            </div>
             <div class="form-group" id="addMore">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="form-inline">
                        <button type="button" id='btnAddField' class="btn btn-info" onclick="add_veld_add();">Voeg veld toe</button>
                    </div>
                </div>
            </div>
									
                    <?php echo Pf::event()->trigger("filter","factuur-form"); ?>
                    <?php echo Pf::event()->trigger("filter","factuur-adding-form"); ?>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'inline-block'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});

	$('.input-group-btn a').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function() {
            var imgurl  =   $('#testimonial_avatar').val();
            if (imgurl.length > 0) {
                $('#img-avatar').attr('src', '../'+imgurl);
            }
        }
    });
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
	   tinymce.remove();
	}catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Factuur_New(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
	   tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Factuur_New').attr('action'),$('#frm_Factuur_New').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> Factuur aangemaakt",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>
<script>
function add_veld_add(){
    var source   = $("#field-template").html();
    var append = $('#append').append('<div class="form-group custom-cols"> <label for="question" class="col-sm-2 control-label">Veld</label><div class="col-sm-10" style="padding:0px">  <div class="col-sm-11"> <?php echo form_input(array('name' => 'answer[]', 'class' => 'form-control'));?></div> <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div></div></div>');
}
$('#append').on('click', '.btn-remove', function(){
    $(this).closest('.custom-cols').remove();
    return false;
});
</script>