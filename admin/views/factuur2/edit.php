<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            Klant  <small>edit</small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Factuur2_Edit" id="frm_Factuur2_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            <div class="row pad">
                                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_naam");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Naam
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_naam");?>
                            <?php $this->error_message("factuur_naam")?>
                        </div>
									  </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_klant");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Klant ID (voor eigen administratie)
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_klant");?>
                            <?php $this->error_message("factuur_klant")?>
                        </div>
									  </div>
									  <div class="form-group <?php $this->error_class("factuur_gebruikerkoppeling");?>">
                    <label for="subject" class="col-sm-2 control-label">
                       Gebruikerkoppeling
                    </label>
                    <div class="col-sm-8" >
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
                            echo form_dropdown ("factuur_gebruikerkoppeling", $user_category_select);
                        ?>
                        <?php $this->error_message("factuur_gebruikerkoppeling")?>
                    </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_bedrijfsnaam");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Bedrijfsnaam
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_bedrijfsnaam");?>
                            <?php $this->error_message("factuur_bedrijfsnaam")?>
                        </div>
									  </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_adres");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Adres
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_adres");?>
                            <?php $this->error_message("factuur_adres")?>
                        </div>
									  </div>
									  <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_postcode");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Postcode
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_postcode");?>
                            <?php $this->error_message("factuur_postcode")?>
                        </div>
									  </div>
									 <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_provincie");?>">
                        <div class="col-sm-2 control-label">
                                <label for="subject">
                                   Provincie
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("factuur_provincie");?>
                            <?php $this->error_message("factuur_provincie")?>
                        </div>
									  </div>
									 <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_datum");?>">
                        <div class="col-sm-2 control-label">
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
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_content");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Notities
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_textarea("factuur_content");?>
                            <?php $this->error_message("factuur_content")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("factuur_info");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               Meer notities
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_textarea("factuur_info");?>
                            <?php $this->error_message("factuur_info")?>
                        </div>
                    </div>
                    <?php echo Pf::event()->trigger("filter","testimonials-form"); ?>
                    <?php echo Pf::event()->trigger("filter","testimonials-editing-form"); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});

	avatar = $('#testimonial_avatar').val();
	if(avatar == ''){
		$('#img-avatar').attr('src','<?php echo no_image();?>');
	}else{
		$('#img-avatar').attr('src','<?php echo RELATIVE_PATH;?>/'+avatar);
	}
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
function Factuur2_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Factuur2_Edit').attr('action'),$('#frm_Factuur2_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Testimonial is updated successfully','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function Factuur2_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Factuur2_Edit').attr('action'),$('#frm_Factuur2_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Testimonial is updated successfully','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>