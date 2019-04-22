<?php if (!empty($_SESSION['logado'])) { ?>
            </div>
        </div>
    </div>
<?php } ?>
    <script src="<?php echo app::dominio; ?>view/assets/js/jquery-1.10.2.js"></script>
    <script src="<?php echo app::dominio; ?>view/assets/js/bootstrap.min.js"></script>
	<script src="<?php echo app::dominio; ?>view/assets/materialize/js/materialize.min.js"></script>
    <script src="<?php echo app::dominio; ?>view/assets/js/jquery.metisMenu.js"></script>
	<script src="<?php echo app::dominio; ?>view/assets/js/easypiechart.js"></script>
	<script src="<?php echo app::dominio; ?>view/assets/js/easypiechart-data.js"></script>
	<script src="<?php echo app::dominio; ?>view/assets/js/Lightweight-Chart/jquery.chart.js"></script>
    <!-- DataTables JS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.20/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.20/vfs_fonts.js"></script>
    <script src="<?php echo app::dominio; ?>view/assets/js/custom-scripts.js"></script> 
    <script src="<?php echo app::dominio; ?>view/assets/js/function.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
</body>
<script type="text/javascript">
    $('.select2class').select2();
</script>
</html>