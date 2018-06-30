<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> Zoeken", array (
        'onclick' => 'Factuur_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=factuur&sub_page=factuur';
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_Factuur_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>

<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> Nieuwe factuur", array('onclick' => 'Factuur_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>

<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> Opslaan", array('onclick' => 'Factuur_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> Annuleren", array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> Opslaan", array('onclick' => 'Factuur_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> Toepassen", array('onclick' => 'Factuur_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> Annuleren", array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            Facturatie systeem  <small>overzicht facturen</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="btn-group" id="bulk-action-container"
            style="display: none;">
            <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <?php echo __('Bulk Actions','testimonials'); ?> <span
                    class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"
                    onclick="bulk_action('delete'); return false;">Verwijderen</a></li>
                <li><a href="#"
                    onclick="bulk_action('publish'); return false;">Betaald</a></li>
                <li><a href="#"
                    onclick="bulk_action('unpublish'); return false;">Niet betaald</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4" style="height: 34px;">&nbsp;</div>
</div>
<div class="row" style="padding: 10px 0px;">
    <div class="col-md-6">
        <div class="btn-group">
            <button type="button"
                class="btn btn-default btn-sm btn-flat dropdown-toggle"
                data-toggle="dropdown">
                <span id="view-title">Alles</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a
                    href="<?php echo admin_url("admin-page=factuur", false); ?>"> Alles</a></li>
                <li><a href="javascript:view_allstatus(1,'published');"> Betaald</a></li>
                <li><a
                    href="javascript:view_allstatus(2,'unpublished');"> Niet betaald</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="pull-right">
            <?php echo $this->pagination->short_page_link(); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <form method="post" role="form"
                action="<?php echo admin_url('&action=bulk_action'); ?>"
                id="frm-bulk-action">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-right" style="width: 30px; background: #3c8dbc; color: white;">#</th>
                                <th class="text-right" style="width: 30px; background: #3c8dbc; color: white;"><input type="checkbox" id="check-all"></th>
															  <th style="background: #3c8dbc; color: white;width:188px">Factuurnummer</th>
                                <th style="background: #3c8dbc; color: white;width: 136px;">Bedrag</th>
															  <th style="background: #3c8dbc; color: white;width: 136px;">Aangemaakt</th>
															  <th style="background: #3c8dbc; color: white;width: 136px;">Verloopdatum</th>
                                <th style="background: #3c8dbc; color: white;width:188px">Klant ID</th>
                                <th style="background: #3c8dbc; color: white;width:88px;">
                                    <?php
                                    $factuur_status_icon = "";
                                    if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "factuur_status") {
                                        if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                            $factuur_status_icon = "fa-sort-desc";
                                        } else {
                                            $factuur_status_icon = "fa-sort-asc";
                                        }
                                    }
                                    
                                    ?>
                                    <div style="cursor: pointer; position: relative;" id="order_factuur_status" onclick="Factuur_Oder(this,'factuur_status');">
                                        Status
                                        <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $factuur_status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($factuur_status_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th class="text-center" style="width: 100px; background: #3c8dbc; color: white;">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php if (!empty($this->records) && is_array($this->records)){?>
                    <?php
                        
                        foreach ( $this->records as $index => $record ) {
                            $token = Pf_Plugin_CSRF::token ( $this->key . $record ['id'] );
                            ?>
                            <tr>
                                <td class="text-right"><?php echo (($this->page_index - 1) * NUM_PER_PAGE + $index +1); ?></td>
                                <td><input type="checkbox" name="id[]"
                                    value="<?php echo (int)$record['id'];?>">
                                    <input type="hidden"
                                    name="<?php echo 'token[' . (int)$record['id'] . ']' ?>"
                                    value="<?php echo $token; ?>"></td>
                                <td><?php echo $record["factuur_nummer"]; ?>-<?php echo $record["id"]; ?></td>
                                <!--<td><?php echo $record["factuur_info"]; ?></td>-->
															  <td>&euro; <?php echo $record["factuur_prijs"]; ?></td>
															  <td><?php echo $record["factuur_datum"]; ?></td>
															  <td><?php echo $record["factuur_verloopdatum"]; ?></td>
                                <td><?php echo $record["factuur_klantid"]; ?> (<?php
																$list_gebruiker = $this->controller->factuurklanten_model->get_gebruiker($record["factuur_klantid"]); 
														
																if($list_gebruiker != NULL){
																	echo $list_gebruiker['factuur_naam'];
																}else{
																	echo "Geen gebruikers gevonden";
																}
																
														  	?>)</td>
                                <td>
                                    <?php if($record['factuur_status'] == 1){ ?>
                                        <span id="status_<?php echo $record['id'];?>"><a href="javascript:change_statusfactuur(<?php echo $record['id'];?>,'unpublish');" class="label label-success">Betaald</a></span>
                                    <?php }else{?>
                                        <span id="status_<?php echo $record['id'];?>"><a href="javascript:change_statusfactuur(<?php echo $record['id'];?>,'publish');" class="label label-danger">Niet betaald</a></span>
                                    <?php }?>
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                        onclick="Factuur_Edit('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                        class="btn btn-info btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button"
                                        onclick="Factuur_Delete('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                        class="btn btn-danger btn-xs">
                                        <i class="fa fa-minus-circle"></i>
																	  </button>
                                    <button type="button"
                                        onclick="Factuur_Pdf('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                        class="btn btn-danger btn-xs">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>
																	  <button type="button"
                                        onclick="goToURL('<?php echo (int)$record['id'];?>'); return false;"
                                        class="btn btn-alert btn-xs">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php }?>
                    <?php }else{ ?>
                            <tr>
                                <td id="data_empty">Empty!</td>
                            </tr>
                            <script>
                                $(document).ready(function(){
                                    $('#data_empty').attr('colspan',$('#frm-bulk-action  th').length).css({'text-align':'center'});
                                });
                            </script>
                    <?php } ?>
                </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="search_modal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span
                        class="sr-only">Sluiten</span>
                </button>
                <h4 class="modal-title">Factuur zoeken </h4>
            </div>
            <form name="frm_Factuur_Search"
                id="frm_Factuur_Search" class="form-horizontal"
                role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="subject"
                            class="col-sm-2 control-label">
                            Factuurnummer (zonder jaarnummer)
                        </label>
                                <div class="col-sm-10">
                            <?php echo form_input("search[id]",(isset($this->controller->get->search["id"]))?$this->controller->get->search["id"]:""); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject"
                            class="col-sm-2 control-label">
                            Klant ID
                        </label>
                                <div class="col-sm-10">
                            <?php echo form_input("search[factuur_klantid]",(isset($this->controller->get->search["factuur_klantid"]))?$this->controller->get->search["factuur_klantid"]:""); ?>
                        </div>
                    </div>
                <?php echo Pf::event()->trigger("filter","factuur-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Factuur_Search();">
                    <i class='fa fa-search'></i> Zoeken</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $this->pagination->page_lable(); ?>
    </div>
    <div class="col-sm-6">
        <div class="pull-right">
            <?php echo $this->pagination->page_links(admin_url($this->page.'=') . '&'); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'inline-block'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	
	try{
       tinymce.remove();
    }catch(e){}
	
	$('#check-all').on('ifChecked', function(event){
		$('input[name="id\[\]"]').iCheck('check');
		$('#bulk-action-container').show();
	});

	$('#check-all').on('ifUnchecked', function(event){
		$('input[name="id\[\]"]').iCheck('uncheck');
		$('#bulk-action-container').hide();
	});

	$('input[name="id\[\]"]').on('ifToggled', function(event){
	    if ($('#main-content input[name="id\[\]"]:checked').length > 0){
	    	$('#bulk-action-container').show();
	    }else{
	    	$('#bulk-action-container').hide();
	    	$('#check-all').iCheck('uncheck');
	    }
	});
	
	$('.pagination a').click( function(event) {
		$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
		$('#main-content').load($(this).data('url')+$(this).val(),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
	});
});


function bulk_action(type){
	if (type != undefined){
		$.sModal({
	    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
	    	content:'<?php echo __('Are you sure with this action?','testimonials'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> <?php echo __('OK','testimonials'); ?>',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
    	              				 if (obj.error == 0){
    	              				   switch(obj.action){
    	              				        case 'delete':
    	    	              				    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Testimonial is deleted successfully','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    	              					    break;
    	              					    case 'publish':
  	    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i>  Betaald",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
  	    	              				    break;
  	    	              				    case 'unpublish':
  	    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> Niet betaald",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
  											break;
    	              				   }
    	              				}else{
  	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
  	              				   }
    	              				});
    	              			  $.sModal('close',m_id);
	              			    },'json');
	                     }
	                 },
	                 {
	                     text:'Cancel',
	                     click:function(id,data){
	                         $.sModal('close',id);
	                     }
	                 },
	             ]
	    });
	}
}

