    <div class="modal fade" id="quitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="smallModalLabel">Quit?</h4>
                </div>
                <div id="message" class="modal-body">
    
                </div>
                <div class="modal-footer">
                    <button id="quitbtn" type="button" class="btn btn-link waves-effect">Ok</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">var host = "<?= $this->req->urlmain?>";</script>
    <!-- Jquery Core Js -->
    <script src="<?= $this->temp->public?>plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?= $this->temp->public?>plugins/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?= $this->temp->public?>js/lib/jquery.autocomplete.min.js"></script>

    <!-- Select Plugin Js -->
    <script src="<?= $this->temp->public?>plugins/bootstrap-select/js/bootstrap-select.min.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?= $this->temp->public?>plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?= $this->temp->public?>plugins/node-waves/waves.min.js"></script>

    <!-- Chart Plugins Js -->
    <script src="<?= $this->temp->public?>plugins/chartjs/Chart.bundle.js"></script>

    <!-- Custom Js -->
    <script src="<?= $this->temp->public?>js/admin.js"></script>
    <!-- Demo Js -->
    <!-- <script src="<?= $this->temp->public?>js/demo.js"></script> -->
    <!-- <script src="<?= $this->temp->public?>js/custom/user.js"></script> -->

    <?php if(isset($scripts)):?>
        <?php foreach($scripts as $index => $fileloc):?>
            <script src="<?=$fileloc?>"></script>
        <?php endforeach ?>
    <?php endif?>
    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
    <!--<script>-->
    <!--  (adsbygoogle = window.adsbygoogle || []).push({-->
    <!--    google_ad_client: "ca-pub-4611153084513069",-->
    <!--    enable_page_level_ads: true-->
    <!--  });-->
    <!--</script>-->
    </body>
</html>