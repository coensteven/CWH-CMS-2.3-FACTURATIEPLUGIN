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
            Factuur  <small>PDF</small>
        </h3>
    </div>
</div>
<?php if (!empty($this->records3) && is_array($this->records3)){?>
                    <?php
                        
                        foreach ( $this->records3 as $index => $record ) {
                            $token = Pf_Plugin_CSRF::token ( $this->key . $record ['id'] );
                            ?>
														<iframe src="http://coensteven.nl/cms/factuur.php?id=<?php echo $record ['id']; ?>" width="100%" height="700px"></iframe>
                            <tr>
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