function Factuur_Search_modal(){
    $('#search_modal').modal('show');
}

function Factuur_Search(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Factuur_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Factuurklanten_Search(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Factuur_Add(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function Factuur_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
	
function Factuur_Pdf(id,token){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	$.get('<?php echo admin_url($this->action.'=pdf');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
	
	
function goToURL(id) {
      location.href = 'http://coensteven.nl/cms/factuur.php?id='+id;
}


function Factuur_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','testimonials'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','testimonials'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Testimonial is deleted successfully','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }
                    		 $('#main-content').load(obj.url,function(){
                    			 $("#main-content").unmask();
                    			 $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                         $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
                    		 });
                    		 $.sModal('close',m_id);
                    	 },'json');
                     }
                 },
                 {
                     text:'Cancel',
                     click:function(id,data){
                         $.sModal('close',id);
                     }
                 },
             ]
    });
	
}

function Testimonials_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
    var order_type = '';
    if ($(obj).find('.fa-sort-asc').length <= 0){
        order_type = 'asc';  
    }else{
        order_type = 'desc';
    }
    
    $('#main-content').load('<?php echo admin_url('order_field=&order_type='); ?>&order_field=' + field +'&order_type=' + order_type ,function(){
        $("#main-content").unmask();
        $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}

//Change status
function change_statusfactuur(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_statusfactuur');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish") {
            result = "<a href=\"javascript:change_statusfactuur(" + id + ",'unpublish')\" class=\"label label-success\">Betaald</a>";
        } else {
        	result = "<a href=\"javascript:change_statusfactuur(" + id + ",'publish')\" class=\"label label-danger\">Niet betaald</a>";
        }
        $('#status_' + id).html(result);

    });
}

//Change all status
function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status,id: id},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(id == 1){
    		$('#view-title').html('Actief');
    	}else if(id == 2){
    		$('#view-title').html('Niet actief');
    	}
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>
<style type="text/css">
table.table.table-bordered tr td {
    vertical-align: middle;
}

span#view-title {
    text-transform: capitalize;
}
</style>