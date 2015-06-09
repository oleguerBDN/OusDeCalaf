<?php
include '../../includes/general_settings.php';
include BASE_PATH.'admin/functions_display.php';
$c = $_POST['id'];
$c++;
$output .= '<div id="banner_'.$c.'"><fieldset><legend></legend>
                <div><a class="btn btn-danger" onClick="delImg(-1,'.$c.');">
                            <i class="fa-icon-trash"></i> 
                            </a></div>';
    $output .=  '<div class="control-group">
                       <label class="control-label" for="df">Alt imágen</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.$c.'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.$c.'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_1" name="galeria_img['.$c.'][alt_img][1]" type="text" value="">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_2" name="galeria_img['.$c.'][alt_img][2]" type="text" value="">
                                </div></div></div>
                             </div>   
                            <input value="" type="hidden" name="galeria_img['.$c.'][alt_img][itemLiteralId]" type="text">
                       </div>
                   </div>';
    
    $output .=     '<div class="control-group">
                       <label class="control-label" for="df">Desc imágen</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.($c + 1).'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.($c + 1).'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_1" name="galeria_img['.$c.'][desc_img][1]" type="text" value="">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_2" name="galeria_img['.$c.'][desc_img][2]" type="text" value="">
                                </div></div></div>
                             </div>   
                            <input value="" type="hidden" name="galeria_img['.$c.'][desc_img][itemLiteralId]" type="text">
                       </div>
                   </div>
                   <div class="control-group">
                       <label class="control-label" for="df">Imágen</label>
                       <div class="controls">
                           <input value="" class="input-xlarge focused" id="" name="galeria_img['.$c.'][galeria_img]" type="file">
                           <input type="hidden" name="galeria_img['.$c.'][itemid]" value="-1">
                       </div>
                   </div>
                   </fieldset></div>';
echo $output;
?>
